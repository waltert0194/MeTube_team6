$(document).ready(function(){

	/* sets reply buttons to add recipient name to messaging area and
	 * sets typing foucs to the message box
	 */
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

	/* Sends an ajax request to try to send the message to the given recipients
	 * upon clicking the send button
	 */
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
				$('#messagesuccess').text("Message sent successfully");
                refreshSentMessages();
			}
			else if(data == "empty")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message cannot be empty");
			}
			else if (data == "short")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message must be over 10 characters");
			}
			else if(data == "long")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Message cannot be over 1000 characters");
			}
			else if(data == "failed")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("");
				alert("Failed to send message");
			}
			else if(data == "nousers")
			{
				$('#messagesuccess').text("");
				$('#messageerror').text("Must have one or more recipients");
			}
			else
				alert(data);
		});

		request.fail(function(jqXHR, textStatus, errorThrown){
			alert("Failed to send message");
		});
	});
});
