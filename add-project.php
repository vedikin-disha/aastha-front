<?php 
include 'common/header.php'; 
// Define API URL if not already defined
if (!defined('API_URL')) {
    define('API_URL', 'your_api_base_url_here/'); // Replace with your actual API base URL
}
?>
<!-- Additional CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    .required-label::after {
        content: ' *';
        color: red;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .error-feedback {
        width: 100%;
        margin-top: .25rem;
        font-size: 80%;
        color: #dc3545;
    }
    /* Custom Select2 highlight and selected color */
.select2-container--bootstrap-5 .select2-results__option--highlighted {
  background-color:rgb(236, 236, 236) !important;
}
.select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable,
.select2-container--bootstrap-5 .select2-results__option--selected {
  background-color: #30b8b9 !important;
  color: #fff !important;
}
 
/* Style for selected item in the dropdown */
.select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
  background-color: rgb(236, 236, 236) !important;
  color: #212529 !important;
}
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Add New Project</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <a href="projects.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Projects
            </a>
            </ol>
        </div>
    </div>

   
    
    <!-- Alert for form submission result -->
    <div id="formResult" class="alert" style="display: none;"></div>

    <div class="card">
        <div class="card-header" style="background-color: #30b8b9;border:none;color:white;">
            <h3 class="card-title">Project Details</h3>
        </div>
        <div class="card-body">
            <form id="addProjectForm">
  
  
  
            <div class="row">
                    <!-- Basic Information Section -->
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Project Type -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="project_type_id" class="required-label">Project Type:</label>
                                            <select id="project_type_id" name="project_type_id" class="form-control select2" required>
                                                <option value="">Select Project Type</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="project_type_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Proposed Work -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="proposed_work_id">Proposed Work:</label>
                                            <select id="proposed_work_id" name="proposed_work_id" class="form-control select2">
                                                <option value="">-- Select Proposed Work --</option>
                                            </select>
                                            <div class="error-feedback" id="proposed_work_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Project Name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="project_name" class="required-label">Project Name:</label>
                                            <input type="text" class="form-control" id="project_name" name="project_name" 
                                                placeholder="Enter project name" required>
                                            <div class="error-feedback" id="project_name_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Job No -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="job_no" class="required-label">Job No:</label>
                                            <input type="text" class="form-control" id="job_no" name="job_no" 
                                                placeholder="Enter job number">
                                            <div class="error-feedback" id="job_no_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Job No Reference Date (Hidden) -->
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="job_no_reference_date">Job No Reference Date:</label>
                                        <input type="date" class="form-control" id="job_no_reference_date" name="job_no_reference_date" data-date-format="YYYY-MM-DD">
                                        <div class="error-feedback" id="job_no_reference_date_error"></div>
                                    </div>
                                    </div>
                                    
                                    <!-- Priority -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="priority" class="required-label">Priority:</label>
                                            <select id="priority" name="priority" class="form-control" required>
                                                <option value="Regular">Regular</option>
                                                <option value="High">High</option>
                                            </select>
                                            <div class="error-feedback" id="priority_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Current Department -->
                        
                                    <!-- Client Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client_name" class="required-label">Client Name:</label>
                                            <input type="text" class="form-control" id="client_name" name="client_name" 
                                                placeholder="Enter client name">
                                            <div class="error-feedback" id="client_name_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" 
                                                placeholder="Enter project description"></textarea>
                                            <div class="error-feedback" id="description_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location & Duration Section -->
                    <div class="col-md-6">

                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Location & Duration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Start Date -->
                                    <div class="col-md-12">

                                  <!-- // by defult today date -->
                                        <div class="form-group">
                                            <label for="start_date" class="required-label">Start Date:</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" data-date-format="YYYY-MM-DD">
                                            <div class="error-feedback" id="start_date_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- End Date -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" data-date-format="YYYY-MM-DD">
                                            <div class="error-feedback" id="end_date_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Circle -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_circle" class="required-label">Circle:</label>
                                            <select id="id_circle" name="circle_id" class="form-control select2">
                                                <option value="">Select Circle</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="circle_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Division -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_division" class="required-label">Division:</label>
                                            <select id="id_division" name="division_id" class="form-control select2">
                                                <option value="">Select Division</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="division_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Subdivision -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_sub">Subdivision:</label>
                                            <select id="id_sub" name="sub_id" class="form-control select2">
                                                <option value="">Select Subdivision</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="sub_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Taluka -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_taluka">Taluka:</label>
                                            <select id="id_taluka" name="taluka_id" class="form-control select2">
                                                <option value="">Select Taluka</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="taluka_id_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

