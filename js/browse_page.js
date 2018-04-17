/* Javascript file for the browse page, adding functionality to the
 * side navigation pane and allowing for the dynamic updating of
 * the viewed media items.
 */

//Runs when the document is ready to set onclick functions
$(document).ready(function() {

    //Set the onclick for the home nav button
    $('.browse-sidenav-button.home').click(function() {
        if(!($(this).hasClass('active')))
        {
            var button = $(this);
                
            init = $.ajax({
                url: "browsehome.php",
                type: "POST"
            });

            //Page is successfully loaded
            init.done(function(data, textStatus, jqXHR) {
                $('.browse-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#bodyContent').html(data);
            });
           
            //Page is not loaded successfully
            init.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to load home page.");
            });
        }
    });

    //Set the onclick for the category nav buttons
    $('.browse-sidenav-button.category').click(function() {
        if(!($(this).hasClass('active')))
        {
            var category = $(this).find('[name="categoryName"]').val();
            var button = $(this);
                
            init = $.ajax({
                url: "browsecategory.php",
                type: "POST",
                data: {"category": category}
            });

            //Page is successfully loaded
            init.done(function(data, textStatus, jqXHR) {
                $('.browse-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#bodyContent').html(data);
            });
           
            //Page is not loaded successfully
            init.fail(function(jqXHR, textStatus, errorThrown) {
                    alert("Failed to load category page.");
            });
        }
    });

});
