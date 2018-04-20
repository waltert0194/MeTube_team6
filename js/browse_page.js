
$(document).ready(function() {

    //.onclick for the home button
    $('.browse-sidenav-button.home').click(function() {
        if(!($(this).hasClass('active')))
        {
            var button = $(this);
                
            init = $.ajax({
                url: "browsehome.php",
                type: "POST"
            });

            //page load success
            init.done(function(data, textStatus, jqXHR) {
                $('.browse-sidenav-button.active').removeClass('active');
                button.addClass('active');
                $('#bodyContent').html(data);
            });
           
            //page failed to load
            init.fail(function(jqXHR, textStatus, errorThrown) {
                alert("Failed to load home page.");
            });
        }
    });

    //.onclick for the category button
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
                    alert("Category page could not load.");
            });
        }
    });

});
