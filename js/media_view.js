
//.onclick functions
$(document).ready(function() {

    //.onclick for favorite
    function setFavoriteMediaOnclick()
    {
        if($('#favoriteMediaButton.glyphicon-star').length)
        {
            $('#favoriteMediaButton.glyphicon-star').off('click');
            
            $('#favoriteMediaButton.glyphicon-star').click(function() {
                var mediaid = $('#mediaidJS').val();
                var btn = $(this);

                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 4, 'mediaid': mediaid}
                });

                //update comments
                initiate.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                    {
                        btn.attr('title', 'Favorite Media');
                        btn.removeClass('glyphicon-star');
                        btn.addClass('glyphicon-star-empty');
                        setFavoriteMediaOnclick();
                    }
                    else
                        alert("Failed to unfavorite media.");
                });

                //Warn user if the unfavorite fails
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
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
                var btn = $(this);

                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 5, 'mediaid': mediaid}
                });

                //success
                initiate.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                    {
                        btn.attr('title', 'Unfavorite Media');
                        btn.removeClass('glyphicon-star-empty');
                        btn.addClass('glyphicon-star');
                        setFavoriteMediaOnclick();
                    }
                    else
                        alert("Favorite failed.");
                });

                //failed
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to favorite media.");
                });
            });
        }
    }

    //delete comment
    function setDeleteCommentOnclick()
    {
        //delete comment
        if($('.btn-delete-comment').length)
        {
            $('.btn-delete-comment').click(function() {
                var commentid = $(this).parent().find('[name="commentidField"]').val();

                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 1, 'commentid': commentid}
                });

                //success
                initiate.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshComments();
                    else
                        alert("Failed to delete comment.");
                });

                //failed
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to delete comment.");
                });
            });
        }
    }

    //edit comment
    function setEditCommentOnSubmit()
    {
        //edit comment and refresh page
        if($('.edit-comment').length)
        {
            $('.edit-comment').submit(function() {
                $(this).find('validate-comment').text("");
                if($(this).find('[name="commentText"]').val().length < 10)
                {
                    $(this).find('validate-comment').text("Comment length cannot be under 10 characters.");
                    return false;
                }
                else if($(this).find('[name="commentText"]').val().length > 1000)
                {
                    $(this).find('validate-comment').text("Comment length cannot exceed 1000 characters.");
                    return false;
                }
                
                var form = $(this);
                var serializedForm = form.serialize() + "&action=3";
                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: serializedForm
                });

                //If submit succeeds, refresh comments
                initiate.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        refreshComments();
                    else
                        form.find('validate-comment').text(data);
                });

                //Warn user if the submit fails
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
                     form.find('validate-comment').text("Failed to submit comment.");
                });
                
                return false;
            });
        }
            
        //If someone cancels editing a comment, refresh comments
        if($('.editCancel-comment').length)
        {
            $('.editCancel-comment').click(function() {
               refreshComments(); 
            });
        }
    }

    //edit comment
    function setEditCommentOnclick()
    {
        //Code for editing comments via AJAX
        if($('.btn-edit-comment').length)
        {
            $('.btn-edit-comment').click(function() {
                var commentid = $(this).parent().find('[name="commentidField"]').val();
                var element = $(this);
                
                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 2, 'commentid': commentid}
                });

                //success
                initiate.done(function(data, textStatus, jqXHR) {
                    element.parent().addClass('edit-comment-container');
                    element.parent().html(data);
                    setEditCommentOnSubmit();
                });

                //failure
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load edit comment form.");
                });
            });
        }
    }

    //playlist dropdown button
    function functionclickPlaylistDropdown()
    {
       
        if($('.playlist-dropdown-button.add').length)
        {
            $('.playlist-dropdown-button.add').click(function() {
                var playlistid = $(this).find('[name="playlistidField"]').val();
                var mediaid = $('#mediaidJS').val();
                var element = $(this);
                
                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 6, 'playlistid': playlistid, 'mediaid': mediaid}
                });

                //success
                initiate.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        dropdownPlaylistRegen();
                    else
                        alert("adding media to playlist failure");
                    
                });

                //failure
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to add media to playlist.");
                });
            });
        }

    
        if($('.playlist-dropdown-button.remove').length)
        {
            $('.playlist-dropdown-button.remove').click(function() {
                var playlistid = $(this).find('[name="playlistidField"]').val();
                var mediaid = $('#mediaidJS').val();
                var element = $(this);
                
                initiate = $.ajax({
                    url: "mediaViewAjax.php",
                    type: "POST",
                    data: {'action': 7, 'playlistid': playlistid, 'mediaid': mediaid}
                });

                //success
                initiate.done(function(data, textStatus, jqXHR) {
                    if(data === "success")
                        dropdownPlaylistRegen();
                    else
                        alert("could not remove media from playlist.");
                });

                //failure
                initiate.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("could not remove media from playlist.");
                });
            });
        }
    }

    function dropdownPlaylistRegen()
    {
        var mediaid = $('#mediaidJS').val();

        initiate = $.ajax({
            url: "playlistDropdown.php",
            type: "POST",
            data: { 'id': mediaid }
        });

        success
        initiate.done(function(data, textStatus, jqXHR) {
            $('#playlistDropdownContainer').html(data);
           clickPlaylistDropdown();
            if($('.playlist-dropdown-toggle').length)
                $('.playlist-dropdown-toggle').dropdown();
        });

        //Warn user if the refresh fails
        initiate.fail(function(jqXHR, textStatus, errorThrown) {
            alert("Failed to refresh playlists.");
        });

        return false; 
    }

    //Refresh comments
    function refreshComments()
    {
        if($("#commentSection").length)
        {
            var mediaid = $('#mediaidJS').val();

            initiate = $.ajax({
                url: "comments.php",
                type: "POST",
                data: { 'id': mediaid }
            });

            //success
            initiate.done(function(data, textStatus, jqXHR) {
                $('#commentSection').html(data);
                setDeleteCommentOnclick();
                setEditCommentOnclick();
            });

            //failure
            initiate.fail(function(jqXHR, textStatus, errorThrown) {
                alert("refresh comments failed.");
            });
        }
        return false; 
    }

    if($('#makeCommentForm').length)
    {
        $('#makeCommentForm').submit(function() {
            $('#makeCommentValidation').text("");
            if($('#commentText').val().length < 10)
            {
                $('#makeCommentValidation').text("Comment must be longer than 10 characters");
                return false;
            }
            else if($('#commentText').val().length > 1000)
            {
                $('#makeCommentValidation').text("Comment must be shorter than 1000 characters");
                return false;
            }

            initiate = $.ajax({
                url: "mediaViewAjax.php",
                type: "POST",
                data: $('#makeCommentForm').serialize() + "&action=0"
            });

            //success
            initiate.done(function(data, textStatus, jqXHR) {
                if(data === "success")
                {
                    $('#commentText').val("");
                    refreshComments();
                }
                else
                    $('#makeCommentValidation').text(data);
            });

            //failure
            initiate.fail(function(jqXHR, textStatus, errorThrown) {
                 $('#makeCommentValidation').text("Failed to submit comment.");
            });
            
            return false; 
        });
    }

    //download media
    $('#downloadMediaBtn').click(function() {
        var mediaid = $('#mediaidJS').val();
        
        $.post("media_download_process.php",
        {
            id: mediaid
        });
    });

    
    setFavoriteMediaOnclick();
    setDeleteCommentOnclick();
    setEditCommentOnclick();
    clickPlaylistDropdown();
});
