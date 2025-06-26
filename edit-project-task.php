<?php 
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get and decode task_id from URL parameter
$task_id = isset($_GET['id']) ? base64_decode($_GET['id']) : null;

// Redirect if no valid ID - must be before any output
if (!$task_id) {
    header('Location: project-task-list.php');
    exit();
}

// Now include header.php after any potential redirects
include 'common/header.php';

// Fetch task details using API
if ($task_id) {
    $api_url = API_URL . 'project-task-edit';
    $headers = array(
        'Content-Type: application/json'
    );
    
    $post_data = array(
        'access_token' => $_SESSION['access_token'],
        'task_id' => $task_id
    );
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200) {
        $result = json_decode($response, true);
        if ($result['is_successful'] == '1') {
            $task_data = $result['data'];
        } else {
            echo "<div class='alert alert-danger'>Error: " . $result['errors'] . "</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>Error fetching task details</div>";
        exit;
    }
}
?>

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container-fluid">
  <div class="card card-primary">
    <div class="card-header" style="background-color:transparent !important; border-bottom: 1px solid rgba(0, 0, 0, .125) !important; border-top: 3px solid #30b8b9 !important; color: #212529 !important;">
      <h3 class="card-title">Edit Project Task</h3>
    </div>
    <div class="card-body">
      <form id="projectTaskForm" method="POST" action="">
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
<input type="hidden" id="encoded_task_id" value="<?php echo base64_encode($task_id); ?>">
<?php if (isset($task_data)): ?>
<input type="hidden" name="project_id" id="project_id" value="<?php echo $task_data['project_id']; ?>">
<input type="hidden" name="dept_id" id="dept_id" value="<?php echo $task_data['dept_id']; ?>">
<?php endif; ?>
        <!-- Project Selection -->
        <div class="form-group mb-3">
          <label for="id_project" class="form-label">Project</label>
          <div class="input-group">
            <select class="form-select" id="id_project" name="project" required disabled>
              <option value="">Select a Project</option>
              <?php if (isset($task_data)): ?>
              <option value="<?php echo $task_data['project_id']; ?>" disabled>
                <?php echo htmlspecialchars($task_data['project_name']); ?>
              </option>
              <?php endif; ?>
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
            <select class="form-select" id="id_dept" name="department" required disabled>
              <option value="">Select a Department</option>
              <?php if (isset($task_data)): ?>
              <option value="<?php echo $task_data['dept_id']; ?>"  disabled>
                <?php echo htmlspecialchars($task_data['dept_name']); ?>
              </option>
              <?php endif; ?>
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
            <input type="text" class="form-control" id="task_name" name="task_name" value="<?php echo isset($task_data) ? htmlspecialchars($task_data['task_name']) : ''; ?>" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-tasks"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <div class="form-group mb-3">
          <label for="id_assign" class="form-label">Assign To</label>
          <div class="input-group">
            <select class="form-select" id="id_assign" name="id_assign" required>
              <option value="">Select an Employee</option>
            </select>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <?php
        // Function to convert date from 'D, d M Y H:i:s T' to 'Y-m-d' format for date input
        function formatDateForInput($dateString) {
            if (empty($dateString)) return '';
            try {
                $date = new DateTime($dateString);
                return $date->format('Y-m-d');
            } catch (Exception $e) {
                return '';
            }
        }
        ?>
        <!-- priority -->
        <div class="form-group mb-3">
            <label for="priority" class="required-label">Priority:</label>
            <select id="priority" name="priority" class="form-control" required>
                    <option value="" <?php echo !isset($task_data['task_priority']) ? 'selected' : ''; ?>>-- Select Priority --</option>
                    <option value="High" <?php echo (isset($task_data['task_priority']) && strtolower($task_data['task_priority']) === 'high') ? 'selected' : ''; ?>>High</option>
                    <option value="Regular" <?php echo (isset($task_data['task_priority']) && strtolower($task_data['task_priority']) === 'regular') ? 'selected' : ''; ?>>Regular</option>
                </select>
                <div class="error-feedback" id="priority_error"></div>
        </div>
        
        <!-- Start Date -->
         <div class='row'>
        <div class="form-group mb-3 col-md-6">
          <label for="start_date" class="form-label">Start Date</label>
          <div class="input-group">
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($task_data['start_date']) ? formatDateForInput($task_data['start_date']) : ''; ?>" >
            <div class="input-group-append">
           
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <!-- End Date -->
        <div class="form-group mb-3 col-md-6">
          <label for="end_date" class="form-label">End Date</label>
          <div class="input-group">
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($task_data['end_date']) ? formatDateForInput($task_data['end_date']) : ''; ?>" >
            <div class="input-group-append">
              
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>
        </div>

        <!-- Task Status -->
        <!-- <div class="form-group mb-3">
          <label for="id_task_status" class="form-label">Task Status</label>
          <div class="input-group">
            <select class="form-select" id="id_task_status" name="task_status" required>
              <option value="">-- Select Status --</option>
              <option value="1" <?php echo (isset($task_data) && $task_data['task_status'] == '1') ? 'selected' : ''; ?>>Pending</option>
              <option value="2" <?php echo (isset($task_data) && $task_data['task_status'] == '2') ? 'selected' : ''; ?>>In Progress</option>
              <option value="3" <?php echo (isset($task_data) && $task_data['task_status'] == '3') ? 'selected' : ''; ?>>Completed</option>
            </select>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div> -->

        <div class="mt-3">
          <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border:none;">Update</button>
          <a href="project-task-list.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
