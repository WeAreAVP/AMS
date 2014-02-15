$().ready(function() {
    $("#loginForm").validate({
            rules: {j_username: "required",j_password: "required"},
            messages: {j_username: "",j_password: ""}
     });
    $("#forgotemailForm").validate({
            rules: {email: "required"},
            messages: {email: ""}
     });
    $("#registrationForm").validate({
            rules: {email: "required",iagree: "required"},
            messages: {email: "",iagree: msgRequired }
            //msgRequired is generated in inc_header.ftl and
            // set as a javascript variable (its a spring message
            // so cannot be generated in this js file
     });
});