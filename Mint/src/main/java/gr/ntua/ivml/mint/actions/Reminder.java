package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.util.MailSender;

import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Random;

import javax.servlet.http.HttpServletRequest;

import org.apache.log4j.Logger;
import org.apache.struts2.ServletActionContext;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.InterceptorRef;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.dispatcher.ServletActionRedirectResult;
import org.apache.struts2.interceptor.SessionAware;

@Results( {
		@Result(name = "input", location = "reminder.jsp"),
		@Result(name = "error", location = "reminder.jsp"),
		@Result(name = "success", location = "reminderty.jsp") })
public class Reminder extends GeneralAction {

	protected final Logger log = Logger.getLogger(getClass());

	//The length of the generated password
	private static final int PASSWORD_LENGTH = 8;
	
	private static final Random random = new Random(System.currentTimeMillis());
    private static final char[] alphaNumberic = new char[]{'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'};



	private String username;
	
	private String mailResult;
	
	public String getMailResult() {
		return mailResult;
	}

	public void setMailResult(String mailResult) {
		this.mailResult = mailResult;
	}

	@Action(value = "Reminder", interceptorRefs = @InterceptorRef("defaultStack"))
	public String execute() throws Exception {

		User user = null;
		String result = "";
		
		if (username == null || username.length() == 0) {
			addFieldError("username", "Username is required");

		}
		if (!getFieldErrors().isEmpty()) {
			return ERROR;

		}
		
		user = DB.getUserDAO().getByLogin(getUsername());
				
		if (user != null) {
			if (!user.isAccountActive()) {
				addActionError("account is no longer active");
				return ERROR;
			} else if (user.getPasswordExpires() != null
					&& user.getPasswordExpires().getTime() < (new Date()
							.getTime())) {
				addActionError("your password has expired");
				return ERROR;
			} else {
				log.debug("Login successful for user:" + user.getLogin());
				HttpServletRequest request = ServletActionContext.getRequest();
				log.info("Generating new password for user " + getUsername() + " after a request from " + request.getRemoteAddr());
				String newPassword = getRandomPassword();
				user.encryptAndSetLoginPassword(getUsername(), newPassword);
				DB.getUserDAO().makePersistent(user);
				MailSender ms = new MailSender();
				result = ms.send(Config.get("mail.admin"), "New password for " + Config.get("mint.title"), "Dear Sir/Madam, \nYour new password is " + newPassword + ".", user.getEmail());
			}
		} else {
			addActionError("wrong username");
			return ERROR;
		}
		if(result.contains("Error")) {
			addActionError("There was an error sending you the new password via email. Please try again later.");
			log.warn("Error sending password reminder email:" + result);
			return ERROR;
		}
		this.mailResult = result;
		log.info("Redirecting to " + SUCCESS);
		return SUCCESS;
	}

	public String getUsername() {

		return username;
	}

	public void setUsername(String username) {

		this.username = username;
	}

	
	 @Override
	    @Action(value="Reminder_input",interceptorRefs=@InterceptorRef("defaultStack"))  
	    public String input() throws Exception {
	    	return super.input();
	    }
	 
	private String getRandomPassword() {
		String password = "";
		for (int i = 0; i < PASSWORD_LENGTH; i++) {
             int r = random.nextInt(alphaNumberic.length);
             password += alphaNumberic[r];
		}

		return password;
	}

}