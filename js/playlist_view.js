/* Javascript file for AJAX requests for viewing a playlist, allowing for
 * deletion of media and deletion of playlist.
 */

//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //Set the logic for deleting a playlist
    if($('.btn-delete-playlist').length)
    {
        $('.btn-delete-playlist').click(function(){
		    var playlistid = $('#playlistidJS').val();
            
            request = $.ajax({
					url: "playlistViewAjax.php",
					type: "POST",
					data: {'action': 0, 'playlistid': playlistid}
				});

				request.done(function(data, textStatus, jqXHR) {
					if(data === "success")
                        window.location.href = "./account.php";    
                    else
						alert("Error deleting playlist.");
				});

				request.fail(function(jqXHR, textStatus, errorThrown) {
					alert("Error deleting playlist.");
				})
            
        });
    }

    //Code for removing media from playlists on
    //the playlist page via AJAX
    if($('.btn-delete-playlist-media').length)
    {
        $('.btn-delete-playlist-media').click(function() {
            var playlistid = $('#playlistidJS').val();
            var mediaid = $(this).parent().find('[name="mediaidField"]').val();
            var button = $(this);
            
            request = $.ajax({
                url: "mediaViewAjax.php",
                type: "POST",
                data: {'action': 7, 'playlistid': playlistid, 'mediaid': mediaid}
            });

            //If playlist remove succeeds, remove its pane
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    var parentdiv = button.parent().parent();
                    button.parent().remove();
                    if(!parentdiv.find('.playlist-media-details-container').length)
                    {
                        parentdiv.html("<div style='height: 5px'></div><h5>There is no media in this playlist yet.</h5>");
                    }
                }
                else
                    alert("Failed to remove media from playlist.");
            });

            //Warn user if the playlist remove fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to remove media from playlist.");
            });
        });
    }
});
