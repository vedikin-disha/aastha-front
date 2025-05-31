<?php include 'common/header.php'; ?>

<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/select2-bootstrap4.min.css" rel="stylesheet" />
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<style>
  .form-control {
    height: 38px;
  }
  .input-group-text {
    width: 40px;
    justify-content: center;
  }
  .task-item {
    margin-bottom: 0.5rem;
  }
</style>

<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Edit Project Template</h3>
  </div>
  <div class="card-body">
    <div id="successMessage" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
      <span id="successText"></span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
    </div>

    <form method="post" id="templateForm" onsubmit="return handleSubmit(event)">
      

      <!-- Project Type -->
      <div class="form-group">
        <label>Project Type <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-project-diagram"></i></span>
          <input type="text" name="type_name" class="form-control" placeholder="Enter project type name">
        </div>
        <div class="invalid-feedback" style="display: none;"></div>
      </div>

      <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="card mt-4">
          <div class="card-header"><h5>Department <?php echo $i; ?></h5></div>
          <div class="card-body">

            <!-- Department Dropdown -->
            <div class="form-group">
              <label>Department <?php echo $i; ?> <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
                <select name="dept<?php echo $i; ?>_id" class="form-control select2">
                  <option value="">Select Department</option>
                </select>
              </div>
              <div class="invalid-feedback" style="display: none;"></div>
            </div>

            <!-- Assigned Days -->
            <div class="form-group">
              <label>Assigned Days <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                <input type="number" name="dept<?php echo $i; ?>_assigned_days" class="form-control" placeholder="Enter assigned days">
              </div>
              <div class="invalid-feedback" style="display: none;"></div>
            </div>

            <!-- Tasks (unchanged) -->
            <div class="form-group">
              <label>Tasks</label>
              <div class="task-container" id="taskContainer<?php echo $i; ?>">
                 
                    <div class="task-item input-group">
                      <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                      <input type="text" name="dept<?php echo $i; ?>_task[]" class="form-control" placeholder="Enter task">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>
                      </div>
                    </div>
                

                <!-- New empty field -->
                <div class="task-item input-group">
                  <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                  <input type="text" name="dept<?php echo $i; ?>_task[]" class="form-control" placeholder="Enter task">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-success add-task"><i class="fas fa-plus"></i></button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      <?php endfor ?>
      

      <!-- Submit Buttons -->
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        <a href="project-template-list.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
  // Get project_type_id from URL parameter
  const urlParams = new URLSearchParams(window.location.search);
  const projectTypeId = urlParams.get('id');

  // Populate department dropdowns
  const departments = [
    { dept_id: 1, dept_name: 'Architecture' },
    { dept_id: 2, dept_name: 'Civil' },
    { dept_id: 3, dept_name: 'MEP' },
    { dept_id: 4, dept_name: 'Interior' },
    { dept_id: 5, dept_name: 'Project Management' }
  ];

  // Add department options to all dropdowns
  for (let i = 1; i <= 5; i++) {
    const select = $(`select[name="dept${i}_id"]`);
    departments.forEach(dept => {
      select.append(new Option(dept.dept_name, dept.dept_id));
    });
  }

  // Make API call to fetch project template data
  $.ajax({
    url: '<?= API_URL ?>template-edit',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODQyNTU4OCwianRpIjoiMmE0NjIzOTUtY2YwZi00NzY3LTlkYzgtMzA1ZTlkODMzMDZkIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6ImplbnNpLmNoYW5nYW5pQHZlZGlraW4uY29tIiwibmJmIjoxNzQ4NDI1NTg4LCJjc3JmIjoiZTVmOGJlNzgtZTQ0Ny00NjRlLWI5M2EtODljNzY5YmE1MDZkIiwiZXhwIjoxNzQ4NTExOTg4fQ.OZ94UxHkpdiNORfT3EB5sCqeKQi0oH_qgvmec6NJ_9M",
      "project_type_id": projectTypeId
    }),
    success: function(response) {
      if (response.is_successful === "1") {
        const data = response.data;
        
        // Populate form fields
        $('input[name="type_name"]').val(data.project_type_name);
        
        // Populate department data
        for (let i = 1; i <= 5; i++) {
          $(`select[name="dept${i}_id"]`).val(data[`dept${i}_id`]);
          $(`input[name="dept${i}_assigned_days"]`).val(data[`dept${i}_assigned_days`]);
        }

        // Clear all task fields first
        for (let i = 1; i <= 5; i++) {
          const taskContainer = $(`#taskContainer${i}`);
          taskContainer.find('.task-item:not(:last)').remove();
          taskContainer.find('input[type="text"]').val('');
        }

        // Populate tasks
        if (data.tasks && data.tasks.length > 0) {
          data.tasks.forEach(task => {
            const deptId = task.dept_id;
            const taskContainer = $(`#taskContainer${deptId}`);
            const firstInput = taskContainer.find('input[type="text"]').first();
            firstInput.val(task.task_name);
          });
        }
      } else {
        alert('Failed to load project template data: ' + response.errors);
      }
    },
    error: function(xhr, status, error) {
      alert('Error loading project template data: ' + error);
    }
  });

  $('.select2').select2({
    theme: 'bootstrap4',
    placeholder: 'Select Department',
    allowClear: true
  });

  // Add task field
  $('.task-container').on('click', '.add-task', function() {
    const taskItem = $(this).closest('.task-item');
    const newTask = taskItem.clone();
    newTask.find('input').val('');
    taskItem.find('.input-group-append').html('<button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>');
    taskItem.after(newTask);
  });

  // Remove task field
  $('.task-container').on('click', '.remove-task', function() {
    const taskItem = $(this).closest('.task-item');
    const container = taskItem.closest('.task-container');

    if (container.find('.task-item').length > 1) {
      if (!taskItem.next('.task-item').length && taskItem.prev('.task-item').length) {
        taskItem.prev('.task-item').find('.input-group-append').html('<button type="button" class="btn btn-success add-task"><i class="fas fa-plus"></i></button>');
      }
      taskItem.remove();
    }
  });

  $('#project-template a').addClass('active nav-link');
});
</script>

