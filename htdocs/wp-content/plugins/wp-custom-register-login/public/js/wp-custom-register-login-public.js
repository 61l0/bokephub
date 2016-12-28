(function($) {
    'use strict';

    $(document).ready(initScript);

    function initScript() {

        //defing global ajax post url
        window.ajaxPostUrl = ajax_object.ajax_url;
        // validating login form request
        wpcrlValidateAndProcessLoginForm();
        // validating registration form request
        wpcrlValidateAndProcessRegisterForm();
        // validating reset password form request
        wpcrlValidateAndProcessResetPasswordForm();
        //Show Reset password
        wpcrlShowResetPasswordForm();
        //Return to login
        wpcrlReturnToLoginForm();
        generateCaptcha();

    }

    // Validate login form
    function wpcrlValidateAndProcessLoginForm() {
        $('#wpcrlLoginForm').formValidation({
            message: 'This value is not valid',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpcrl_username: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The username is required.'
                        }
                    }
                },
                wpcrl_password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required.'
                        }
                    }
                }
            }
        }).on('success.form.fv', function(e) {
            $('#wpcrl-login-alert').hide();
            // You can get the form instance
            var $loginForm = $(e.target);
            // and the FormValidation instance
            var fv = $loginForm.data('formValidation');
            var content = $loginForm.serialize();

            // start processing
            $('#wpcrl-login-loader-info').show();
            wpcrlStartLoginProcess(content);
            // Prevent form submission
            e.preventDefault();
        });
    }

    // Make ajax request with user credentials
    function wpcrlStartLoginProcess(content) {

        var loginRequest = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            data: content + '&action=wpcrl_user_login',
            dataType: 'json',
            success: function(data) {
                $('#wpcrl-login-loader-info').hide();
                // check login status
                if (true == data.logged_in) {
                    $('#wpcrl-login-alert').removeClass('alert-danger');
                    $('#wpcrl-login-alert').addClass('alert-success');
                    $('#wpcrl-login-alert').show();
                    $('#wpcrl-login-alert').html(data.success);

                    // redirect to redirection url provided
                    window.location = data.redirection_url;

                } else {

                    $('#wpcrl-login-alert').show();
                    $('#wpcrl-login-alert').html(data.error);

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    // Validate registration form


    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    function generateCaptcha() {
        $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));
    }

    // Validate registration form
    function wpcrlValidateAndProcessRegisterForm() {
        $('#wpcrlRegisterForm').formValidation({
            message: 'This value is not valid',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpcrl_fname: {
                    validators: {
                        notEmpty: {
                            message: 'The first name is required'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The firstname must be less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z]*$/,
                            message: 'Only characters are allowed.'
                        }
                    }
                },
                wpcrl_username: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The username is required'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: 'The username must be more than 6 and less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: 'The username can only consist of alphabetical, number, dot and underscore'
                        }
                    }
                },
                wpcrl_email: {
                    validators: {
                        notEmpty: {
                            message: 'The email is required'
                        },
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'The value is not a valid email address'
                        }
                    }
                },
                wpcrl_password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        stringLength: {
                            min: 6,
                            message: 'The password must be more than 6 characters long'
                        }
                    }
                },
                wpcrl_password2: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        identical: {
                            field: 'wpcrl_password',
                            message: 'The password and its confirm are not the same'
                        },
                        stringLength: {
                            min: 6,
                            message: 'The password must be more than 6 characters long'
                        }
                    }
                },
                wpcrl_captcha: {
                    validators: {
                        callback: {
                            message: 'Wrong answer',
                            callback: function(value, validator, $field) {
                                var items = $('#captchaOperation').html().split(' '),
                                        sum = parseInt(items[0]) + parseInt(items[2]);
                                return value == sum;
                            }
                        }
                    }
                }
            }
        }).on('success.form.fv', function(e) {
            $('#wpcrl-register-alert').hide();
            $('#wpcrl-mail-alert').hide();
            $('body, html').animate({
                scrollTop: 0
            }, 'slow');
            // You can get the form instance
            var $registerForm = $(e.target);
            // and the FormValidation instance
            var fv = $registerForm.data('formValidation');
            var content = $registerForm.serialize();

            // start processing
            $('#wpcrl-reg-loader-info').show();
            wpcrlStartRegistrationProcess(content);
            // Prevent form submission
            e.preventDefault();
        }).on('err.form.fv', function(e) {
            // Regenerate the captcha
            generateCaptcha();
        });
    }


    // Make ajax request with user credentials
    function wpcrlStartRegistrationProcess(content) {

        var registerRequest = $.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            data: content + '&action=wpcrl_user_registration',
            dataType: 'json',
            success: function(data) {

                $('#wpcrl-reg-loader-info').hide();
                //check mail sent status
                if (data.mail_status == false) {

                    $('#wpcrl-mail-alert').show();
                    $('#wpcrl-mail-alert').html('Could not able to send the email notification.');
                }
                // check login status
                if (true == data.reg_status) {
                    $('#wpcrl-register-alert').removeClass('alert-danger');
                    $('#wpcrl-register-alert').addClass('alert-success');
                    $('#wpcrl-register-alert').show();
                    $('#wpcrl-register-alert').html(data.success);

                } else {
                    $('#wpcrl-register-alert').addClass('alert-danger');
                    $('#wpcrl-register-alert').show();
                    $('#wpcrl-register-alert').html(data.error);

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function wpcrlShowResetPasswordForm() {
        $('#btnForgotPassword').click(function() {
              $('#wpcrlResetPasswordSection').removeClass('hidden');
              $('#wpcrlLoginForm').slideUp(500);  
               $('#wpcrlResetPasswordSection').slideDown(500);
        });
    }
    
    function wpcrlReturnToLoginForm() {
        $('#btnReturnToLogin').click(function() {
              $('#wpcrlResetPasswordSection').slideUp(500);              
              $('#wpcrlResetPasswordSection').addClass('hidden');
              $('#wpcrlLoginForm').removeClass('hidden');
              $('#wpcrlLoginForm').slideDown(500);               
        });
    }

    // Validate reset password form
    //Neelkanth
    function wpcrlValidateAndProcessResetPasswordForm() {

        $('#wpcrlResetPasswordForm').formValidation({
            message: 'This value is not valid',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpcrl_rp_email: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your email address which you used during registration.'
                        },
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'The value is not a valid email address'
                        }
                    }
                },
                wpcrl_newpassword: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        stringLength: {
                            min: 6,
                            message: 'The password must be more than 6 characters long'
                        }
                    }
                }
            }
        }).on('success.form.fv', function(e) {
            $('#wpcrl-resetpassword-alert').hide();

            $('body, html').animate({
                scrollTop: 0
            }, 'slow');
            // You can get the form instance
            var $resetPasswordForm = $(e.target);
            // and the FormValidation instance
            var fv = $resetPasswordForm.data('formValidation');
            var content = $resetPasswordForm.serialize();
            
            // start processing
            $('#wpcrl-resetpassword-loader-info').show();
            wpcrlStartResetPasswordProcess(content);
            // Prevent form submission
            e.preventDefault();
        });
    }

    // Make ajax request with email
    //Neelkanth
    function wpcrlStartResetPasswordProcess(content) {
        
        var resetPasswordRequest = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            data: content + '&action=wpcrl_resetpassword',
            dataType: 'json',
            success: function(data) {
                
                $('#wpcrl-resetpassword-loader-info').hide();
                // check login status
                if (data.success) {
                    
                    $('#wpcrl-resetpassword-alert').removeClass('alert-danger');
                    $('#wpcrl-resetpassword-alert').addClass('alert-success');
                    $('#wpcrl-resetpassword-alert').show();
                    $('#wpcrl-resetpassword-alert').html(data.success);

                } else {

                    $('#wpcrl-resetpassword-alert').show();
                    $('#wpcrl-resetpassword-alert').html(data.error);

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }



})(jQuery);
