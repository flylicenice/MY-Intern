$(document).ready(function() {
    $("#loginForm").on("submit", function(e) {
        //Stop the page from refreshing
        e.preventDefault();

        //Disable the login button and give message to user about the email is sending
        $('#submitBtn').text('Processing...').prop('disabled', true);
        $('#responseMessage').text('Sending email...').css(
            {
                "color": "blue",
                "text-align": "center"
            }
        );

        //Getting the form data from the loginForm
        var formData = $(this).serialize();

        $.ajax({
            url: "../includes/send_email.php",
            type: 'POST',
            data: formData,
            
            //If the send_email.php successfully send message
            success: function(response) {
                $('#responseMessage').text('Success! Please check your inbox!').css('color', 'green');
                return false;
            }, 
            
            //Else if there is an error
            error: function() {
                $('#responseMessage').text('Error sending email').css('color', 'red');
                $('#submitBtn').text('Login').prop('disabled', false);
            }
        });
    });
});