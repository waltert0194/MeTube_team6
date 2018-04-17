/* Javascript file for AJAX inits, form submission, and validation for
 * editing playlist information.
 */

var isName = true;
var isDescript = true;

//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //If the form is loaded, set its validation and submission functions
    if($('#editPlaylistForm').length)
    {
        //Make sure a name is entered and not too long
        $('#name').on('change', function() {
            $("#isNameation").text('');
            if($(this).val().length == 0)
            {
                $('#isNameation').text('You must enter a name for the playlist.');
                isName = false;
            }
            else if($(this).val().length > 40)
            {
                $('#isNameation').text('Playlist names cannot exceed 40 characers.');
                isName = false;
            }
            else
            {
                $('#isNameation').text('');
                isName = true;
            }
        });

        //Make sure the description is not too long
        $('#description').on('change', function() {
            $("#isDescriptation").text('');
            if($(this).val().length > 1000)
            {
                $('#isDescriptation').text('Playlist descriptions cannot exceed 1000 characers.');
                isDescript = false;
            }
            else
            {
                $('#isDescriptation').text('');
                isDescript = true;
            }

        });

        //On submit, update playlist metadata if valid information
        //is entered and redirect the user back if successful.
        $('#editPlaylistForm').submit(function(){
            if(isName && isDescript)
            {
                var playlistid = $('#playlistid').val();
                var serializedForm = $(this).serialize() + "&action=0";
                init = $.ajax({
                    url: "editPlaylistAjax.php",
                    type: "POST",
                    data: serializedForm
                });
                
                //Redirect the user if successful, or alert them if not
                init.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        window.location.href = 'playlist.php?id='.concat(playlistid);
                    else
                        alert("Playlist metadata was not updated successfully.");
                });
                
                init.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Playlist metadata was not updated successfully.");
                });
            }
            return false;
        });
    }
});
