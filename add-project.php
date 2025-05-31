<?php include 'common/header.php'; ?>

<!-- Additional CSS -->
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">

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
        <div class="card-header">
            <h3 class="card-title">Project Details</h3>
        </div>
        <div class="card-body">
            <form id="addProjectForm">
                <div class="row">
                    <!-- Project Type -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="project_type_id" class="required-label">Project type id:</label>
                            <select id="project_type_id" name="project_type_id" class="form-control select2" required>
                                <option value="">Select Project Type</option>
                                <!-- Options will be loaded from API -->
                            </select>
                            <div class="error-feedback" id="project_type_id_error"></div>
                        </div>
                    </div>

                    <!-- Project Name -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="project_name" class="required-label">Project name:</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" 
                                placeholder="Enter project name" required>
                            <div class="error-feedback" id="project_name_error"></div>
                        </div>
                    </div>

                    <!-- Job No -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="job_no">Job no:</label>
                            <input type="text" class="form-control" id="job_no" name="job_no" 
                                placeholder="Enter job number">
                            <div class="error-feedback" id="job_no_error"></div>
                        </div>
                    </div>

                    <!-- Client Name -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="client_name">Client name:</label>
                            <input type="text" class="form-control" id="client_name" name="client_name" 
                                placeholder="Enter client name">
                            <div class="error-feedback" id="client_name_error"></div>
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start date:</label>
                            <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                <input type="date" class="form-control datetimepicker-input" id="start_date" 
                                    name="start_date" data-target="#start_date_picker" placeholder="dd-mm-yyyy">
                                <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                    <!-- <div class="input-group-date"><i class="fa fa-calendar"></i></div> -->
                                </div>
                            </div>
                            <div class="error-feedback" id="start_date_error"></div>
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End date:</label>
                            <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                <input type="date" class="form-control datetimepicker-input" id="end_date" 
                                    name="end_date" data-target="#end_date_picker" placeholder="dd-mm-yyyy">
                                <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                    <!-- <div class="input-group-text"><i class="fa fa-calendar"></i></div> -->
                                </div>
                            </div>
                            <div class="error-feedback" id="end_date_error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Circle ID -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_circle">Circle id:</label>
                            <select id="id_circle" name="circle_id" class="form-control select2">
                                <option value="">Select Circle</option>
                                <!-- Options will be loaded from API -->
                            </select>
                            <div class="error-feedback" id="circle_id_error"></div>
                        </div>
                    </div>

                    <!-- Division ID -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_division">Division id:</label>
                            <select id="id_division" name="division_id" class="form-control select2">
                                <option value="">Select Division</option>
                                <!-- Options will be loaded from API -->
                            </select>
                            <div class="error-feedback" id="division_id_error"></div>
                        </div>
                    </div>

                    <!-- Sub ID -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_sub">Sub:</label>
                            <select id="id_sub" name="sub_id" class="form-control select2">
                                <option value="">Select Subdivision</option>
                                <!-- Options will be loaded from API -->
                            </select>
                            <div class="error-feedback" id="sub_id_error"></div>
                        </div>
                    </div>

                    <!-- Taluka ID -->
                    <div class="col-md-3">
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

                <div class="row">
                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="required-label">Status:</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                <option value="Active">Active</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Planned">Planned</option>
                            </select>
                            <div class="error-feedback" id="status_error"></div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success" id="saveProject">
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
<script src="js/jquery-3.5.1.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>
// API functions defined first so they can be called immediately
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
                    options += `<option value="${type.project_type_id}">${type.type_name}</option>`;
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

// Direct API calls outside document.ready to ensure they run immediately
console.log('Starting API calls');
// Ensure jQuery is fully loaded before making API calls
$(function() {
    console.log('jQuery loaded, making API calls');
    loadProjectTypes();
    loadCircles();
});

