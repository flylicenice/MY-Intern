$(document).ready(function() {
    $("form#genPDF").on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: '../includes/gen_pdf.php',
            xhrFields: { responseType: 'blob' },
            success: function(response) {
                var blob = new Blob([response], {type: 'application/pdf'});
                var link = document.createElement('a');
                link,href = window.URL.createObjectURL(blob);
                $('#responseBlock').text('The pdf has successfully  generated!');
            },

            error: function(xhr, status, error) {
                $('#responseBlock').text("Failed.", error);
            }
        });
    }); 
});