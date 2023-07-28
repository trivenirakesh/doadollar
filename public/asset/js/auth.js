$.validator.addMethod("pwcheck", function (value) {
    return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
        && /[a-z]/.test(value) // has a lowercase letter
        && /\d/.test(value) // has a digit
});

// Reset password form validation 
$("#resetPasswordFrm").validate({
    rules: {
        password: {
            required: true,
            minlength: 8,
            pwcheck: true
        },
        password_confirmation: {
            required: true,
            equalTo: "#password"
        },
    },
    messages: {
        password: {
            required: "Please enter password",
            minlength: "Password must 8 characters",
            pwcheck: 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.'
        },
        password_confirmation: {
            required: "Please enter confirm password",
            equalTo: "Confirm password and password must equal",
        },
    },
});

// Login form validation 
$("#loginFrm").validate({
    rules: {
        email: {
            required: true,
            email: true,
        },
        password: {
            required: true,
        },
    },
    messages: {
        email: {
            required: "Please enter email",
            email: "Email format is not correct",
        },
        password: {
            required: "Please enter password",
        },
    },
});