// Make task data available to JavaScript
var taskData = <?php echo isset($task_data) ? json_encode($task_data) : 'null'; ?>;

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

$(document).ready(function() {
  // Initialize Select2 for dropdowns
  $('#id_project, #id_dept, #id_task_status,#id_assign').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#projectTaskForm')
  });
  
  // Add active class to navigation
  $('#project-task a').addClass('active nav-link');
});

// Handle form submission
$('#projectTaskForm').on('submit', function(e) {
  e.preventDefault();
  
  // Reset validation
  $('.is-invalid').removeClass('is-invalid');
  $('.invalid-feedback').remove();
  
  // Basic form validation
  var isValid = true;
  $('.form-control:required').each(function() {
    if ($(this).val() === '') {
      isValid = false;
      $(this).addClass('is-invalid');
      $(this).next('.invalid-feedback').text('This field is required').show();
    } else {
      $(this).removeClass('is-invalid');
      $(this).next('.invalid-feedback').hide();
    }
  });
  
  // Validate dates
  if (!validateDates()) {
    isValid = false;
  }
  
  if (!isValid) {
    return false;
  }
  
  // Get the selected employee ID from Select2
  var selectedEmployee = $('#id_assign').val();
  
  var formData = {
    access_token: '<?php echo $_SESSION["access_token"]; ?>',
    task_id: $('input[name="task_id"]').val(),
    project_id: $('#project_id').val(),
    dept_id: $('#dept_id').val(),
    task_name: $('#task_name').val(),
    assigned_emp_id: selectedEmployee,
    start_date: $('#start_date').val(),
    end_date: $('#end_date').val(),
    task_status: $('#id_task_status').val(),
    task_priority: $('#priority').val()
  };
  
  // Validate required fields
  if (!formData.assigned_emp_id) {
    $(document).Toasts('create', {
      class: 'bg-warning',
      title: 'Validation Error',
      position: 'bottomRight',
      body: 'Please select an employee to assign the task to.',
      autohide: true,
      delay: 3000
    });
    $('#id_assign').focus();
    return false;
  }

  $.ajax({
    url: '<?php echo API_URL; ?>project-task-update',
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
          window.location.href = 'project-task-list.php';
        }, 1000);
      } else {
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          position: 'bottomRight',
          body: response.errors,
          autohide: true,
          delay: 3000
        });
      }
    },
    error: function() {
      $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Error',
        position: 'bottomRight',
        body: 'Failed to update task. Please try again.',
        autohide: true,
        delay: 3000
      });
    }
  });
});

