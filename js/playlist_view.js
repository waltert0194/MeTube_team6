//. onclick function
$(document).ready(function() {

    //remove playlist
    if($('.btn-delete-playlist').length)
    {
        $('.btn-delete-playlist').click(function(){
		    var playlistid = $('#playlistidJS').val();
            
            initiate = $.ajax({
					url: "playlistViewAjax.php",
					type: "POST",
					data: {'action': 0, 'playlistid': playlistid}
				});

				initiate.done(function(data, textStatus, jqXHR) {
					if(data === "success")
                        window.location.href = "./account.php";    
                    else
						alert("Error deleting playlist.");
				});

				initiate.fail(function(jqXHR, textStatus, errorThrown) {
					alert("Error deleting playlist.");
				})
            
        });
    }
    
    if($('.btn-delete-playlist-media').length)
    {
        $('.btn-delete-playlist-media').click(function() {
            var playlistid = $('#playlistidJS').val();
            var mediaid = $(this).parent().find('[name="mediaidField"]').val();
            var button = $(this);
            
            initiate = $.ajax({
                url: "mediaViewAjax.php",
                type: "POST",
                data: {'action': 7, 'playlistid': playlistid, 'mediaid': mediaid}
            });

            //success
            initiate.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    var master = button.parent().parent();
                    button.parent().remove();
                    if(!master.find('.playlist-media-details-container').length)
                    {
                        master.html("<div style='height: 5px'></div><h5>No media in this playlist</h5>");
                    }
                }
                else
                    alert("Could not remove item from list.");
            });

            //failure
            initiate.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Could not remove item from list.");
            });
        });
    }
});
