/* File to hold javascript for dynamic events on the account viewing
 * page, including sending AJAX requests.
 */

var playlistNameValid = false;

$(document).ready(function() {

	$('#editsub').click(function() {
		var subval = $(this).val();
		var username = $('#username').val();

		switch(subval)
		{
			case "0"://not subbed
				request = $.ajax({
					url: "accountViewAjax.php",
					type: "POST",
					data: {'action': 0, 'username': username}
				});

				request.done(function(data, textStatus, jqXHR) {
					if(data === "success")
					{	
						$('#editsub').attr("value", "1");
						$('#editsub').text("Unsubscribe");
					}
					else if(data === "default")
					{
						alert("Failed to subscribe");
					}
					else
						alert("Failed to subscribe");
				});

				request.fail(function(jqXHR, textStatus, errorThrown) {
					alert("Failed to subscribe");
				});

				break;
			case "1"://subbed

				request = $.ajax({
					url: "accountViewAjax.php",
					type: "POST",
					data: {'action': 1, 'username': username}
					
				});

				request.done(function(data, textStatus, jqXHR) {
					if(data === "success")
					{	
						$('#editsub').attr("value", "0");
						$('#editsub').text("Subscribe");
					}
					else if(data === "default")
					{
						alert("Failed to unsubscribe");
					}
					else
						alert("Failed to unsubscribe");
				});

				request.fail(function(jqXHR, textStatus, errorThrown) {
					alert("Failed to unsubscribe");
				});


				break;
			case "2"://login
				window.location = './login.php';
				break;
			case "3"://edit account
				window.location = './editaccount.php';
				break;
			default:
				alert("Something went wrong");
				break;
		}
	});

	$('#messagebox').click(function(){
		$('#messagesuccess').text("");
		$('#messageerror').text("");
    });
    

	$('#messagesend').click(function(){
		var message = $('#messagebox').val();
		var username = $('#username').val();

		request = $.ajax({
			url: "accountViewAjax.php",
			type: "POST",
			data: {'action': 2, 'username': username, 'message': message}
		});

		request.done(function(data, textStatus, jqXHR) {
			if(data === "success")
			{
				$('#messageerror').text("");
				$('#messagesuccess').text("Message sent");
				$('#messagebox').val("");
			}
			else if(data === "empty")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Cannot send empty message");
			}
			else if(data === "long")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message too long");
			}
			else if(data === "short")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message must be longer than 10 characters");
			}
			else
				alert("Failed to send message");
		});

		request.fail(function(jqXHR, textStatus, errorThrown) {

		});
	});

    //Code for deleting a media item on click
    if($('.btn-delete-media').length)
    {
        $('.btn-delete-media').click(function() {
            var mediaid = $(this).parent().find('[name="mediaidField"]').val();
            var button = $(this);
            
            request = $.ajax({
                url: "accountViewAjax.php",
                type: "POST",
                data: {'action': 3, 'mediaid': mediaid}
            });

            //If media deletion succeeds, remove its pane
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                    button.parent().remove();
                else
                    alert("Failed to delete media item.");
            });

            //Warn user if the media deletion fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to delete media item.");
            });
        });
    }


    //Code for removing media from playlists on
    //the account page via AJAX
    if($('.btn-remove-playlist-media').length)
    {
        $('.btn-remove-playlist-media').click(function() {
            var playlistid = $(this).parent().find('[name="playlistidField"]').val();
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
                    button.parent().remove();
                else
                    alert("Failed to remove media from playlist.");
            });

            //Warn user if the playlist remove fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to remove media from playlist.");
            });
        });
    }

    //Set AJAX action for unfavoriting media
    if($('.btn-account-remove-favorite').length)
    {
        $('.btn-account-remove-favorite').click(function() {
            var mediaid = $(this).parent().find('[name="mediaidField"]').val();
            var button = $(this);

            request = $.ajax({
                url: "mediaViewAjax.php",
                type: "POST",
                data: {'action': 4, 'mediaid': mediaid}
            });

            //If delete succeeds, refresh comments
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                    button.parent().remove();
                else
                    alert("Failed to unfavorite media.");
            });

            //Warn user if the delete fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to unfavorite media.");
            });
        });
    }

    //Code to clear input when canceling adding a playlist
    if($('#cancelAddPlaylist').length)
    {
        $('#cancelAddPlaylist').click(function(){
            $('#playlistName').val('');
            $('#playlistNameValidation').text('');
            playlistNameValid = false;
        });
    }

    //Code to validate playlist name
    if($('#playlistName').length)
    {
        $('#playlistName').on('change', function(){
            if($(this).val().length == 0)
            {
                $('#playlistNameValidation').text('Playlist must have a name.');
                playlistNameValid = false;
            }
            else if($(this).val().length > 40)
            {
                $('#playlistNameValidation').text('Playlist name cannot exceed 40 characters.');
                playlistNameValid = false;
            }
            else
            {
                $('#playlistNameValidation').text('');
                playlistNameValid = true;
            }
        });
    }

    //Code to submit new playlist
    if($('#submitAddPlaylist').length)
    {
        $('#submitAddPlaylist').click(function(){
            $('#playlistName').change();
            if(playlistNameValid)
            {
                var playlistName = $('#playlistName').val();
                var button = $(this);

                request = $.ajax({
                    url: "playlistViewAjax.php",
                    type: "POST",
                    data: {'action': 1, 'playlistName': playlistName}
                });

                //If delete succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        window.location.reload();
                    else
                        $('#playlistNameValidation').text('Failed to add playlist.');
                });

                //Warn user if the delete fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    $('#playlistNameValidation').text('Failed to add playlist.');
                });
            }
        });
    }

});