function loadEmployees(callback) {
  var apiUrl = '<?php echo API_URL; ?>user';
  var accessToken = "<?php echo $_SESSION['access_token']; ?>";
  var $select = $('#id_assign');

  var selectedEmpId = '<?php echo isset($task_data['assigned_emp_id']) ? $task_data['assigned_emp_id'] : ''; ?>';
  var selectedEmpName = '<?php echo isset($task_data['assigned_emp_name']) ? addslashes($task_data['assigned_emp_name']) : ''; ?>';

  // Show loading state
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
        var hasSelectedEmp = false;
        
        response.data.forEach(function (emp) {
          if (emp.emp_status == 1) {
            var isSelected = (selectedEmpId && emp.emp_id == selectedEmpId);
            if (isSelected) hasSelectedEmp = true;
            $select.append(new Option(
              emp.emp_name,
              emp.emp_id,
              isSelected,
              isSelected
            ));
          }
        });
        
        // If the selected employee wasn't in the list but we have their data, add them
        
        if (selectedEmpId && selectedEmpName && !hasSelectedEmp) {
          $select.append(new Option(
            selectedEmpName,
            selectedEmpId,
            true,
            true
          ));
        }
        
        $select.prop('disabled', false).trigger('change');
      } else {
        // If no employees but we have a selected one, add it
        if (selectedEmpId && selectedEmpName) {
          $select.append(new Option(
            selectedEmpName,
            selectedEmpId,
            true,
            true
          ));
        } else {
          $select.append('<option value="">No employees available</option>');
        }
        
        if (!selectedEmpId || !selectedEmpName) {
          $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'No Employees',
            position: 'bottomRight',
            body: 'No active employees found',
            autohide: true,
            delay: 3000
          });
        }
      }
      
      // Execute callback if provided
      if (typeof callback === 'function') {
        callback();
      }
    },
    error: function (xhr, status, error) {
      $select.empty().append('<option value="">Error loading employees</option>');
      // If we have a selected employee, add them even on error
      if (selectedEmpId && selectedEmpName) {
        $select.append(new Option(
          selectedEmpName,
          selectedEmpId,
          true,
          true
        ));
      }
      
      $(document).Toasts('create', {
        class: 'bg-danger',
        title: 'Error',
        position: 'bottomRight',
        body: 'Failed to load employees. ' + (error || ''),
        autohide: true,
        delay: 3000
      });
      console.error("Failed to load employees:", error);
    }
  });
}

// Function to set the selected employee after loading
function setSelectedEmployee(empId, empName) {
  if (empId && empName) {
    // If the option doesn't exist, create and append it
    if ($('#id_assign option[value="' + empId + '"]').length === 0) {
      $('#id_assign').append(new Option(empName, empId, true, true));
    }
    // Set the value and trigger change
    $('#id_assign').val(empId).trigger('change');
  }
}

// Initialize Select2 on the employee dropdown
$('#id_assign').select2({
  theme: 'bootstrap-5',
  width: '100%',
  placeholder: 'Select an Employee',
  allowClear: true
});

// Get the assigned employee data from PHP
var assignedEmpId = '<?php echo isset($task_data['assigned_emp_id']) ? $task_data['assigned_emp_id'] : ''; ?>';
var assignedEmpName = '<?php echo isset($task_data['assigned_emp_name']) ? addslashes($task_data['assigned_emp_name']) : ''; ?>';

// Load employees and set the selected one
$(document).ready(function() {
  if (assignedEmpId && assignedEmpName) {
    loadEmployees(function() {
      setSelectedEmployee(assignedEmpId, assignedEmpName);
    });
  } else {
    loadEmployees();
  }
});

// Reload employees when department changes
$('#id_dept').on('change', function() {
  loadEmployees();
});

// Set up date validation
if (taskData && taskData.start_date) {
  var startDate = new Date(taskData.start_date);
  var nextDay = new Date(startDate);
  nextDay.setDate(nextDay.getDate() + 1);
  var nextDayFormatted = nextDay.toISOString().split('T')[0];
  $('#end_date').attr('min', nextDayFormatted);
}

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


<?php include 'common/footer.php'; ?>