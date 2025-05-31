<?php

include 'common/header.php'; 

$redirect_to = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare template data
    $template_data = [
        'access_token' => $_SESSION['access_token'],
        'project_type_name' => $_POST['type_name']
    ];

    // Process each department's data
    for ($i = 1; $i <= 5; $i++) {
        $dept_id = $_POST["dept{$i}_id"] ?? '';
        if (!empty($dept_id)) {
            $template_data["dept{$i}_id"] = $dept_id;
            $template_data["dept{$i}_assigned_days"] = $_POST["dept{$i}_assigned_days"] ?? 0;
        }
    }

    // Process tasks
    $tasks = [];
    for ($i = 1; $i <= 5; $i++) {
        $dept_tasks = $_POST["dept{$i}_task"] ?? [];
        $dept_id = $_POST["dept{$i}_id"] ?? '';
        
        if (!empty($dept_id) && !empty($dept_tasks)) {
            foreach ($dept_tasks as $task_name) {
                if (!empty($task_name)) {
                    $tasks[] = [
                        'task_name' => $task_name,
                        'dept_id' => $dept_id
                    ];
                }
            }
        }
    }
    $template_data['tasks'] = $tasks;

    // Send data to API
    $url = API_URL . 'add-template';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($template_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    
    if ($response === false) {
        $error_message = 'cURL Error: ' . curl_error($ch);
    } else {
        $result = json_decode($response, true);
        if (isset($result['is_successful']) && $result['is_successful'] === '1') {
            // Set redirect flag instead of immediate redirect
            $redirect_to = 'project-template-list.php';
        } else {
            $error_message = $result['errors'] ?? 'Failed to add template';
        }
    }
    curl_close($ch);
}

// API call to fetch departments
$url = '<?php echo API_URL; ?>department';
$request_data = [
    'access_token' => $_SESSION['access_token']
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// SSL settings
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$departments = [];

if ($response === false) {
    $error_message = 'cURL Error: ' . curl_error($ch);
} else {    
    $result = json_decode($response, true);
    if (isset($result['is_successful']) && $result['is_successful'] === '1' && !empty($result['data'])) {
        $departments = $result['data'];
    }
}
curl_close($ch);
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
</style>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Add Project Template</h3>
    </div>
    <div class="card-body">

            
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
<form method="post" id="templateForm">
<input type="hidden" name="" value="" />
<?php if (isset($validation_errors)): ?>
    <div class="alert alert-danger">
        <?php echo $validation_errors; ?>
    </div>
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
                required>
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
    <label for="dept<?php echo $i; ?>_id">Department <?php if ($i == 1): ?><span class="text-danger">*</span><?php endif; ?></label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-building"></i></span>
      <select name="dept<?php echo $i; ?>_id" id="dept<?php echo $i; ?>_id" class="form-control select2" data-placeholder="-- Select Department --" <?php if ($i == 1): ?>required<?php endif; ?>>
        <option value=""></option>
        <?php foreach ($departments as $dept): ?>
          <option value="<?php echo $dept['dept_id']; ?>"><?php echo $dept['dept_name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- Assigned Days -->
  <div class="form-group">
    <label for="dept<?php echo $i; ?>_days">Assigned Days <?php if ($i == 1): ?><span class="text-danger">*</span><?php endif; ?></label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
      <input type="number" 
             name="dept<?php echo $i; ?>_assigned_days" 
             id="dept<?php echo $i; ?>_assigned_days" 
             class="form-control" 
             min="0"
             <?php if ($i == 1): ?>required<?php endif; ?>>
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
<button type="submit" class="btn btn-primary">
  <i class="fas fa-save"></i> Save Template
</button>
<a href="project-template-list" class="btn btn-secondary">
  <i class="fas fa-times"></i> Cancel
</a>
</div>
</form>
</div>
</div>



<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

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
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Select Department --',
        allowClear: true
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