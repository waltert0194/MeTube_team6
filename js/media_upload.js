var title = false;
var file = false;
var keywords = true;
$(document).ready(function(){
	
	//ensure valid submission
	$('form').submit( function(){
		if(title){
			if(file){
				if(keywords){
					upload = document.getElementById('fileInput');
					if(upload && upload.files && upload.files[0] && upload.files[0].size < 52428800) //SIZE HERE 
						return true;
					else alert("File is too large.");
				}else{alert("Keywords should only have letters and be separated by spaces.");}
			}else{alert("Select a file of supported file type.");}
		}else{alert("Your media must have a title.");}
		return false;
        });
	

	//ensure the correct file type
	$('#fileInput').on('change', function(){
		if($(this).val() !== ""){
			var ext = $('#fileInput').val().split('.').pop().toLowerCase();
			if($.inArray(ext, 
			    ['ico','wav','mp3','mp4','webm','ogg','gif','png','jpg','jpeg','bmp']) == -1) {
				file=false;
			}else file=true;
		}else file = false;
	});

	//title
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
