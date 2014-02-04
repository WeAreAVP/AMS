package gr.ntua.ivml.mint.util;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import java.io.IOException;

public class EncodingFilter implements Filter {

	private String encoding = "UTF-8";

	public void destroy() {

	}

	public void doFilter(ServletRequest request, ServletResponse response, FilterChain filterChain) 
		throws IOException, ServletException {

		request.setCharacterEncoding(encoding); 
		response.setCharacterEncoding(encoding); 
		filterChain.doFilter(request, response); 
	}

	public void init(FilterConfig filterConfig) throws ServletException { 
		// TODO Auto-generated method stub String encodingParam = filterConfig.getInitParameter("encoding"); if (encodingParam != null) { encoding = encodingParam; }

	} 
} 