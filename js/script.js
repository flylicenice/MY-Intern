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

    if (viewBtn.length && closeBtn.length) {
        viewBtn.on("click", function () {
            detailPanel.show();
        });

        closeBtn.on("click", function () {
            detailPanel.hide();
            const jobDetailPanel = $(".job-details-panel");
            if (jobDetailPanel.length) {
                jobDetailPanel.hide();
            }
        });
    }
}

function filterLecturerTable() {
    const searchInput = document.getElementById('tableSearchInput');
    if (!searchInput) return; 

    const searchFieldQuery = document.getElementById('tableSearchInput').value.toLowerCase();
    const checkedRadioOption = document.querySelector('input[name="filter"]:checked');
    const statusConstraint = checkedRadioOption ? checkedRadioOption.value : 'all';

    const operationalRows = document.querySelectorAll('.-data-row');
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

    const nullState = document.getElementById('null-state-row').style.display = (visibleMatchCounter === 0) ? '' : 'none';
    const totalCount = document.getElementById('lecturer-total-count').innerText = visibleMatchCounter;
    if (nullState) nullState.style.display = (visibleMatchCounter === 0) ? '' : 'none';
    if (totalCount) totalCount.innerText = visibleMatchCounter;

}

function openFacultyDrawer(data) {
    if (!data) return;
    const drawerName = document.getElementById('drawerName');
    if (drawerName) drawerName.innerText = data.name || 'N/A';
    document.getElementById('drawerId').innerText = data.id || 'N/A';
    document.getElementById('drawerEmail').innerText = data.email || 'N/A';
    document.getElementById('drawerPhone').innerText = data.phone || 'Not Provided';
    document.getElementById('drawerFaculty').innerText = data.faculty || 'Faculty of Information and Communication Technology';
    document.getElementById('drawerDept').innerText = data.department || 'N/A';
   
    const drawer = document.getElementById('facultyDetailsDrawer');
    if (drawer) {
        drawer.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeFacultyDrawer() {
    const drawer = document.getElementById('facultyDetailsDrawer').style.display = 'none';
    if (drawer){
        drawer.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
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

function openAddAdminWindow() {
    const width = 800;
    const height = 600;

    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    window.open(
        '../admin/add_admin.php',
        'PopupName',
        `width=${width},height=${height},left=${left},top=${top}`
    );
}

function showJobDetailsPanel() {
    const jobDetailPanel = $("#detailsPanel");
    const oldDetailSection = $(".details-section");

    $(document).on("click", ".job-posting-card", function() {
        const title = $(this).find('.job-posting-title').text();
        const company = $(this).find('.company-name-text').text();
        const location = $(this).find('.job-location-text').text();
        const allowance = $(this).find('.job-salary-text').text();
        const jobId = $(this).find('.apply-now-btn').data('jobid');

        $('#panel-title').text(title);
        $('#panel-company').text(company);
        $('#panel-location').text(location);
        $('#panel-allowance').text(allowance);
        $('#panel-apply-btn').data('jobid', jobId);
        
        jobDetailPanel.show();
    });

    $("#closeDetailsBtn").on("click", function(e) {
        e.preventDefault();
        jobDetailPanel.hide();
    });

    $(document).on("click", ".apply-now-btn", function(e) {
        e.preventDefault();
        const jobId = $(this).data('jobid');

        fetch('process_apply.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'job_id=' + encodeURIComponent(jobId)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('🎉 ' + data.message);
                window.location.href = 'pages/student/student_dashboard.php';
            } else {
                alert('⚠️ ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
}

        

$(document).ready(function () {
    linkActive();
    triggerDropDownMenu();
    triggerShowPassword();
    triggerShowNotification();
    
    showJobDetailsPanel();

    if (document.getElementById('tableSearchInput')) {
        filterLecturerTable();
    }
});