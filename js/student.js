$(document).ready(function () {
    linkActive();
    printDoc();
    handleELogUpload();
});

function linkActive() {
    const currentPath = window.location.pathname;
    const currentSearch = window.location.search;

    $('.nav-item').each(function () {
        var link = $(this);
        var innerLink = link.find('a');

        if (innerLink.length > 0) {
            link.removeClass('active');

            if (innerLink.attr("href") === currentSearch) {
                link.addClass('active');
            }
        }
    });
}

function printDoc() {
    $("#dashboard-print-trigger").on("click", function () {
        window.print();
    });
}

function handleELogUpload()
{
   $(document).on("submit", "#logbookForm", function(event){
        event.preventDefault(); 

        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');

        submitBtn.prop('disabled', true).text('Uploading...');

        $.ajax({
            url: window.location.href,
            type: "POST",
            data: formData,
            contentType: false, 
            processData: false, 
            success: function (response) {
                if (response.status === "success"){
                  alert("Logbook submitted successfully!");
                  window.top.location.href = "student_dashboard.php?page=e-log&status=success";
            }else{
                  alert("Upload failed: " + response.message);            
                  submitBtn.prop('disabled', false).text('Upload Weekly Logbook');
            }
        },
        });
   });
}

