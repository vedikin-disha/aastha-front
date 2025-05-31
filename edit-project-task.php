<?php 
include 'common/header.php';

// Get task_id from URL parameter
$task_id = isset($_GET['id']) ? $_GET['id'] : null;

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
    <div class="card-header">
      <h3 class="card-title">Edit Project Task</h3>
    </div>
    <div class="card-body">
      <form id="projectTaskForm" method="POST" action="">
        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
        <!-- Project Selection -->
        <div class="form-group mb-3">
          <label for="id_project" class="form-label">Project</label>
          <div class="input-group">
            <select class="form-select" id="id_project" name="project" required>
              <option value="">Select a Project</option>
              <?php if (isset($task_data)): ?>
              <option value="<?php echo $task_data['project_id']; ?>" selected>
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
            <select class="form-select" id="id_dept" name="department" required>
              <option value="">Select a Department</option>
              <?php if (isset($task_data)): ?>
              <option value="<?php echo $task_data['dept_id']; ?>" selected>
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

        <!-- Task Status -->
        <div class="form-group mb-3">
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
        </div>

        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Update</button>
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

$(document).ready(function() {
  // Initialize Select2 for dropdowns
  $('#id_project, #id_dept, #id_task_status').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#projectTaskForm')
  });
  
  // Add active class to navigation
  $('#project-task a').addClass('active nav-link');
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
  }
  .card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
  .btn {
    padding: 0.375rem 0.75rem;
    font-weight: 400;
    line-height: 1.5;
    border-radius: 4px;
  }
  .btn-primary {
    margin-right: 0.5rem;
  }
</style>


<script>
$(document).ready(function() {
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
          // Set the selected project if we have task data
          if (typeof taskData !== 'undefined' && taskData.project_id) {
            projectSelect.val(taskData.project_id);
          }
          projectSelect.trigger('change');
        } else {
          console.error('Error loading projects:', response.errors);
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
        console.error('Error loading projects:', error);
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
          // Set the selected department if we have task data
          if (typeof taskData !== 'undefined' && taskData.dept_id) {
            deptSelect.val(taskData.dept_id);
          }
          deptSelect.trigger('change');
        } else {
          console.error('Error loading projects:', response.errors);
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
        console.error('Error loading projects:', error);
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

    // Handle form submission
    $('#projectTaskForm').on('submit', function(e) {
      e.preventDefault();
      
      var formData = {
        access_token: '<?php echo $_SESSION["access_token"]; ?>',
        task_id: $('input[name="task_id"]').val(),
        project_id: $('#id_project').val(),
        dept_id: $('#id_dept').val(),
        task_name: $('#task_name').val(),
        task_status: $('#id_task_status').val()
      };

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
  });
</script>


<?php include 'common/footer.php'; ?> 