</div>
                <!-- Estimated Amount In lakhs and Metadata Section -->
                <div class="row" style="margin-top: 20px;">
                    <!-- Estimated Amount In lakhs -->
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Estimated Amount In lakhs</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Estimated Amount -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="estimated_amount">Estimated Amount:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₹</span>
                                                </div>
                                                <input type="number" class="form-control" id="estimated_amount" name="estimated_amount" 
                                                    step="0.01" min="0" value="0.00" placeholder="0.00">
                                            </div>
                                            <div class="error-feedback" id="estimated_amount_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Budget Head -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="budget_head">Budget Head:</label>
                                            <input type="text" class="form-control" id="budget_head" name="budget_head" 
                                                placeholder="Enter budget head">
                                            <div class="error-feedback" id="budget_head_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Length -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="length">Length (in meters):</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="length" name="length" 
                                                    step="0.01" min="0" value="0.00" placeholder="0.00">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                            <div class="error-feedback" id="length_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    
                    <!-- Administrative Attachments Section -->
                    <div class="col-md -6">
                        <div class="card">
                            <div class="card-header" >
                                <h5 class="mb-0">Administrative Attachments</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Administrative Approval -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Administrative Approval:</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="administrative_approval" name="administrative_approval" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png.csv.xls,.xlsx">
                                                <label class="custom-file-label" for="administrative_approval">Choose file</label>
                                            </div>
                                            <div id="administrative_approval_uploaded"></div>
                                        </div>
                                    </div>

                                    <!-- DTP Section -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>DTP Section:</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="dtp_section" name="dtp_section" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png.csv.xls,.xlsx">
                                                <label class="custom-file-label" for="dtp_section">Choose file</label>
                                            </div>
                                            <div id="dtp_section_uploaded"></div>
                                        </div>
                                    </div>

                                    <!-- Technical Section -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Technical Section:</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="technical_section" name="technical_section" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png.csv.xls,.xlsx">
                                                <label class="custom-file-label" for="technical_section">Choose file</label>
                                            </div>
                                            <div id="technical_section_uploaded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div >

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success" id="saveProject"style="background-color: #30b8b9;border:none;">
                            <i class="fas fa-save"></i> Save Project
                        </button>
                        <a href="projects.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </div>
            
                </form>

</div>
</div>
</div>

<!-- Required JS files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
// Define API_URL for JavaScript
const API_URL = '<?php echo API_URL; ?>';
</script></script>

<script>

function loadProjectTypes() {
    console.log('Loading project types...');
    $.ajax({
        url: '<?php echo API_URL; ?>template-listing',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
            console.log('Project types API response:', response);
            if (response.is_successful === "1" && response.data) {
                let options = '<option value="">Select Project Type</option>';
                response.data.forEach(function(type) {
                    options += `<option value="${type.project_type_id}">${type.project_type_name}</option>`;
                });
                $('#project_type_id').html(options);
            } else {
                console.error('Failed to load project types:', response);
                $(document).Toasts('create', {
                    class: 'bg-warning',
                    title: 'Warning',
                    body: 'Unable to load project types. Please try refreshing the page.',
                    autohide: true,
                    delay: 3000
                });
            }
        },
        error: function(error) {
            console.error('Error loading project types:', error);
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: 'Failed to load project types. Please check your connection and try again.',
                autohide: true,
                delay: 3000
            });
        }
    });
}


function loadCircles() {
    console.log('Loading circles...');
    $.ajax({
        url: '<?php echo API_URL; ?>circle',
        type: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
            console.log('Circles API response:', response);
            if (response && Array.isArray(response)) {
                var circleSelect = $('#id_circle');
                circleSelect.empty();
                circleSelect.append('<option value="">Select Circle</option>');
                
                response.forEach(function(circle) {
                    circleSelect.append('<option value="' + circle.circle_id + '">' + circle.circle_name + '</option>');
                });
            } else {
                console.error('API Error:', 'Invalid response format');
            }
        },
        error: function(error) {
            console.error('Error loading circles:', error);
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: 'Failed to load circle data. Please refresh the page.',
                autohide: true,
                delay: 3000
            });
        }
    });
}