<script>
function handleSubmit(event) {
  event.preventDefault();
  
  const urlParams = new URLSearchParams(window.location.search);
  const projectTypeId = urlParams.get('id');
  const projectTypeName = $('input[name="type_name"]').val();

  // Prepare tasks array
  let tasks = [];
  $('.task-container').each(function(index) {
    const deptId = index + 1;
    $(this).find('input[type="text"]').each(function() {
      const taskName = $(this).val().trim();
      if (taskName) {
        tasks.push({
          task_name: taskName,
          dept_id: deptId
        });
      }
    });
  });

  // Prepare request data
  const requestData = {
    access_token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODQyNTU4OCwianRpIjoiMmE0NjIzOTUtY2YwZi00NzY3LTlkYzgtMzA1ZTlkODMzMDZkIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6ImplbnNpLmNoYW5nYW5pQHZlZGlraW4uY29tIiwibmJmIjoxNzQ4NDI1NTg4LCJjc3JmIjoiZTVmOGJlNzgtZTQ0Ny00NjRlLWI5M2EtODljNzY5YmE1MDZkIiwiZXhwIjoxNzQ4NTExOTg4fQ.OZ94UxHkpdiNORfT3EB5sCqeKQi0oH_qgvmec6NJ_9M",
    project_type_id: projectTypeId,
    project_type_name: projectTypeName,
    tasks: tasks
  };

  // Add department data
  for (let i = 1; i <= 5; i++) {
    requestData[`dept${i}_id`] = parseInt($(`select[name="dept${i}_id"]`).val()) || null;
    requestData[`dept${i}_assigned_days`] = parseInt($(`input[name="dept${i}_assigned_days"]`).val()) || null;
  }

  // Make API call to update template
  $.ajax({
    url: '<?= API_URL ?>update-template',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(requestData),
    success: function(response) {
      if (response.is_successful === "1") {
        $('#successText').text(response.success_message);
        $('#successMessage').addClass('show').show();
        setTimeout(function() {
          window.location.href = 'project-template-list.php';
        }, 2000);
      } else {
        alert('Failed to update project template: ' + (response.errors || 'Unknown error'));
      }
    },
    error: function(xhr, status, error) {
      alert('Error updating project template: ' + error);
    }
  });

  return false;
}
</script>

<?php include 'common/footer.php'; ?>
