<div class="job-creation-container">
    <!-- Form Section Header -->
    <div class="form-header-block">
        <h2 class="form-main-title">Create Internship Vacancy</h2>
        <p class="form-subtitle">Fill in the fields below to publish a new active internship position to the student matching network.</p>
    </div>

    <!-- Main Job Submission Form -->
    <form action="process_job_posting.php" method="POST" class="recruitment-secure-form">
        
        <!-- 1. Job Title Field -->
        <div class="form-field-group">
            <label for="job_title" class="field-label">Job Title <span class="required-indicator">*</span></label>
            <input type="text" id="job_title" name="job_title" class="field-input" placeholder="e.g. Software Engineer Intern" required>
        </div>

        <!-- 2. Dual Column Row: Department & Location Type -->
        <div class="form-grid-row-dual">
            <div class="form-field-group">
                <label for="department" class="field-label">Department / Team <span class="required-indicator">*</span></label>
                <input type="text" id="department" name="department" class="field-input" placeholder="e.g. Core Engineering, UX Research" required>
            </div>
            
            <div class="form-field-group">
                <label for="location_type" class="field-label">Location Type <span class="required-indicator">*</span></label>
                <select id="location_type" name="location_type" class="field-select" required>
                    <option value="" disabled selected>Select location rule...</option>
                    <option value="On-site">🏢 On-site (Office Base)</option>
                    <option value="Remote">🏠 Remote (Work From Home)</option>
                    <option value="Hybrid">🔄 Hybrid Setup</option>
                </select>
            </div>
        </div>

        <!-- 3. Dual Column Row: Slots & Monthly Stipend -->
        <div class="form-grid-row-dual">
            <div class="form-field-group">
                <label for="slots_available" class="field-label">Available Slots / Vacancies <span class="required-indicator">*</span></label>
                <input type="number" id="slots_available" name="slots_available" class="field-input" min="1" value="1" required>
            </div>
            
            <div class="form-field-group">
                <label for="stipend" class="field-label">Monthly Allowance / Stipend (RM)</label>
                <input type="text" id="stipend" name="stipend" class="field-input" placeholder="e.g. 1000 or Competitve / Negotiable">
            </div>
        </div>

        <!-- 4. Rich Text Area: Job Responsibilities & Core Requirements -->
        <div class="form-field-group">
            <label for="job_description" class="field-label">Job Description & Tech Stack Requirements <span class="required-indicator">*</span></label>
            <textarea id="job_description" name="job_description" class="field-textarea" rows="6" placeholder="Outline daily tasks, assigned projects, and structural expectations (e.g., Knowledge of HTML/CSS, PHP, MySQL, or Git source management)..." required></textarea>
        </div>

        <!-- 5. Form Submissions Action Controls Row -->
        <div class="form-actions-footer">
            <button type="button" class="btn-secondary-cancel" onclick="window.history.back();">Cancel</button>
            <button type="submit" class="btn-primary-publish">
                <i class='bx bx-cloud-upload'></i> Publish Job Posting
            </button>
        </div>

    </form>
</div>