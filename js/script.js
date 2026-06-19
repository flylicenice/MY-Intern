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
    $(document).on("click", ".btn-approve", function () {
        alert("Approved Successfully!");
    });

    $(document).on("click", ".btn-reject", function () {
        alert("Reject Successfully!");
    });

}

function showDetailsPanel() {
    const detailPanel = $(".details-section");

    const viewBtn = $(".btn-view");
    const closeBtn = $("#closeDetailsBtn");

    if (viewBtn.length && closeBtn) {
        viewBtn.on("click", function () {
            detailPanel.show();
        });

        closeBtn.on("click", function () {
            detailPanel.hide();
            jobDetailPanel.hide();
        });
    }
}

function filterLecturerTable() {
    const searchFieldQuery = document.getElementById('tableSearchInput').value.toLowerCase();
    const checkedRadioOption = document.querySelector('input[name="filter"]:checked');
    const statusConstraint = checkedRadioOption ? checkedRadioOption.value : 'all';

    const operationalRows = document.querySelectorAll('.lecturer-data-row');
    let visibleMatchCounter = 0;

    operationalRows.forEach(row => {
        const rowStatusAttr = row.getAttribute('data-status');
        const cellLecturerName = row.querySelector('.lecturer-name').innerText.toLowerCase();
        const cellStaffId = row.cells[0].innerText.toLowerCase();

        const matchesStatus = (statusConstraint === 'all' || rowStatusAttr === statusConstraint);
        const matchesSearch = (cellLecturerName.includes(searchFieldQuery) || cellStaffId.includes(searchFieldQuery));

        if (matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleMatchCounter++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('null-state-row').style.display = (visibleMatchCounter === 0) ? '' : 'none';
    document.getElementById('lecturer-total-count').innerText = visibleMatchCounter;
}

function openFacultyDrawer(data) {
    document.getElementById('drawerName').innerText = data.name || 'N/A';
    document.getElementById('drawerId').innerText = data.id || 'N/A';
    document.getElementById('drawerEmail').innerText = data.email || 'N/A';
    document.getElementById('drawerPhone').innerText = data.phone || 'Not Provided';
    document.getElementById('drawerFaculty').innerText = data.faculty || 'Faculty of Information and Communication Technology';
    document.getElementById('drawerDept').innerText = data.department || 'N/A';
    document.getElementById('facultyDetailsDrawer').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeFacultyDrawer() {
    document.getElementById('facultyDetailsDrawer').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function openAddLecturerWindow() {
    const width = 800;
    const height = 600;

    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    window.open(
        '/MYIntern/pages/admin/add_lecturer.php',
        'PopupName',
        `width=${width},height=${height},left=${left},top=${top}`
    );
}

function showJobDetailsPanel() {
    const jobCard = $(".job-posting-card");
    const jobDetailPanel = $(".job-details-panel");
    const closeBtn = $("#closeDetailsBtn");

    if (closeBtn && jobCard && jobDetailPanel) {
        jobDetailPanel.hide();
        closeBtn.on("click", function() {
            jobDetailPanel.hide();
        });
        
        jobCard.on("click", function() {
            jobDetailPanel.show();
        });
    }
}

$(document).ready(function () {
    linkActive();
    triggerDropDownMenu();
    triggerShowPassword();
    triggerShowNotification();
    showDetailsPanel();
    toggleLecturerFilterMenu();
    filterLecturerTable();
    openFacultyDrawer(data);
    closeFacultyDrawer();
    openAddLecturerWindow();
    showJobDetailsPanel();
});