$(document).ready(function(){

	$('.btn-reply').click(function(){
		var sender = $(this).val();

		$('#recipients').val(sender);
		$('#messagecontents').focus();
	});


	$('#messagecontents').click(function(){
		$('#messagesuccess').text("");
		$('#messageerror').text("");
	});

	$('#recipients').click(function(){
		$('#messagesuccess').text("");
		$('#messageerror').text("");
	});


    function refreshSentMessages() {
        request = $.ajax({
			url: "messagessent.php",
			type: "POST"
		});
		
        request.done(function(data, textStatus, jqXHR){
            $('#sentMessagesContainer').html(data);
        });

        request.fail(function(jqXHR, textStatus, errorThrown){
			alert("Failed to refresh sent messages.");
		});
    }

	$('#sendmessage').click(function(){
		var recipientlist = $('#recipients').val();
		var message = $('#messagecontents').val();
		
		request = $.ajax({
			url: "messagePageAjax.php",
			type: "POST",
			data: {'action': 0, 'recipients': recipientlist, 'message': message}
		});

		request.done(function(data, textStatus, jqXHR){
			if(data == "success")
			{
				$('#recipients').val("");
				$('#messagecontents').val("");
				$('#messageerror').text("");
				$('#messagesuccess').text("Message Sent");
                refreshSentMessages();
			}
			else if(data == "empty")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Cannot send empty message");
			}
			else if (data == "short")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message must be longer than 10 characters");
			}
			else if(data == "long")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message must be shorter than 1000 characters");
			}
			else if(data == "failed")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("");
				alert("Could not send message");
			}
			else if(data == "nousers")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Must send message to at least one user");
			}
			else
				alert(data);
		});

		request.fail(function(jqXHR, textStatus, errorThrown){
			alert("could not send message");
		});
	});
});