function loadDivisions(circleId) {
    console.log('Loading divisions for circle ID:', circleId);
    $.ajax({
        url: '<?php echo API_URL; ?>division',
        type: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>",
            circle_id: circleId
        }),
        success: function(response) {
            console.log('Divisions API response:', response);
            var divisionSelect = $('#id_division');
            divisionSelect.empty();
            divisionSelect.append('<option value="">Select Division</option>');
            
            if (response && Array.isArray(response)) {
                response.forEach(function(division) {
                    divisionSelect.append('<option value="' + division.division_id + '">' + division.division_name + '</option>');
                });
            } else {
                console.error('API Error:', 'Invalid division response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching divisions:', error);
        }
    });
}

function loadSubDivisions(divisionId) {
    console.log('Loading subdivisions for division ID:', divisionId);
    $.ajax({
        url: '<?php echo API_URL; ?>subdivision',
        type: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>",
            division_id: divisionId
        }),
        success: function(response) {
            console.log('Subdivisions API response:', response);
            var subDivisionSelect = $('#id_sub');
            subDivisionSelect.empty();
            subDivisionSelect.append('<option value="">Select Subdivision</option>');
            
            if (response && Array.isArray(response)) {
                response.forEach(function(subdivision) {
                    subDivisionSelect.append('<option value="' + subdivision.sub_id + '">' + subdivision.subdivision_name + '</option>');
                });
            } else {
                console.error('API Error:', 'Invalid subdivision response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching subdivisions:', error);
        }
    });
}

function loadTalukas(subId) {
        $.ajax({
            url: '<?php echo API_URL; ?>taluka',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                access_token: "<?php echo $_SESSION['access_token']; ?>",
                sub_id: subId
            }),
            success: function(response) {
                var talukaSelect = $('#id_taluka');
                talukaSelect.empty();
                talukaSelect.append('<option value="">All Talukas</option>');
                
                if (response && Array.isArray(response)) {
                    response.forEach(function(taluka) {
                        talukaSelect.append('<option value="' + taluka.taluka_id + '">' + taluka.taluka_name + '</option>');
                    });
                } else {
                    console.error('API Error:', 'Invalid taluka response format');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching talukas:', error);
            }
        });
    }

