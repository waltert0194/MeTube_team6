title=true;
keywords=true;
$(document).ready(function (){
	
	 //require valid changes
        $('form').submit( function(){
		$('#title').trigger('change');
		$('#keywords').trigger('change');
                if(title){
                       	if(keywords){
                             	return true;
                        }else{alert("Keywords should contain letters only and be separated by spaces.");}
                }else{alert("Media must have title");}
                return false;
        });
	

	$('#title').on('change', function(){
                if($(this).val() !== ""){
                        title = true;
                }else title = false;
        });


        $('#keywords').on('change', function(){
                $('#keywords').val($('#keywords').val().toLowerCase());
                var list = $('#keywords').val().split(' ');
                var syntax=true;
                for(var i = 0; i < list.length; i++)
                        if(!/^[a-z]*$/i.test(list[i])){
                                syntax = false;
                        }
                if(syntax === true) keywords = true; else keywords = false;
        });	

});
