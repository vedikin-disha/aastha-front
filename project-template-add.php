<?php
include 'common/header.php'; 

$redirect_to = '';
// Handle form submission
// Form will be handled via AJAX

// Departments will be loaded via AJAX
?>
<style>
  .input-group {
    margin-bottom: 1rem !important;
  }
  .form-control {
    height: 38px;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
  .task-item {
    margin-bottom: 0.5rem;
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
    <div class="card-header" style="background-color:transparent !important;border-bottom: 1px solid rgba(0, 0, 0, .125) !important; border-top: 3px solid #30b8b9 !important; color: #212529 !important;">
        <h3 class="card-title">Add Project Template</h3>
    </div>
    <div class="card-body">
        <form id="templateForm" method="POST" onsubmit="return handleSubmit(event)">
            
<?php if (isset($messages)): ?>
    <?php foreach ($messages as $message): ?>
        <div class="alert <?php echo $message['level_tag'] === 'error' ? 'alert-danger' : 'alert-' . $message['tags']; ?>">
            <?php echo $message['message']; ?>
            <?php if ($message['extra_tags'] === 'email_exists'): ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<form method="post" id="templateForm" onsubmit="return handleSubmit(event)" novalidate>
<input type="hidden" name="" value="" />
<?php if (isset($error_message)): ?>
    <script>
        $(document).ready(function() {
            showToast('<?php echo addslashes($error_message); ?>', false);
        });
    </script>
<?php endif; ?>

<!-- Project Type -->
<div class="form-group">
        <label for="type_name">Project Type <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-project-diagram"></i></span>
            <input type="text" 
                name="type_name" 
                id="type_name" 
                class="form-control" 
                placeholder="Enter project type name"
>
        </div>
    </div>


<!-- Department Sections -->
<?php for ($i = 1; $i <= 5; $i++): ?>
<div class="card mt-4">
<div class="card-header">
  <h3 class="card-title">Department <?php echo $i; ?></h3>
</div>
<div class="card-body">
  <!-- Department Selection -->
  <div class="form-group">
    <label for="dept<?php echo $i; ?>_id">Department <?php if ($i === 1): ?><span class="text-danger">*</span><?php endif; ?></label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-building"></i></span>
      <select name="dept<?php echo $i; ?>_id" id="dept<?php echo $i; ?>_id" class="form-control select2" data-placeholder="-- Select Department --">
        <option value=""></option>
        <?php foreach ($departments as $dept): ?>
          <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['dept_name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Assigned Days -->
  <div class="form-group">
    <label for="dept<?php echo $i; ?>_days">Assigned Days <?php if ($i === 1): ?><span class="text-danger">*</span><?php endif; ?></label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
      <input type="number" 
             name="dept<?php echo $i; ?>_assigned_days" 
             id="dept<?php echo $i; ?>_assigned_days" 
             class="form-control" 
             min="1">
    </div>
  </div>

  <!-- Tasks -->
  <div class="form-group">
    <label>Tasks</label>
    <div class="task-container" id="taskContainer<?php echo $i; ?>">
      <div class="task-item input-group mb-2">
        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
        <input type="text" 
               name="dept<?php echo $i; ?>_task[]" 
               class="form-control"
               placeholder="Enter task description">
        <div class="input-group-append">
          <button type="button" class="btn btn-success add-task">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php endfor; ?>

<!-- Submit Buttons -->
<div class="form-group mt-4">
<button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border:none;">
  <i class="fas fa-save"></i> Save Template
</button>
</form>
<a href="project-template-list.php" class="btn btn-secondary">
  <i class="fas fa-times"></i> Cancel
</a>
</div>
</form>
</div>
</div>



<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<!-- Common JS with showToast function -->
<script src="js/common.js"></script>

<style>
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    border: 1px solid #ced4da;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}
.select2-container--bootstrap-5 .select2-selection--single {
    padding-top: 0.25rem;
}
.input-group > .select2-container {
    flex: 1 1 auto;
    width: 1% !important;
}
.input-group > .select2-container .select2-selection {
    height: 100%;
}
</style>

<script>
// Form validation and submission handling
function handleSubmit(event) {
    event.preventDefault();
    
    // Validate project type name
    const projectTypeName = $('#type_name').val().trim();
    if (!projectTypeName) {
        showToast('Please enter Project Type name', false);
        return false;
    }
    
    // Validate Department 1 (required)
    const dept1Id = $('#dept1_id').val();
    const dept1Days = $('#dept1_assigned_days').val();
    
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
    for (let i = 2; i <= 5; i++) {
        const deptId = $(`#dept${i}_id`).val();
        const assignedDays = $(`#dept${i}_assigned_days`).val();
        
        // Only validate if either field is filled
        if (deptId || assignedDays) {
            // Check if one field is filled but not the other
            if (!deptId) {
                showToast(`Please select Department ${i}`, false);
                return false;
            } else if (!assignedDays) {
                showToast(`Please enter Assigned Days for Department ${i}`, false);
                return false;
            } else if (parseInt(assignedDays) <= 0) {
                showToast(`Assigned Days for Department ${i} must be greater than 0`, false);
                return false;
            }
        }
    }
    
    // Prepare template data
    const templateData = {
        access_token: '<?php echo $_SESSION["access_token"]; ?>',
        project_type_name: projectTypeName
    };

    // Process each department's data
    for (let i = 1; i <= 5; i++) {
        const deptId = $(`#dept${i}_id`).val();
        const assignedDays = $(`#dept${i}_assigned_days`).val();
        
        if (deptId) {
            templateData[`dept${i}_id`] = deptId;
            templateData[`dept${i}_assigned_days`] = assignedDays || 0;
        }
    }

    // Process tasks
    const tasks = [];
    for (let i = 1; i <= 5; i++) {
        const deptId = $(`#dept${i}_id`).val();
        if (deptId) {
            $(`#taskContainer${i} input[type="text"]`).each(function() {
                const taskName = $(this).val().trim();
                if (taskName) {
                    tasks.push({
                        task_name: taskName,
                        dept_id: deptId
                    });
                }
            });
        }
    }
    templateData.tasks = tasks;

    // Send data via AJAX
    $.ajax({
        url: API_URL + 'add-template',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(templateData),
        success: function(response) {
            if (response.is_successful === '1') {
                showToast('Project template added successfully!', true);
                setTimeout(() => {
                    window.location.href = 'project-template-list.php';
                }, 1000);
            } else {
                showToast(response.errors || 'Failed to add template', false);
            }
        },
        error: function() {
            showToast('Failed to add template. Please try again.', false);
        }
    });

    return false;
}

// Define API URL for JavaScript
const API_URL = '<?php echo API_URL; ?>';

$(document).ready(function() {
    // Fetch departments via AJAX
    $.ajax({
        url: API_URL + '/department',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: '<?php echo $_SESSION["access_token"]; ?>'
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
                const defaultOption = '<option value="">-- Select Department --</option>';
                $('select[id^="dept"]').html(defaultOption + options);
                
                // Initialize Select2
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Select Department --',
                    allowClear: true
                });
            } else {
                showToast('Failed to load departments: ' + (response.message || 'Unknown error'), false);
            }
        },
        error: function(xhr, status, error) {
            showToast('Failed to load departments: ' + error, false);
        }
    });

    // Handle adding new task fields
    $('.task-container').on('click', '.add-task', function() {
        var container = $(this).closest('.task-container');
        var firstInput = container.find('input:first');
        var newTask = $('<div class="task-item input-group mb-2">' +
            '<span class="input-group-text"><i class="fas fa-tasks"></i></span>' +
            '<input type="text" name="' + firstInput.attr('name') + '" ' +
            'class="form-control" placeholder="Enter task description">' +
            '<div class="input-group-append">' +
            '<button type="button" class="btn btn-success add-task">' +
            '<i class="fas fa-plus"></i></button>' +
            '</div>' +
            '</div>');
        
        // Insert after the current task item
        $(this).closest('.task-item').after(newTask);
        
        // Replace + button with - button on current item
        var currentItem = $(this).closest('.task-item');
        currentItem.find('.input-group-append').html(
            '<button type="button" class="btn btn-danger remove-task">' +
            '<i class="fas fa-minus"></i></button>'
        );
    });

    // Handle removing task fields
    $('.task-container').on('click', '.remove-task', function() {
        var container = $(this).closest('.task-container');
        var taskItem = $(this).closest('.task-item');
        
        if (container.find('.task-item').length > 1) {
            // If removing the last field, add + button to the previous field
            if (!taskItem.next('.task-item').length && taskItem.prev('.task-item').length) {
                var prevItem = taskItem.prev('.task-item');
                prevItem.find('.input-group-append').html(
                    '<button type="button" class="btn btn-success add-task">' +
                    '<i class="fas fa-plus"></i></button>'
                );
            }
            taskItem.remove();
        }
    });

    // Add active class to navigation
    $('#project-template a').addClass('active nav-link');

});
</script>

<!-- <script>
  $(document).ready(function() {
    // {% for message in messages %}
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Success',
        position: 'bottomRight',
        body: '{{ message|escapejs }}',
        autohide: true,
        delay: 3000
      });
    // {% endfor %}
  });
</script> -->

<?php 
include 'common/footer.php'; 
?>