$(document).ready(function() {
    // Initialize Select2 Elements
    // $('.select2').select2({
    //     theme: 'bootstrap4'
    // });
    
    // Initialize date pickers
    // $('#start_date_picker').datetimepicker({
    //     format: 'DD-MM-YYYY',
    //     icons: {
    //         time: 'far fa-clock'
    //     }
    // });

    // $('#end_date_picker').datetimepicker({
    //     format: 'DD-MM-YYYY',
    //     icons: {
    //         time: 'far fa-clock'
    //     },
    //     useCurrent: false
    // });

    // Link the two date pickers
    // $("#start_date_picker").on("change.datetimepicker", function (e) {
    //     $('#end_date_picker').datetimepicker('minDate', e.date);
    // });
    
    // $("#end_date_picker").on("change.datetimepicker", function (e) {
    //     $('#start_date_picker').datetimepicker('maxDate', e.date);
    // });
    
    // Handle circle selection change
    $('#id_circle').on('change', function() {
        var selectedCircleId = $(this).val();
        if (selectedCircleId) {
            loadDivisions(selectedCircleId);
        } else {
            $('#id_division').empty().append('<option value="">Select Division</option>');
        }
        // Reset dependent dropdowns
        $('#id_sub').empty().append('<option value="">Select Subdivision</option>');
        $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
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
    
    // Direct click handler for the save button
    $('#saveProject').on('click', function(e) {
        e.preventDefault();
        submitProjectForm();
    });
    
    // Function to submit the project form
    function submitProjectForm() {
        // Clear previous errors
        $('.error-feedback').text('');
        
        // Get date values directly from the input fields
        var startDateValue = $('#start_date').val();
        var endDateValue = $('#end_date').val();
        
        // Since the API requires YYYY-MM-DD format, we'll ensure our dates are in this format
        // If the date picker is disabled, users might enter dates in various formats
        
        // Helper function to ensure YYYY-MM-DD format
        function formatDateForAPI(dateStr) {
            if (!dateStr) return '';
            
            // If it's already in YYYY-MM-DD format, return as is
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                return dateStr;
            }
            
            // If it's in DD-MM-YYYY format
            var parts = dateStr.split('-');
            if (parts.length === 3) {
                // Check if the first part looks like a day (1-31)
                if (parseInt(parts[0]) >= 1 && parseInt(parts[0]) <= 31) {
                    return parts[2] + '-' + parts[1] + '-' + parts[0]; // Convert to YYYY-MM-DD
                }
            }
            
            // For any other format, try to create a valid date object and format it
            try {
                var date = new Date(dateStr);
                if (!isNaN(date.getTime())) {
                    // Valid date, format as YYYY-MM-DD
                    var year = date.getFullYear();
                    var month = (date.getMonth() + 1).toString().padStart(2, '0');
                    var day = date.getDate().toString().padStart(2, '0');
                    return year + '-' + month + '-' + day;
                }
            } catch (e) {
                console.error('Error parsing date:', e);
            }
            
            // If all else fails, return the original string
            return dateStr;
        }
        
        var formattedStartDate = formatDateForAPI(startDateValue);
        var formattedEndDate = formatDateForAPI(endDateValue);
        
        console.log('Original start date:', startDateValue, 'Formatted:', formattedStartDate);
        console.log('Original end date:', endDateValue, 'Formatted:', formattedEndDate);
        
        // Create form data object
        const formData = {
            access_token: "<?php echo $_SESSION['access_token']; ?>",
            project_type_id: $('#project_type_id').val(),
            project_name: $('#project_name').val(),
            job_no: $('#job_no').val(),
            client_name: $('#client_name').val(),
            start_date: formattedStartDate,
            end_date: formattedEndDate,
            circle_id: $('#id_circle').val(),
            division_id: $('#id_division').val(),
            sub_id: $('#id_sub').val(),
            taluka_id: $('#id_taluka').val(),
            status: $('#status').val()
        };
        
        console.log('Submitting project data:', formData);
        
        // Submit form data to the specified base URL
        $.ajax({
            url: api_url + 'project-add',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            beforeSend: function() {
                // Disable submit button and show loading state
                $('#saveProject').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            },
            success: function(response) {
                console.log('API Response:', response);
                if (response.is_successful === "1") {
                    // Show success message with the API success message
                    $('#formResult').removeClass('alert-danger').addClass('alert-success')
                        .html(response.success_message || 'Project added successfully!').show();
                    
                    // Display project ID if available
                    if (response.data && response.data.project_id) {
                        console.log('New Project ID:', response.data.project_id);
                    }
                    
                    // Clear form
                    $('#addProjectForm')[0].reset();
                    $('.select2').val(null).trigger('change');
                    
                    // Redirect to projects page after a delay
                    setTimeout(function() {
                        window.location.href = 'projects.php';
                    }, 2000);
                } else {
                    // Show validation errors
                    $('#formResult').removeClass('alert-success').addClass('alert-danger')
                        .html(response.errors || 'Failed to add project. Please check the form and try again.').show();
                    
                    if (response.errors) {
                        // Display field-specific errors
                        if (typeof response.errors === 'object') {
                            for (const field in response.errors) {
                                $('#' + field + '_error').text(Array.isArray(response.errors[field]) ? 
                                    response.errors[field].join(', ') : response.errors[field]);
                            }
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                // Show error message
                $('#formResult').removeClass('alert-success').addClass('alert-danger')
                    .html('An error occurred. Please try again later.').show();
                console.error('API Error:', error);
                console.error('Response:', xhr.responseText);
            },
            complete: function() {
                // Re-enable submit button
                $('#saveProject').prop('disabled', false).html('<i class="fas fa-save"></i> Save Project');
            }
        });
    }
    
    // Form submission
    $('#addProjectForm').on('submit', function(e) {
        e.preventDefault();
        submitProjectForm();
    });
    
    // Direct click handler for the save button
    $('#saveProject').on('click', function(e) {
        e.preventDefault();
        submitProjectForm();
    });
  
    // Add active class to navigation
    $('#projects-menu').addClass('active');
    
    // API calls are already made when page loads
});

// API functions are already defined at the top of the script

// API functions are defined at the top of the script
</script>

<?php include 'common/footer.php'; ?>