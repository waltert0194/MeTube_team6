
var isEmail = true;
var isBio = true;
var isCurrentPass = false;
var isNewPass = false;

//ensures that email address is valid format
function validateEmail(email)
{
    var regex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return regex.test(email);
}

//.onclick functions
$(document).ready(function() {


    function setForm() {
        
        isEmail = true;
        isBio = true;
        isCurrentPass = false;
        isNewPass = false;

        if($('#updateProfileMeta').length)
        {
            $('#updateEmailaddr').on('change', function() {
                $("#updateProfileValidation").text('');
                if(!validateEmail($(this).val()))
                {
                    $('#updatevalidateEmail').text('Invalid email address.');
                    isEmail = false;
                }
                else if($(this).val().length > 255)
                {
                    $('#updatevalidateEmail').text('Your email is too long.');
                    isEmail = false;
                }
                else
                {
                    isEmail = true;
                    $('#updatevalidateEmail').text('');
                }
            });

            $('#updateBio').on('change', function() {
                $("#updateProfileValidation").text('');
                if($(this).val().length > 750)
                {
                    $('#updateisBioation').text('Bio cannot be longer than 750 characters.');
                    isBio = false;
                }
                else
                {
                    $('#updateisBioation').text('');
                    isBio = true;
                }
            });

            //update profile information
            $('#updateProfileMeta').submit(function(){
                $("#updateProfileValidation").text('');
                if(isEmail && isBio)
                {
                    var serializedForm = $(this).serialize() + "&action=0";
                    request = $.ajax({
                        url: "editAccountAjax.php",
                        type: "POST",
                        data: serializedForm
                    });
                    
                    //feedback for request
                    request.done(function(data, textStatus, jqXHR) {
                        if(data === "success")
                            $("#updateProfileValidation").text("Successfully updated profile metadata.");
                        else
                            $("#updateProfileValidation").text("Profile failed to update.");
                    });
                    
                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        $("#updateProfileValidation").text("Profile failed to update.");
                    });
                }
                return false;
            });
        }


        if($('#updatePasswordinfo').length)
        {
            //Make sure the user enters their current password
            $('#currentPassword').on('change', function() {
                $("#validateUpdatedPassword").text('');
                if($(this).val().length == 0)
                {
                    $('#currentPasswordValidation').text('You must enter your current password.');
                    isCurrentPass = false;
                }
                else
                {
                    $('#currentPasswordValidation').text('');
                    isCurrentPass = true;
                }
            });

            //Make sure new passwords are valid
            $('#passwordMatch1').on('change', function() {
                $("#validateUpdatedPassword").text('');
                if($(this).val().length < 8)
                {
                    $('#validatePasswordMatch1').text('You password must be at least 8 characters in length.');
                    isNewPass = false;
                }
                else if($('#passwordMatch2').val().length >= 8 &&
                        $(this).val() != $('#passwordMatch2').val())
                {
                    $('#validatePasswordMatch1').text('');
                    $('#validatePasswordMatch2').text('New password does not match.');
                    isNewPass = false;
                }
                else if($('#passwordMatch2').val().length >= 8 &&
                        $(this).val() == $('#passwordMatch2').val())
                {
                    $('#validatePasswordMatch1').text('');
                    $('#validatePasswordMatch2').text('');
                    isNewPass = true;
                }
                else if($('#passwordMatch2').val().length < 8)
                {
                    $('#validatePasswordMatch1').text('');
                    isNewPass = false;
                }
                else
                {
                    $('#validatePasswordMatch1').text('');
                    $('#validatePasswordMatch2').text('');
                    isNewPass = false;
                }
            });

            //Make sure new passwords are valid and match
            $('#passwordMatch2').on('change', function() {
                $("#validateUpdatedPassword").text('');
                if($(this).val().length < 8)
                {
                    $('#validatePasswordMatch2').text('Passwords must have at least 8 characters.');
                    isNewPass = false;
                }
                else if($('#passwordMatch1').val().length >= 8 &&
                        $(this).val() != $('#passwordMatch1').val())
                {
                    $('#validatePasswordMatch1').text('');
                    $('#validatePasswordMatch2').text('New password does not match.');
                    isNewPass = false;
                }
                else if($('#passwordMatch1').val().length >= 8 &&
                        $(this).val() == $('#passwordMatch1').val())
                {
                    $('#validatePasswordMatch1').text('');
                    $('#validatePasswordMatch2').text('');
                    isNewPass = true;
                }
                else if($('#passwordMatch1').val().length < 8)
                {
                    $('#validatePasswordMatch2').text('');
                    isNewPass = false;
                }
                else
                {
                    $('#validatePasswordMatch1').text('');
                    $('#validatePasswordMatch2').text('');
                    isNewPass = false;
                }
            });

            //update pass
            $('#updatePasswordinfo').submit(function(){
                $("#validateUpdatedPassword").text('');
                $("#currentPassword").change();
                $("#passwordMatch1").change();
                $("#passwordMatch2").change();
                if(isCurrentPass && isNewPass)
                {
                    var serializedForm = $(this).serialize() + "&action=1";
                    request = $.ajax({
                        url: "editAccountAjax.php",
                        type: "POST",
                        data: serializedForm
                    });
                    
                    //Let the user know the status of their request
                    request.done(function(data, textStatus, jqXHR) {
                        if(data === "success")
                        {
                            $("#validateUpdatedPassword").text("Successfully updated password.");
                            //Clear password form
                            isCurrentPass = false;
                            isNewPass = false;
                            $("#currentPassword").val("");
                            $("#passwordMatch1").val("");
                            $("#passwordMatch2").val("");
                        }
                        else
                            $("#validateUpdatedPassword").text(data);
                    });
                    
                    request.fail(function(jqXHR, textStatus, errorThrown) {
                        $("#validateUpdatedPassword").text("Password not updated successfully.");
                    });
                }
                return false;
            });
        }
    }


    //Set the onclick for the Edit Profile Information nav button
    $('#editProfileTabButton').click(function() {
        if(!($(this).hasClass('active')))
        {
            var button = $(this);
                
            request = $.ajax({
                url: "editprofileinfo.php",
                type: "POST"
            });

            //Page is successfully loaded
            request.done(function(data, textStatus, jqXHR) {
                $('.account-edit-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#editAccountMeta').html(data);
                setForm();
            });
           
            //Page is not loaded successfully
            request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load profile edit form.");
            });        
        }
    }); 

    //Set the onclick for the Update Password nav button
    $('#changePasswordBtn').click(function() {
        if(!($(this).hasClass('active')))
        {
            var button = $(this);
                
            request = $.ajax({
                url: "updatepassword.php",
                type: "POST"
            });

            //Page is successfully loaded
            request.done(function(data, textStatus, jqXHR) {
                $('.account-edit-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#editAccountMeta').html(data);
                setForm();
            });
           
            //Page is not loaded successfully
            request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load password update form.");
            });
        }
    });

    //Set the initial events for the form
    setForm();
});
