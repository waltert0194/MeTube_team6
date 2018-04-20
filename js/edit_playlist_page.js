
var isName = true;
var isDescript = true;

//.onclick functions
$(document).ready(function() {

    if($('#editPlaylistMeta').length)
    {
        //Make sure a name is entered and not too long
        $('#name').on('change', function() {
            $("#isNameation").text('');
            if($(this).val().length == 0)
            {
                $('#isNameation').text('Your playlist must have a name.');
                isName = false;
            }
            else if($(this).val().length > 40)
            {
                $('#isNameation').text('Playlist names cannot be longer than 40 characters.');
                isName = false;
            }
            else
            {
                $('#isNameation').text('');
                isName = true;
            }
        });

        //description rules
        $('#description').on('change', function() {
            $("#isDescription").text('');
            if($(this).val().length > 1000)
            {
                $('#isDescription').text('Playlist descriptions cannot exceed 1000 characers.');
                isDescript = false;
            }
            else
            {
                $('#isDescription').text('');
                isDescript = true;
            }

        });

       //playlist metadata update
        $('#editPlaylistMeta').submit(function(){
            if(isName && isDescript)
            {
                var playlistid = $('#playlistid').val();
                var serializedForm = $(this).serialize() + "&action=0";
                init = $.ajax({
                    url: "editPlaylistAjax.php",
                    type: "POST",
                    data: serializedForm
                });
                
                //success
                init.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        window.location.href = 'playlist.php?id='.concat(playlistid);
                    else
                        alert("Playlist info could not be changed.");
                });
                //failed
                init.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Playlist info could not be changed.");
                });
            }
            return false;
        });
    }
});
