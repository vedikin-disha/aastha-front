<?php include 'common/header.php'; ?>

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container-fluid">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Add Project Task</h3>
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

        <!-- Task Status -->
        <div class="form-group mb-3">
          <label for="id_task_status" class="form-label">Task Status</label>
          <div class="input-group">
            <select class="form-select" id="id_task_status" name="task_status" required>
              <option value="">-- Select Status --</option>
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
            </div>
          </div>
          <div class="invalid-feedback" style="display: none;"></div>
        </div>

        <div class="mt-3">
          <div class="col-12">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="project-task-list.php" class="btn btn-secondary">Cancel</a>
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
$(document).ready(function() {
  // Initialize Select2 for dropdowns
  $('#id_project, #id_dept, #id_task_status').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#projectTaskForm')
  });
  
  // Add active class to navigation
  $('#project-task a').addClass('active nav-link');

  // Handle form submission
  $('#projectTaskForm').on('submit', function(e) {
    e.preventDefault();
    
    var formData = {
      access_token: '<?php echo $_SESSION["access_token"]; ?>',
      project_id: $('#id_project').val(),
      dept_id: $('#id_dept').val(),
      task_name: $('#task_name').val(),
      task_status: $('#id_task_status').val()
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
            window.location.href = 'project-task-list.php';
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
        console.error('Error adding task:', error);
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
</style>


<script>
$(document).ready(function() {
    $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Success',
        position: 'bottomRight',
        body: 'Project Task added successfully',
        autohide: true,
        delay: 5000
    });
  
});
</script>
<?php include 'common/footer.php'; ?>