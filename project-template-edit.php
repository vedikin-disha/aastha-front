<?php
include 'config/constant.php';
include 'common/header.php';
?>

<!-- Debug Info -->
<?php
echo '<!-- Debug: API_URL = ' . API_URL . ' -->';
echo '<!-- Debug: Session token exists = ' . (isset($_SESSION['access_token']) ? 'yes' : 'no') . ' -->';
?>

<script>
const API_URL = '<?php echo API_URL; ?>';
const ACCESS_TOKEN = '<?php echo $_SESSION['access_token']; ?>';
</script>

<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/select2-bootstrap4.min.css" rel="stylesheet" />
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<!-- Common JS with showToast function -->
<script src="js/common.js"></script>

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

  .select2-container--bootstrap4 .select2-results__option--highlighted, .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
    background-color: #30b8b9 !important;
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

<div class="card card-primary">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);background-color: transparent;color: #212529;">
    <h3 class="card-title">Edit Project Template</h3>
  </div>
  <div class="card-body">
    <div id="successMessage" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
      <span id="successText"></span>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
    </div>

    <form method="post" id="templateForm" onsubmit="return handleSubmit(event)" novalidate>
      

      <!-- Project Type -->
      <div class="form-group">
        <label>Project Type <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-project-diagram"></i></span>
          <input type="text" name="type_name" class="form-control" placeholder="Enter project type name">
        </div>
        <div class="invalid-feedback" style="display: none;"></div>
      </div>

      <!-- Department 1 -->
      <div class="card mt-4">
        <div class="card-header"><h5>Department 1</h5></div>
        <div class="card-body">
          <div class="form-group">
            <label>Department Name<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-building"></i></span>
              <select name="dept1_id" class="form-control select2" required>
                <option value="">Select Department</option>
                <option value="1" selected>Admin</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>

          <div class="form-group">
        <label>Assigned Days <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
          <input type="number" min="1" name="dept1_assigned_days" class="form-control" placeholder="Enter assigned days">
        </div>
        <div class="invalid-feedback" style="display: none;"></div>
      </div>
      <div class="form-group">
        <label>Tasks</label>
        <div class="task-container" id="taskContainer1">
          <div class="task-item input-group">
            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
            <input type="text" name="dept1_tasks[]" class="form-control" placeholder="Enter task">
            <div class="input-group-append">
              <button type="button" class="btn btn-danger remove-task" data-task-id="1"><i class="fas fa-minus"></i></button>
            </div>
          </div>
          <div class="task-item input-group">
            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
            <input type="text" name="dept1_tasks[]" class="form-control" placeholder="Enter task">
            <div class="input-group-append">
              <button type="button" class="btn btn-success add-task" data-dept-id="1" data-task-id="1"><i class="fas fa-plus"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

      <!-- Department 2 -->
      <div class="card mt-4">
        <div class="card-header"><h5>Department 2</h5></div>
        <div class="card-body">
          <div class="form-group">
            <label>Department Name<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-building"></i></span>
              <select name="dept2_id" class="form-control select2">
                <option value="">Select Department</option>
                <option value="2" selected>Drafting</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>

          <div class="form-group">
            <label>Assigned Days <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
              <input type="number" min="1" name="dept2_assigned_days" class="form-control" placeholder="Enter assigned days">
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>
          
          <div class="form-group">
            <label>Tasks</label>
            <div class="task-container" id="taskContainer2">
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept2_task[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept2_task[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-success add-task" data-dept-id="2"><i class="fas fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Department 3 -->
      <div class="card mt-4">
        <div class="card-header"><h5>Department 3</h5></div>
        <div class="card-body">
          <div class="form-group">
            <label>Department Name<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-building"></i></span>
              <select name="dept3_id" class="form-control select2">
                <option value="">Select Department</option>
                <option value="3" selected>Planning</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>

          <div class="form-group">
            <label>Assigned Days <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
              <input type="number" min="1" name="dept3_assigned_days" class="form-control" placeholder="Enter assigned days">
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>
          
          <div class="form-group">
            <label>Tasks</label>
            <div class="task-container" id="taskContainer3">
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept3_tasks[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept3_tasks[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-success add-task" data-dept-id="3"><i class="fas fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Department 4 -->
      <div class="card mt-4">
        <div class="card-header"><h5>Department 4</h5></div>
        <div class="card-body">
          <div class="form-group">
            <label>Department Name<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-building"></i></span>
              <select name="dept4_id" class="form-control select2">
                <option value="">Select Department</option>
                <option value="4" selected>Estimation</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>

          <div class="form-group">
            <label>Assigned Days <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
              <input type="number" min="1" name="dept4_assigned_days" class="form-control" placeholder="Enter assigned days">
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>
          
          <div class="form-group">
            <label>Tasks</label>
            <div class="task-container" id="taskContainer4">
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept4_tasks[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept4_tasks[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-success add-task" data-dept-id="4"><i class="fas fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Department 5 -->
      <div class="card mt-4">
        <div class="card-header"><h5>Department 5</h5></div>
        <div class="card-body">
          <div class="form-group">
            <label>Department Name<span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-building"></i></span>
              <select name="dept5_id" class="form-control select2">
                <option value="">Select Department</option>
                <option value="5" selected>Legal</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>

          <div class="form-group">
            <label>Assigned Days <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
              <input type="number" min="1" name="dept5_assigned_days" class="form-control" placeholder="Enter assigned days">
            </div>
            <div class="invalid-feedback" style="display: none;"></div>
          </div>
          
          <div class="form-group">
            <label>Tasks</label>
            <div class="task-container" id="taskContainer5">
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept5_tasks[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="task-item input-group">
                <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                <input type="text" name="dept5_tasks[]" class="form-control" placeholder="Enter task">
                <div class="input-group-append">
                  <button type="button" class="btn btn-success add-task" data-dept-id="5"><i class="fas fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Submit Buttons -->
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border: 1px solid #30b8b9;"><i class="fas fa-save"></i> Save Changes</button>
        <a href="project-template-list" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
  // Get project_type_id from URL parameter and decode it from base64
  const urlParams = new URLSearchParams(window.location.search);
  const encodedId = urlParams.get('id');
  const projectTypeId = encodedId ? atob(encodedId) : null;
  
  if (!projectTypeId) {
    showToast('Invalid project template ID', false);
    setTimeout(() => { window.location.href = 'project-template-list'; }, 2000);
    return;
  }

  // Fetch departments from API
  $.ajax({
    url: API_URL + 'department',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      access_token: ACCESS_TOKEN
    }),
    success: function(response) {
      if (response.is_successful === '1' && response.data) {
        // Sort departments alphabetically
        response.data.sort((a, b) => a.dept_name.localeCompare(b.dept_name));
        
        // Populate all department dropdowns
        const departments = response.data;
        const options = departments.map(dept => 
          `<option value="${dept.dept_id}">${dept.dept_name}</option>`
        ).join('');
        
        // Add default option and update all department selects
        const defaultOption = '<option value="">Select Department</option>';
        $('select[name^="dept"]').html(defaultOption + options);
        
        // Initialize Select2 after populating options
        $('.select2').select2({
          theme: 'bootstrap4',
          placeholder: 'Select Department',
          allowClear: true
        });

        // Now fetch template data after departments are loaded
        fetchTemplateData();
      } else {
        showToast('Failed to load departments: ' + (response.message || 'Unknown error'), false);
      }
    },
    error: function(xhr, status, error) {
      showToast('Failed to load departments: ' + error, false);
    }
  });

  function fetchTemplateData() {
    // Make API call to fetch project template data
    $.ajax({
    url: '<?= API_URL ?>template-edit',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      "access_token": ACCESS_TOKEN,
      "project_type_id": projectTypeId
    }),
    success: function(response) {
      if (response.is_successful === "1") {
        const data = response.data;
        
        // Populate form fields
        $('input[name="type_name"]').val(data.project_type_name);
        
        // Populate department data
        for (let i = 1; i <= 5; i++) {
          const deptId = data[`dept${i}_id`];
          const assignedDays = data[`dept${i}_assigned_days`];
          
          if (deptId) {
            $(`select[name="dept${i}_id"]`).val(deptId).trigger('change');
          }
          
          if (assignedDays) {
            $(`input[name="dept${i}_assigned_days"]`).val(assignedDays);
          }
        }

        // Clear all task fields first
        for (let i = 1; i <= 5; i++) {
          const taskContainer = $(`#taskContainer${i}`);
          taskContainer.find('.task-item:not(:last)').remove();
          taskContainer.find('input[type="text"]').val('');
        }

        // Group tasks by department
        const tasksByDept = {};
        if (data.tasks && data.tasks.length > 0) {
          data.tasks.forEach(task => {
            if (!tasksByDept[task.dept_id]) {
              tasksByDept[task.dept_id] = [];
            }
            tasksByDept[task.dept_id].push(task.task_name);
          });
        }

        // First, clear all task containers
        $('.task-container').each(function() {
          $(this).empty();
        });

        // Then populate tasks for each department
        for (let i = 1; i <= 5; i++) {
          const deptId = data[`dept${i}_id`];
          if (deptId && tasksByDept[deptId]) {
            const taskContainer = $(`#taskContainer${i}`);
            const tasks = tasksByDept[deptId];
            
            tasks.forEach((taskName, index) => {
              const isLast = index === tasks.length - 1;
              const taskItem = `
                <div class="task-item input-group mb-2">
                  <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                  <input type="text" name="dept${i}_tasks[]" class="form-control" value="${taskName}" placeholder="Enter task">
                  <div class="input-group-append">
                    ${isLast ? 
                      '<button type="button" class="btn btn-success add-task" data-dept-id="' + i + '"><i class="fas fa-plus"></i></button>' : 
                      '<button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>'}
                  </div>
                </div>
              `;
              taskContainer.append(taskItem);
            });
          }
        }
        
        // If no tasks found, ensure each department has at least one task input
        $('.task-container:empty').each(function() {
          const deptNum = $(this).attr('id').replace('taskContainer', '');
          const taskItem = `
            <div class="task-item input-group mb-2">
              <span class="input-group-text"><i class="fas fa-tasks"></i></span>
              <input type="text" name="dept${deptNum}_tasks[]" class="form-control" placeholder="Enter task">
              <div class="input-group-append">
                <button type="button" class="btn btn-success add-task" data-dept-id="${deptNum}"><i class="fas fa-plus"></i></button>
              </div>
            </div>
          `;
          $(this).append(taskItem);
        });
      } else {
        showToast('Failed to load project template data: ' + response.errors, false);
      }
    },
    error: function(xhr, status, error) {
      showToast('Error loading project template data: ' + error, false);
    }
    });
  }

  $('.select2').select2({
    theme: 'bootstrap4',
    placeholder: 'Select Department',
    allowClear: true
  });

  // Add task field
  $(document).on('click', '.add-task', function() {
    const deptId = $(this).data('dept-id') || $(this).closest('.task-container').attr('id').replace('taskContainer', '');
    const taskContainer = $(this).closest('.task-container');
    const taskItem = $(this).closest('.task-item');
    
    // Determine if we're using 'task' or 'tasks' in the input name
    const inputName = taskContainer.find('input[type="text"]').first().attr('name') || '';
    const taskName = inputName.includes('_task') ? 'task' : 'tasks';
    
    // Create new task item
    const newTask = `
      <div class="task-item input-group mb-2">
        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
        <input type="text" name="dept${deptId}_${taskName}[]" class="form-control" placeholder="Enter task">
        <div class="input-group-append">
          <button type="button" class="btn btn-success add-task" data-dept-id="${deptId}"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    `;
    
    // Change current add button to remove button
    taskItem.find('.input-group-append').html('<button type="button" class="btn btn-danger remove-task"><i class="fas fa-minus"></i></button>');
    
    // Add new task item with add button
    taskContainer.append(newTask);
  });

  // Remove task field
  $(document).on('click', '.remove-task', function() {
    const taskItem = $(this).closest('.task-item');
    const container = taskItem.closest('.task-container');
    const taskItems = container.find('.task-item');

    if (taskItems.length > 1) {
      // If this is the last task item, update the previous one to have an add button
      if (!taskItem.next('.task-item').length && taskItem.prev('.task-item').length) {
        const deptId = container.attr('id').replace('taskContainer', '');
        taskItem.prev('.task-item').find('.input-group-append').html(
          `<button type="button" class="btn btn-success add-task" data-dept-id="${deptId}"><i class="fas fa-plus"></i></button>`
        );
      }
      taskItem.remove();
    } else {
      // If this is the last task, just clear the input
      taskItem.find('input').val('');
    }
  });

  $('#project-template a').addClass('active nav-link');
});
</script>

<script>
function handleSubmit(event) {
  event.preventDefault();
  
  const urlParams = new URLSearchParams(window.location.search);
  const encodedId = urlParams.get('id');
  const projectTypeId = encodedId ? atob(encodedId) : null;
  const projectTypeName = $('input[name="type_name"]').val();
  
  // Validate project type name
  if (!projectTypeName) {
    showToast('Please enter Project Type name', false);
    return false;
  }

  // Validate Department 1 (required)
  const dept1Id = $('select[name="dept1_id"]').val();
  const dept1Days = $('input[name="dept1_assigned_days"]').val();

  if (!dept1Id) {
    showToast('Please select Department 1', false);
    return false;
  }

  if (!dept1Days) {
    showToast('Please enter Assigned Days for Department 1', false);
    return false;
  }

  if (parseInt(dept1Days) <= 0) {
    showToast('Assigned Days for Department 1 must be greater than 0', false);
    return false;
  }

  // Validate other departments only if they are filled (optional)
  // for (let i = 2; i <= 5; i++) {
  //   const deptId = $(`select[name="dept${i}_id"]`).val();
  //   const assignedDays = $(`input[name="dept${i}_assigned_days"]`).val();

  //   // Only validate if either field is filled
  //   if (deptId || assignedDays) {
  //     // Check if one field is filled but not the other
  //     if (!deptId) {
  //       showToast(`Please select Department ${i}`, false);
  //       return false;
  //     } else if (!assignedDays) {
  //       showToast(`Please enter Assigned Days for Department ${i}`, false);
  //       return false;
  //     } else if (parseInt(assignedDays) <= 0) {
  //       showToast(`Assigned Days for Department ${i} must be greater than 0`, false);
  //       return false;
  //     }
  //   }
  // }

  // Prepare tasks array
  let tasks = [];
  $('.task-container').each(function(index) {
    // Get department ID from the select element in the same card
    const card = $(this).closest('.card');
    const deptId = card.find('select[name^="dept"]').val();
    
    $(this).find('input[type="text"]').each(function() {
      const taskName = $(this).val().trim();
      if (taskName && deptId) {
        tasks.push({
          task_name: taskName,
          dept_id: deptId
        });
      }
    });
  });

  // Prepare request data
  const requestData = {
    access_token: ACCESS_TOKEN,
    project_type_id: projectTypeId,
    project_type_name: projectTypeName,
    tasks: tasks
  };

  // Add department data
  for (let i = 1; i <= 5; i++) {
    const deptId = parseInt($(`select[name="dept${i}_id"]`).val());
    const assignedDays = parseInt($(`input[name="dept${i}_assigned_days"]`).val());
    
    // Only add department data if both values are present, or if it's department 1 (which is required)
    if (i === 1 || (deptId && assignedDays)) {
      requestData[`dept${i}_id`] = deptId;
      requestData[`dept${i}_assigned_days`] = assignedDays;
    }
    // If values are not present, the fields will be omitted from the payload
  }

  // Make API call to update template
  $.ajax({
    url: '<?= API_URL ?>update-template',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(requestData),
    success: function(response) {
      if (response.is_successful === "1") {
        showToast('Project template updated successfully', true);
        setTimeout(function() {
          window.location.href = 'project-template-list';
        }, 2000);
      } else {
        showToast('Failed to update project template: ' + (response.errors || 'Unknown error'), false);
      }
    },
    error: function(xhr, status, error) {
      showToast('Error updating project template: ' + error, false);
    }
  });

  return false;
}
</script>

<?php include 'common/footer.php'; ?>
