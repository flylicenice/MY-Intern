document.addEventListener("DOMContentLoaded", function () {
    linkActiveUnderlined();
    triggerDropDownMenu();
});

function linkActiveUnderlined() {
    const currentPath = window.location.pathname;
    const currentSearch = window.location.search;
    const navLinks = document.querySelectorAll('.nav-bar a');
    const studentNavLinks = document.querySelectorAll('.white-link');

    navLinks.forEach(link => {
        link.classList.remove("active");

        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        }
    });

    studentNavLinks.forEach(link => {
        link.classList.remove("active");

        if (link.getAttribute("href") === currentSearch) {
            link.classList.add("active");
        }
    });

}

function triggerDropDownMenu() {
    const profileTrigger = document.getElementById("profile-trigger");
    const menu = document.getElementById("profile-menu");
    const dropDownArrow = document.getElementById('drop-down-profile-arrow');

    if (profileTrigger && menu && dropDownArrow) {
        profileTrigger.addEventListener("click", () => {
            menu.classList.toggle("show");
            dropDownArrow.classList.toggle("show");
        });

        document.addEventListener("click", function (e) {
            if (!menu.contains(e.target) && !profileTrigger.contains(e.target)) {
                menu.classList.remove("show");
                dropDownArrow.classList.remove("show");
            }
        });
    }
}