<?php include 'common/header.php'; ?>

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container-fluid">
  <div class="card card-primary" >
    <div class="card-header" style="background-color: transparent;color: #212529;border-bottom: 1px solid rgba(0, 0, 0, .125);border-top: 3px solid #30b8b9;">
      <h3 class="card-title" >Add Project Task</h3>
    </div>
    <div class="card-body">
      <form id="projectTaskForm" method="POST" action="">
        <!-- Project Selection -->
        <div class="form-group mb-3">
          <label for="id_project" class="form-label">Project</label>
          <div class="input-group">
            <select class="form-select" id="id_project" name="project" required>
              <option value="">Select a Project</option>
            </select>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-project-diagram"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <!-- Department Selection -->
        <div class="form-group mb-3">
          <label for="id_dept" class="form-label">Department</label>
          <div class="input-group">
            <select class="form-select" id="id_dept" name="department" required>
              <option value="">Select a Department</option>
            </select>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-building"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <!-- Task Name -->
        <div class="form-group mb-3">
          <label for="task_name" class="form-label">Task Name</label>
          <div class="input-group">
            <input type="text" class="form-control" id="task_name" name="task_name" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-tasks"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <div class="form-group mb-3">
          <label for="id_assign" class="form-label">Assign To</label>
          <div class="input-group">
            <select class="form-select" id="id_assign" name="id_assign" style="width: 97%;" required>
              <option value="">Select an Employee</option>
            </select>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <!-- Start Date -->
        <div class='row'>
        <div class="form-group mb-3 col-md-6">
          <label for="start_date" class="form-label">Start Date</label>
          <div class="input-group">
            <input type="date" class="form-control" id="start_date" name="start_date" required>
              <!-- <div class="input-group-append">
            
              </div> -->
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <!-- End Date -->
        <div class="form-group mb-3 col-md-6">
          <label for="end_date" class="form-label">End Date</label>
          <div class="input-group">
            <input type="date" class="form-control" id="end_date" name="end_date" required>
            <!-- <div class="input-group-append">
             
            </div> -->
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>
        </div>

        <div class="mt-3">
          <div class="col-12 pl-0">
            <button type="submit" class="btn btn-primary m-0" style="background-color: #30b8b9;border:1px solid #30b8b9;">Save</button>
            <a href="project-task-list" class="btn btn-secondary">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
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
  // Set today's date as default for start date
  const today = new Date();
  const formattedDate = formatDate(today);
  $('#start_date').val(formattedDate);
  
  // Initialize Select2 for dropdowns
  $('#id_project, #id_dept,#id_assign').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#projectTaskForm')
  });
  
  // Add active class to navigation
  $('#project-task a').addClass('active nav-link');

  // Date validation function
  function validateDates() {
    var startDate = new Date($('#start_date').val());
    var endDate = new Date($('#end_date').val());
    
    if (endDate <= startDate) {
      $('#end_date').addClass('is-invalid');
      $('<div class="invalid-feedback">End date must be greater than start date</div>').insertAfter($('#end_date').parent());
      return false;
    }
    return true;
  }

  // Handle form submission
  $('#projectTaskForm').on('submit', function(e) {
    e.preventDefault();
    
    // Reset validation
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Validate dates
    if (!validateDates()) {
      return false;
    }
    
    var formData = {
      access_token: '<?php echo $_SESSION["access_token"]; ?>',
      project_id: $('#id_project').val(),
      dept_id: $('#id_dept').val(),
      task_name: $('#task_name').val(),
      assigned_emp_id: $('#id_assign').val(),
      start_date: $('#start_date').val(),
      end_date: $('#end_date').val(),
      task_status: 0
    };

    $.ajax({
      url: '<?php echo API_URL; ?>project-task-add',
      type: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.is_successful === '1') {
          $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Success',
            position: 'bottomRight',
            body: response.success_message,
            autohide: true,
            delay: 3000
          });
          setTimeout(function() {
            window.location.href = 'project-task-list';
          }, 1000);
        } else {
          $(document).Toasts('create', {
            class: 'bg-danger',
            title: 'Error',
            position: 'bottomRight',
            body: response.errors || 'Failed to add task',
            autohide: true,
            delay: 3000
          });
        }
      },
      error: function(xhr, status, error) {
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          position: 'bottomRight',
          body: 'Failed to add task. Please try again.',
          autohide: true,
          delay: 3000
        });
      }
    });
  });

  // Load projects
  $.ajax({
    url: '<?php echo API_URL; ?>project-listing',
    type: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    data: JSON.stringify({
      access_token: '<?php echo $_SESSION["access_token"]; ?>'
    }),
    success: function(response) {
      if (response.is_successful === '1' && response.data && response.data.projects) {
        var projectSelect = $('#id_project');
        response.data.projects.forEach(function(project) {
          projectSelect.append(new Option(project.project_name, project.project_id));
        });
        projectSelect.trigger('change');
      } else {
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          position: 'bottomRight',
          body: 'Failed to load projects. ' + response.errors,
          autohide: true,
          delay: 3000
        });
      }
    },
    error: function(xhr, status, error) {
      $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Error',
        position: 'bottomRight',
        body: 'Failed to load projects. Please try again.',
        autohide: true,
        delay: 3000
      });
    }
  });

  // Load departments
  $.ajax({
    url: '<?php echo API_URL; ?>department',
    type: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    data: JSON.stringify({
      access_token: '<?php echo $_SESSION["access_token"]; ?>'
    }),
    success: function(response) {
      if (response.is_successful === '1' && response.data) {
        var deptSelect = $('#id_dept');
        response.data.forEach(function(dept) {
          deptSelect.append(new Option(dept.dept_name, dept.dept_id));
        });
        deptSelect.trigger('change');
      } else {
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          position: 'bottomRight',
          body: 'Failed to load projects. ' + response.errors,
          autohide: true,
          delay: 3000
        });
      }
    },
    error: function(xhr, status, error) {
      $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Error',
        position: 'bottomRight',
        body: 'Failed to load projects. Please try again.',
        autohide: true,
        delay: 3000
      });
    }
  });
});