// Function to load departments
function loadDepartments() {
    console.log('Loading departments...');
    $.ajax({
        url: API_URL + 'department',
        type: 'POST',
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        contentType: 'application/json',
        success: function(response) {
            if (response && response.is_successful && Array.isArray(response.data)) {
                var deptSelect = $('#current_department');
                deptSelect.empty().append('<option value="">Select Department</option>');
                
                response.data.forEach(function(dept) {
                    deptSelect.append(`<option value="${dept.department_id}">${dept.department_name}</option>`);
                });
            } else {
                console.error('Failed to load departments:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading departments:', error);
        }
    });
}

function loadProposedWorks() {
    console.log('Loading proposed works...');
    $.ajax({
        url: '<?php echo API_URL; ?>proposed-work-listing',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
            console.log('Proposed works API response:', response);
            if (response.is_successful === "1" && Array.isArray(response.data)) {
                const $proposedWorkSelect = $('#proposed_work_id');
                $proposedWorkSelect.empty().append('<option value="">-- Select Proposed Work --</option>');
                
                response.data.forEach(function(work) {
                    $proposedWorkSelect.append(
                        `<option value="${work.proposed_work_id}">${work.proposed_work_name}</option>`
                    );
                });
            } else {
                console.error('Failed to load proposed works:', response);
                showToast('warning', 'Warning', 'Unable to load proposed works. Please try refreshing the page.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading proposed works:', error);
            showToast('danger', 'Error', 'Failed to load proposed works. Please check your connection.');
        }
    });
}

function showToast(type, title, message) {
    const toast = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;
    
    $('.toast-container').append(toast);
    $('.toast').toast({ autohide: true, delay: 5000 }).toast('show');
    
    // Remove toast after it's hidden
    $('.toast').on('hidden.bs.toast', function () {
        $(this).remove();
    });
}

// Update file input labels when files are selected
$(document).on('change', '.custom-file-input', function() {
    const fileName = $(this).val().split('\\').pop();
    if (fileName) {
        $(this).siblings('.custom-file-label')
            .addClass('selected')
            .html(fileName);
    }
});

function loadEmployees() {
    console.log('Loading employees...');
}

function submitProjectForm() {
    $('#saveProject').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    const projectData = {
        access_token: '<?php echo $_SESSION["access_token"]; ?>',
        project_type_id: $('#project_type_id').val(),
        proposed_work_id: $('#proposed_work_id').val(),
        project_name: $('#project_name').val(),
        job_no: $('#job_no').val(),
        job_no_reference_date: $('#job_no_reference_date').val() || null,
        client_name: $('#client_name').val(),
        description: $('#description').val(),
        start_date: $('#start_date').val() || null,
        end_date: $('#end_date').val() || null,
        circle_id: $('#id_circle').val(),
        division_id: $('#id_division').val(),
        sub_id: $('#id_sub').val(),
        taluka_id: $('#id_taluka').val(),
        estimated_amount: $('#estimated_amount').val() || '0.00',
        length: $('#length').val() || '0.00',
        status: 'Pending',
        priority: $('#priority').val(),
    };

    console.log('Submitting project data:', projectData);

    $.ajax({
        url: API_URL + 'project-add',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(projectData),
        success: function(response) {
            console.log('Project API Response:', response);
            if (response.is_successful === "1") {
                const projectId = response.data.project_id;
                $('#project_id').val(projectId);

                uploadAttachments(projectId, function(uploadedUUIDs) {
                    updateProjectWithAttachments(projectId, uploadedUUIDs);
                    $(document).Toasts('create', {
                    class: 'bg-success',
                    title: 'success',
                    body: response.success_message,
                    autohide: true,
                    delay: 3000
                });
                    setTimeout(() => {
                        window.location.href = 'projects.php';
                    }, 10000);
                });

            } else {
                throw new Error(response.errors || 'Failed to save project');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving project:', error);
            $('#saveProject').prop('disabled', false).html('<i class="fas fa-save"></i> Save Project');
            $(document).Toasts('create', {
                    class: 'bg-denger',
                    title: 'denger',
                    body: response.success_message,
                    autohide: true,
                    delay: 3000
                });
                  


            // showToast('danger', 'Error', 'Failed to save project. Please try again.');
        }
    });
}

function uploadAttachments(projectId, callback) {
    const fileInputs = [
        { id: 'administrative_approval' },
        { id: 'dtp_section' },
        { id: 'technical_section' }
    ];

    let uploadsCompleted = 0;
    const totalUploads = fileInputs.length;
    const uploadedUUIDs = {};

    fileInputs.forEach(function(fileInput) {
        const input = document.getElementById(fileInput.id);
        if (input.files && input.files[0]) {
            const formData = new FormData();
            formData.append('access_token', "<?php echo $_SESSION['access_token']; ?>");
            formData.append('project_id', projectId);
            formData.append('attachment', input.files[0]);

            $(`#${fileInput.id}_uploaded`).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');

            $.ajax({
                url: API_URL + 'attachment-add',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.is_successful === "1") {
                        const file = response.data.attachments[0];
                        const fileUUID = file.uuid || file.UUID || file.attachment_id;
uploadedUUIDs[fileInput.id] = fileUUID;
                        $(`#${fileInput.id}_uploaded`).html(`
                            <div class="alert alert-success p-2 mt-2">
                                <i class="fas fa-check-circle"></i> Uploaded
                            </div>
                        `);
                    } else {
                        $(`#${fileInput.id}_uploaded`).html(`<div class="alert alert-danger">Upload failed</div>`);
                        console.error('Upload failed:', response.errors);
                    }
                },
                error: function(xhr, status, error) {
                    $(`#${fileInput.id}_uploaded`).html(`<div class="alert alert-danger">Error</div>`);
                    console.error('Upload error:', error);
                },
                complete: function() {
                    uploadsCompleted++;
                    if (uploadsCompleted >= totalUploads && typeof callback === 'function') {
                        callback(uploadedUUIDs);
                    }
                }
            });
        } else {
            uploadsCompleted++;
            if (uploadsCompleted >= totalUploads && typeof callback === 'function') {
                callback(uploadedUUIDs);
            }
        }
    });
}

