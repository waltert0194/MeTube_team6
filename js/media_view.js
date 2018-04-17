/* Javascript file for AJAX requests for viewing media, allowing dynamic
 * reloading of content such as comments for the media view page.
 */


//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //Function to set the onclick action for the
    //favorite media button.
    function setFavoriteMediaOnclick()
    {
        //Set AJAX action for unfavoriting media
        if($('#favoriteMediaButton.glyphicon-star').length)
        {
            $('#favoriteMediaButton.glyphicon-star').off('click');
            
            $('#favoriteMediaButton.glyphicon-star').click(function() {
                var mediaid = $('#mediaidJS').val();
                var button = $(this);

                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 4, 'mediaid': mediaid}
                });

                //If unfavorite succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                    {
                        button.attr('title', 'Favorite Media');
                        button.removeClass('glyphicon-star');
                        button.addClass('glyphicon-star-empty');
                        setFavoriteMediaOnclick();
                    }
                    else
                        alert("Failed to unfavorite media.");
                });

                //Warn user if the unfavorite fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to unfavorite media.");
                });
            });
        }

        //Set AJAX action for favoriting media
        if($('#favoriteMediaButton.glyphicon-star-empty').length)
        {
            $('#favoriteMediaButton.glyphicon-star-empty').off('click');

            $('#favoriteMediaButton.glyphicon-star-empty').click(function() {
                var mediaid = $('#mediaidJS').val();
                var button = $(this);

                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 5, 'mediaid': mediaid}
                });

                //If delete succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                    {
                        button.attr('title', 'Unfavorite Media');
                        button.removeClass('glyphicon-star-empty');
                        button.addClass('glyphicon-star');
                        setFavoriteMediaOnclick();
                    }
                    else
                        alert("Failed to favorite media.");
                });

                //Warn user if the delete fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to favorite media.");
                });
            });
        }
    }

    //This is separated so it can be called both on
    //the page load and the comment refresh
    function setDeleteCommentOnclick()
    {
        //Code for deleting comments via AJAX
        if($('.btn-delete-comment').length)
        {
            $('.btn-delete-comment').click(function() {
                var commentid = $(this).parent().find('[name="commentidField"]').val();

                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 1, 'commentid': commentid}
                });

                //If delete succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshComments();
                    else
                        alert("Failed to delete comment.");
                });

                //Warn user if the delete fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to delete comment.");
                });
            });
        }
    }

    //This is separated so it can be called whenever a
    //comment is selected to be edited
    function setEditCommentOnSubmit()
    {
        //Submit comment edit and refresh page on submit click
        if($('.edit-comment-form').length)
        {
            $('.edit-comment-form').submit(function() {
                $(this).find('.comment-validation').text("");
                if($(this).find('[name="commentText"]').val().length < 10)
                {
                    $(this).find('.comment-validation').text("Comment length cannot be under 10 characters.");
                    return false;
                }
                else if($(this).find('[name="commentText"]').val().length > 1000)
                {
                    $(this).find('.comment-validation').text("Comment length cannot exceed 1000 characters.");
                    return false;
                }
                
                var form = $(this);
                var serializedForm = form.serialize() + "&action=3";
                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: serializedForm
                });

                //If submit succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshComments();
                    else
                        form.find('.comment-validation').text(data);
                });

                //Warn user if the submit fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                     form.find('.comment-validation').text("Failed to submit comment.");
                });
                
                return false;
            });
        }
            
        //If someone cancels editing a comment, refresh comments
        if($('.btn-comment-edit-cancel').length)
        {
            $('.btn-comment-edit-cancel').click(function() {
               refreshComments(); 
            });
        }
    }

    //This is separated so it can be called both on
    //the page load and the comment refresh
    function setEditCommentOnclick()
    {
        //Code for editing comments via AJAX
        if($('.btn-edit-comment').length)
        {
            $('.btn-edit-comment').click(function() {
                var commentid = $(this).parent().find('[name="commentidField"]').val();
                var element = $(this);
                
                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 2, 'commentid': commentid}
                });

                //If edit interface request succeeds, refresh comments
                request.done(function(data, textStatus, jqXHR) {
                    element.parent().addClass('edit-comment-container');
                    element.parent().html(data);
                    setEditCommentOnSubmit();
                });

                //Warn user if the edit interface request fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load edit comment form.");
                });
            });
        }
    }

    //This is separated so it can be called both on
    //the page load and the playlist refresh
    function setPlaylistDropdownButtonOnclick()
    {
        //Code for adding media to playlists on
        //media page via AJAX
        if($('.playlist-dropdown-button.add').length)
        {
            $('.playlist-dropdown-button.add').click(function() {
                var playlistid = $(this).find('[name="playlistidField"]').val();
                var mediaid = $('#mediaidJS').val();
                var element = $(this);
                
                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 6, 'playlistid': playlistid, 'mediaid': mediaid}
                });

                //If playlist add succeeds, refresh playlist dropdown
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshPlaylistDropdown();
                    else
                        alert("Failed to add media to playlist.");
                    
                });

                //Warn user if the playlist add fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to add media to playlist.");
                });
            });
        }

        //Code for removing media from playlists on
        //media page via AJAX
        if($('.playlist-dropdown-button.remove').length)
        {
            $('.playlist-dropdown-button.remove').click(function() {
                var playlistid = $(this).find('[name="playlistidField"]').val();
                var mediaid = $('#mediaidJS').val();
                var element = $(this);
                
                request = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 7, 'playlistid': playlistid, 'mediaid': mediaid}
                });

                //If playlist remove succeeds, refresh playlist dropdown
                request.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshPlaylistDropdown();
                    else
                        alert("Failed to remove media from playlist.");
                });

                //Warn user if the playlist remove fails
                request.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to remove media from playlist.");
                });
            });
        }
    }

    //Refresh the playlist dropdown by loading it from the database
    function refreshPlaylistDropdown()
    {
        var mediaid = $('#mediaidJS').val();

        request = $.ajax({
            url: "playlistDropdown.php",
            type: "POST",
            data: { 'id': mediaid }
        });

        //If done correctly, set the playlist dropdown to the returned refresh
        request.done(function(data, textStatus, jqXHR) {
            $('#playlistDropdownContainer').html(data);
            setPlaylistDropdownButtonOnclick();
            if($('.playlist-dropdown-toggle').length)
                $('.playlist-dropdown-toggle').dropdown();
        });

        //Warn user if the refresh fails
        request.fail(function(jqXHR, textStatus, errorThrown) {
            alert("Failed to refresh playlists.");
        });

        return false; 
    }

    //Refresh the comments pane by loading it from the database
    function refreshComments()
    {
        if($("#commentSection").length)
        {
            var mediaid = $('#mediaidJS').val();

            request = $.ajax({
                url: "comments.php",
                type: "POST",
                data: { 'id': mediaid }
            });

            //If done correctly, set the comment section to the returned refresh
            request.done(function(data, textStatus, jqXHR) {
                $('#commentSection').html(data);
                setDeleteCommentOnclick();
                setEditCommentOnclick();
            });

            //Warn user if the refresh fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to refresh comments.");
            });
        }
        return false; 
    }

    //Code for submitting comments via AJAX
    if($('#makeCommentForm').length)
    {
        $('#makeCommentForm').submit(function() {
            $('#makeCommentValidation').text("");
            if($('#commentText').val().length < 10)
            {
                $('#makeCommentValidation').text("Comment length cannot be under 10 characters.");
                return false;
            }
            else if($('#commentText').val().length > 1000)
            {
                $('#makeCommentValidation').text("Comment length cannot exceed 1000 characters.");
                return false;
            }

            request = $.ajax({
                url: "mediaViewAjax.php",
                type: "POST",
                data: $('#makeCommentForm').serialize() + "&action=0"
            });

            //If submit succeeds, refresh comments
            request.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    $('#commentText').val("");
                    refreshComments();
                }
                else
                    $('#makeCommentValidation').text(data);
            });

            //Warn user if the submit fails
            request.fail(function(jqXHR, textStatus, errorThrown) {
                 $('#makeCommentValidation').text("Failed to submit comment.");
            });
            
            return false; 
        });
    }

    //Set the download media button to record all downloads
    $('#downloadMediaBtn').click(function() {
        var mediaid = $('#mediaidJS').val();
        
        $.post("media_download_process.php",
        {
            id: mediaid
        });
    });

    //Set the favorite media button onclick
    setFavoriteMediaOnclick();

    //Set the delete button onclick
    setDeleteCommentOnclick();

    //Set the edit button onclick
    setEditCommentOnclick();

    //Set the playlist dropdown onclicks
    setPlaylistDropdownButtonOnclick();
});