function loadEmployees() {
    var apiUrl = '<?php echo API_URL; ?>user';
    var accessToken = "<?php echo $_SESSION['access_token']; ?>";

    // Show loading state
    var $select = $('#id_assign');
    $select.prop('disabled', true).empty().append('<option value="">Loading employees...</option>').trigger('change');

    $.ajax({
      url: apiUrl,
      method: "POST",
      headers: {
        'Content-Type': 'application/json'
      },
      data: JSON.stringify({ 
        access_token: accessToken,
        dept_id: <?php echo $_SESSION['dept_id']; ?>
      }),
      success: function (response) {
        $select.empty().append('<option value="">Select an Employee</option>');

        if (response.is_successful === "1" && Array.isArray(response.data)) {
          response.data.forEach(function (emp) {
            if (emp.emp_status == 1) {
              $select.append(new Option(emp.emp_name, emp.emp_id));
            }
          });
          $select.prop('disabled', false);
        } else {
          $select.append('<option value="">No employees available</option>');
          $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'No Employees',
            position: 'bottomRight',
            body: 'No active employees found',
            autohide: true,
            delay: 3000
          });
        }
        $select.trigger('change');
      },
      error: function (xhr, status, error) {
        $select.empty().append('<option value="">Error loading employees</option>');
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          position: 'bottomRight',
          body: 'Failed to load employees. Please try again.',
          autohide: true,
          delay: 3000
        });
        console.error("Failed to load employees:", error);
      }
    });
  }
  
  // Reload employees when department changes
  $('#id_dept').on('change', function() {
    loadEmployees();
  });
  
  // Update end date min attribute when start date changes
  $('#start_date').on('change', function() {
    var startDate = $(this).val();
    if (startDate) {
      var nextDay = new Date(startDate);
      nextDay.setDate(nextDay.getDate() + 1);
      var nextDayFormatted = nextDay.toISOString().split('T')[0];
      $('#end_date').attr('min', nextDayFormatted);
      
      // If end date is before or equal to start date, update it to next day
      var endDate = $('#end_date').val();
      if (endDate && new Date(endDate) <= new Date(startDate)) {
        $('#end_date').val(nextDayFormatted);
      }
    }
  });
  
  // Validate end date when it changes
  $('#end_date').on('change', function() {
    validateDates();
  });
</script>

<style>
  .form-control, .form-select {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
  }
  .input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-left: none;
  }
  .input-group .form-control:not(:last-child),
  .input-group .form-select:not(:last-child) {
    border-right: none;
  }
  .form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
  .form-group {
    margin-bottom: 1.5rem;
  }
  .form-group label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #495057;
  }
  .select2-container--bootstrap-5 .select2-selection {
    border: 1px solid #ced4da;
    min-height: 38px;
  }
  .select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }
  .card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    margin-bottom: 1rem;
    border: none;
  }
  .card-primary .card-header {
    background-color: #007bff;
    color: white;
    border-bottom: 1px solid rgba(0,0,0,.125);
    padding: 0.75rem 1.25rem;
  }
  .card-body {
    padding: 1.25rem;
  }
  .btn {
    padding: 0.375rem 0.75rem;
    font-weight: 500;
    line-height: 1.5;
    border-radius: 4px;
  }
  .btn-primary {
    margin-right: 0.5rem;
    background-color: #007bff;
    border-color: #007bff;
  }
  .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
  }
  .invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
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
  background-color: #30b8b9 !important;
  color: #fff !important;
}
</style>


<?php include 'common/footer.php'; ?>