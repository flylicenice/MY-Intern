function linkActive() {
    const currentPath = window.location.pathname;
    const currentSearch = window.location.search;
    const navLinks = document.querySelectorAll('.nav-bar a');
    const studentNavLinks = document.querySelectorAll('.white-link');
    const adminNavLinks = document.querySelectorAll('.nav-item');

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

    adminNavLinks.forEach(link => {
        const innerLink = link.querySelector('a');

        if (innerLink) {
            link.classList.remove('active');

            if (innerLink.getAttribute("href") === currentSearch) {
                link.classList.add('active');
            }
        }
    });
}

function triggerDropDownMenu() {
    const profileTrigger = document.getElementById("profile-trigger");
    const menu = document.getElementById("profile-menu");
    const dropDownArrow = document.getElementById('drop-down-profile-arrow');
    const filterBtn = document.getElementById("filter-btn");
    const filterMenu = document.getElementById('filter-container');

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

    if (filterBtn && filterMenu) {
        filterBtn.addEventListener('click', () => {
            filterMenu.classList.toggle("show");
        });
    }
}

function triggerShowPassword() {
    const eyeIcon = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    if (eyeIcon && passwordField) {
        eyeIcon.addEventListener('click', function () {
            if (passwordField.type === 'password') {
                passwordField.type = "text";
            } else {
                passwordField.type = 'password';
            }
        });
    }
}

function triggerShowNotification() {
    $(document).on("click", ".btn-approve", function() {
        alert("Approved Successfully!");
    });

    $(document).on("click", ".btn-reject", function() {
        alert("Reject Successfully!");
    });

}

function showDetailsPanel() {
    const detailPanel = $(".details-section");
    const viewBtn = $(".btn-view");
    const closeBtn = $("#closeDetailsBtn");

    if (viewBtn.length && closeBtn) {
        viewBtn.on("click", function() {
            detailPanel.show();
        });

        closeBtn.on("click", function(){
            detailPanel.hide();
        });
    }
}

$(document).ready(function () {
    linkActive();
    triggerDropDownMenu();
    triggerShowPassword();
    triggerShowNotification();
    showDetailsPanel();
});