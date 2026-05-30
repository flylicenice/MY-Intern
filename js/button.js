$(document).ready(function() {
    $("i#hidden-btn").on("click", function() {
        $("nav.nav-bar").css("display", "flex");
    });

    $("ul li.my-link").on('click', function() {
        $("ul li.my-link").removeClass('active');
        $(this).addClass("active");
    });
});

