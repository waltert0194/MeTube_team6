
var isEmail = false;
var isUsername = false;
var isPass = false;

function emailValidation(email)
{
    var regex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return regex.test(email);
}

function isUsernameation(username)
{
    var regex = /^[a-zA-Z0-9_]+$/;
    return regex.test(username);
}

//.onclick function
$(document).ready(function() {

    //validate username
    $('#username').on('change', function() {
        if($(this).val().length == 0)
        {
            $('#isUsernameation').text('Cannot have empty username.');
            isUsername = false;
        }
        else if($(this).val().length > 30)
        {
            $('#isUsernameation').text('Username cannot be more than 30 characters.');
            isUsername = false;
        }
        else if(!isUsernameation($(this).val()))
        {
            $('#isUsernameation').text('Username can include numbers, letters, and underscores_.');
            isUsername = false;
        }
        else
        {
            var username = $(this).val();
            request = $.ajax({
                url: "editAccountAjax.php",
                type: "POST",
                data: {'username': username, 'action': 3}
            });
            
            //success
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    $("#isUsernameation").text("");
                    isUsername = true;
                }
                else
                {
                    $("#isUsernameation").text(data);
                    isUsername = false;
                }
            });
            
            //failure
            request.fail(function(jqXHR, textStatus, errorThrown) {
                $("#isUsernameation").text("Could not determine is username exists.");
                isUsername = true;
            });            
        }
    });

    //validate email
    $('#email').on('change', function() {
        if(!emailValidation($(this).val()))
        {
            $('#emailValidation').text('Invalid email.');
            isEmail = false;
        }
        else if($(this).val().length > 255)
        {
            $('#emailValidation').text('Email too long.');
            isEmail = false;
        }
        else
        {
            isEmail = true;
            $('#emailValidation').text('');
        }
    });

    //validate password
    $('#password1').on('change', function() {
        if($(this).val().length < 8)
        {
            $('#password1Validation').text('Passwords need to be more than 8 characters');
            isPass = false;
        }
        else if($('#password2').val().length >= 8 &&
                $(this).val() != $('#password2').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('Passwords do not match.');
            isPass = false;
        }
        else if($('#password2').val().length >= 8 &&
                $(this).val() == $('#password2').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            isPass = true;
        }
        else if($('#password2').val().length < 8)
        {
            $('#password1Validation').text('');
            isPass = false;
        }
        else
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            isPass = false;
        }
    });

    //validate passwords
    $('#password2').on('change', function() {
        if($(this).val().length < 8)
        {
            $('#password2Validation').text('Passwords need to be longer than 8 characters.');
            isPass = false;
        }
        else if($('#password1').val().length >= 8 &&
                $(this).val() != $('#password1').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('Passwords do not match.');
            isPass = false;
        }
        else if($('#password1').val().length >= 8 &&
                $(this).val() == $('#password1').val())
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            isPass = true;
        }
        else if($('#password1').val().length < 8)
        {
            $('#password2Validation').text('');
            isPass = false;
        }
        else
        {
            $('#password1Validation').text('');
            $('#password2Validation').text('');
            isPass = false;
        }
    });
    
    
    $('#registrationForm').submit(function() {
        $('#username').change();
        $('#email').change();
        $('#password1').change();
        $('#password2').change();
        if(isUsername && isEmail && isPass)
        {
            var serializedForm = $(this).serialize() + "&action=2";
            request = $.ajax({
                url: "editAccountAjax.php",
                type: "POST",
                data: serializedForm
            });
            
            //success
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                    window.location.href = "./index.php";
                else
                    alert(data);
            });
            
            //failure
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("could not register.");
            });
        }
        return false;
    });
});
