/* Javascript file for AJAX requests, form submission, and validation for
 * editing profile information.
 */

var emailValid = false;
var usernameValid = false;
var passwordValid = false;

//Function to validate an email address, returning true if the given
//variable is a properly formatted email address
function emailValidation(email)
{
    var regex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return regex.test(email);
}

//Function to validate a username, returning true if the given username
//variable is only alphanumeric plus underscores
function usernameValidation(username)
{
    var regex = /^[a-zA-Z0-9_]+$/;
    return regex.test(username);
}

//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //When the user enters a username, make sure it is valid
    $('#username').on('change', function() {
        if($(this).val().length == 0)
        {
            $('#usernameValidation').text('You must enter a username.');
            usernameValid = false;
        }
        else if($(this).val().length > 30)
        {
            $('#usernameValidation').text('Username cannot exceed 30 characters.');
            usernameValid = false;
        }
        else if(!usernameValidation($(this).val()))
        {
            $('#usernameValidation').text('Username can only consist of letters, numbers, and underscores.');
            usernameValid = false;
        }
        else
        {
            var username = $(this).val();
            
            //Check if username already exists
            request = $.ajax({
                url: "editAccountAjax.php",
                type: "POST",
                data: {'username': username, 'action': 3}
            });
            
            //If the username exists, let the user know, otherwise clear validation
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    $("#usernameValidation").text("");
                    usernameValid = true;
                }
                else
                {
                    $("#usernameValidation").text(data);
                    usernameValid = false;
                }
            });
            
            //Warn the user of error checking for username
            request.fail(function(jqXHR, textStatus, errorThrown) {
                $("#usernameValidation").text("Error checking if username already exists.");
                usernameValid = true;
            });            
        }
    });

    //When the user enters an email, make sure it is valid
    $('#email').on('change', function() {
        if(!emailValidation($(this).val()))
        {
            $('#emailValidation').text('Invalid email entered.');
            emailValid = false;
        }
        else if($(this).val().length > 255)
        {
            $('#emailValidation').text('Email address too long.');
            emailValid = false;
        }
        else
        {
            emailValid = true;
            $('#emailValidation').text('');
        }
    });

    //Make sure passwords are valid and match
    $('#password1').on('change', function() {
        if($(this).val().length < 8)
        {
            $('#password1Validation').text('Passwords must be at least 8 characters long.');
            passwordValid = false;
        }
        else if($('#password2').val().length >= 8 &&
                $(this).val() != $('#password2').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('Passwords do not match.');
            passwordValid = false;
        }
        else if($('#password2').val().length >= 8 &&
                $(this).val() == $('#password2').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            passwordValid = true;
        }
        else if($('#password2').val().length < 8)
        {
            $('#password1Validation').text('');
            passwordValid = false;
        }
        else
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            passwordValid = false;
        }
    });

    //Make sure passwords are valid and match
    $('#password2').on('change', function() {
        if($(this).val().length < 8)
        {
            $('#password2Validation').text('Passwords must be at least 8 characters long.');
            passwordValid = false;
        }
        else if($('#password1').val().length >= 8 &&
                $(this).val() != $('#password1').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('Passwords do not match.');
            passwordValid = false;
        }
        else if($('#password1').val().length >= 8 &&
                $(this).val() == $('#password1').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            passwordValid = true;
        }
        else if($('#password1').val().length < 8)
        {
            $('#password2Validation').text('');
            passwordValid = false;
        }
        else
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            passwordValid = false;
        }
    });
    
    //On submit, register the user and link them to
    //the login page if the registration succeeds
    $('#registrationForm').submit(function() {
        $('#username').change();
        $('#email').change();
        $('#password1').change();
        $('#password2').change();
        if(usernameValid && emailValid && passwordValid)
        {
            var serializedForm = $(this).serialize() + "&action=2";
            request = $.ajax({
                url: "editAccountAjax.php",
                type: "POST",
                data: serializedForm
            });
            
            //If correct, redirect the user, otherwise warn them of the error
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                    window.location.href = "./index.php";
                else
                    alert(data);
            });
            
            //Warn the user of the error
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Error sending registration information.");
            });
        }
        return false;
    });
});