function updateProjectWithAttachments(projectId, uploadedUUIDs) {
    const updatedProjectData = {
        access_token: '<?php echo $_SESSION["access_token"]; ?>',
        project_type_id: $('#project_type_id').val(),
        proposed_work_id: $('#proposed_work_id').val(),
        project_name: $('#project_name').val(),
        job_no: $('#job_no').val(),
        job_no_reference_date: $('#job_no_reference_date').val() || null,
        client_name: $('#client_name').val(),
        description: $('#description').val(),
        start_date: $('#start_date').val() || null,
        end_date: $('#end_date').val() || null,
        circle_id: $('#id_circle').val(),
        division_id: $('#id_division').val(),
        sub_id: $('#id_sub').val(),
        taluka_id: $('#id_taluka').val(),
        estimated_amount: $('#estimated_amount').val() || '0.00',
        length: $('#length').val() || '0.00',
        status: 'Pending',
        priority: $('#priority').val(),
        project_id: projectId,
        administrative_approval: uploadedUUIDs['administrative_approval'] || '',
        dtp_section: uploadedUUIDs['dtp_section'] || '',
        technical_section: uploadedUUIDs['technical_section'] || ''
    };

    console.log('Updating project with attachments:', updatedProjectData);

    $.ajax({
        url: API_URL + 'project-update',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(updatedProjectData),
        success: function(response) {
            if (response.is_successful === "1") {
                console.log('Project updated with attachments');
            } else {
                showToast('danger', 'Error', 'Failed to update project with attachments');
            }
        },
        error: function(xhr, status, error) {
            showToast('danger', 'Error', 'Error during project update: ' + error);
        }
    });
}




// Main document ready function
// Function to format date as YYYY-MM-DD
function formatDate(date) {
    const d = new Date(date);
    let month = '' + (d.getMonth() + 1);
    let day = '' + d.getDate();
    const year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

$(document).ready(function() {
    console.log('jQuery loaded, initializing components');
    
    // Set today's date as default for start date
    const today = new Date();
    const formattedDate = formatDate(today);
    $('#start_date').val(formattedDate);
    
    // Initialize file input change handlers
    $('input[type="file"]').on('change', function() {
        const type = $(this).attr('id');
        if (this.files && this.files[0]) {
            uploadFile(this, type);
        }
    });
    
    // Update file input label to show selected file name
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    
    // Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Format date inputs to display in dd-mm-yyyy format
    function formatDateInput(input) {
        const date = new Date(input.val());
        if (!isNaN(date.getTime())) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            // Update the display value (placeholder)
            input.attr('data-date', `${day}-${month}-${year}`);
        }
    }

    // Initialize date inputs
    $('input[type="date"]').each(function() {
        formatDateInput($(this));
    });

    // Handle date input changes
    $('input[type="date"]').on('change', function() {
        formatDateInput($(this));
    });
    
    // Set minimum end date based on start date
    $('#start_date').on('change', function() {
        var startDate = new Date($(this).val());
        if (!isNaN(startDate.getTime())) {
            // Set min date for end date
            $('#end_date').attr('min', $(this).val());
            
            // If end date is before start date, clear it
            var endDate = new Date($('#end_date').val());
            if (endDate < startDate) {
                $('#end_date').val('');
            }
        }
    });
    
    // Set maximum start date based on end date
    $('#end_date').on('change', function() {
        var endDate = new Date($(this).val());
        if (!isNaN(endDate.getTime())) {
            // Set max date for start date
            $('#start_date').attr('max', $(this).val());
            
            // If start date is after end date, clear it
            var startDate = new Date($('#start_date').val());
            if (startDate > endDate) {
                $('#start_date').val('');
            }
        }
    });
    
    // Handle circle selection change
    $('#id_circle').on('change', function() {
        var selectedCircleId = $(this).val();
        if (selectedCircleId) {
            loadDivisions(selectedCircleId);
        } else {
            $('#id_division').empty().append('<option value="">Select Division</option>');
            // Reset dependent dropdowns
            $('#id_sub').empty().append('<option value="">Select Subdivision</option>');
            $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
        }
    });

    // Handle division selection change
    $('#id_division').on('change', function() {
        var selectedDivisionId = $(this).val();
        if (selectedDivisionId) {
            loadSubDivisions(selectedDivisionId);
        } else {
            $('#id_sub').empty().append('<option value="">Select Subdivision</option>');
        }
        // Reset dependent dropdown
        $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
    });
    
    // Handle subdivision selection change
    $('#id_sub').on('change', function() {
        var selectedSubId = $(this).val();
        if (selectedSubId) {
            loadTalukas(selectedSubId);
        } else {
            $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
        }
    });
    
    // Form submission handler
    $('#addProjectForm').on('submit', function(e) {
        e.preventDefault();
        submitProjectForm();
    });
    
    // Save button click handler
    $('#saveProject').on('click', function(e) {
        e.preventDefault();
        submitProjectForm();
    });
    
    // Add active class to navigation
    $('#projects-menu').addClass('active');
    
    // Load initial data
    console.log('Loading initial data...');
    loadProjectTypes();
    loadCircles();
    loadDepartments();
    loadEmployees();
    loadProposedWorks();
});

// API functions are defined at the top of the script
</script>

<?php include 'common/footer.php'; ?>