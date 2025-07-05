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
?>

<!-- Add a loading indicator -->
<div id="loading" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
        <!-- <span class="visually-hidden">Loading...</span> -->
    </div>
    <p>Loading task details...</p>
</div>

<!-- Container for task details (initially hidden) -->
<div id="taskDetails" style="display: none;">
    <!-- Task details will be inserted here by JavaScript -->
</div>

<!-- Error message container (initially hidden) -->
<div id="errorMessage" class="alert alert-danger" style="display: none;"></div>

<script>
$(document).ready(function() {
    const taskId = "<?php echo $task_id; ?>";
    const accessToken = "<?php echo $_SESSION['access_token']; ?>";
    const apiUrl = "<?php echo API_URL; ?>project-task-edit";
    let taskData = null;

    $('#loading').show();

    // Fetch Task Details
    $.ajax({
        url: apiUrl,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: accessToken,
            task_id: taskId
        }),
        success: function(response) {
            $('#loading').hide();
            if (response.is_successful === '1' && response.data) {
                taskData = response.data;
                displayTaskDetails(taskData);
            } else {
                showError(response.errors || 'Failed to load task details');
            }
        },
        error: function(xhr) {
            $('#loading').hide();
            let errorMessage = 'Error fetching task details';
            if (xhr.responseJSON?.errors) {
                errorMessage = xhr.responseJSON.errors;
            }
            showError(errorMessage);
        }
    });

    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function getPriorityBadge(priority) {
        if (!priority) return '';
        const classMap = { high: 'danger', regular: 'info', low: 'success' };
        const cls = classMap[priority.toLowerCase()] || 'secondary';
        return `<span class="badge bg-${cls}">${priority.charAt(0).toUpperCase() + priority.slice(1)}</span>`;
    }

    function displayTaskDetails(task) {
        const html = `
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title" style="margin: 16px!important;">${task.task_name || 'Task Details'}</h3>
                            <div class="btn-group">
                                <button class="btn btn-primary btn-sm start-task-btn rounded ml-2" ${task.task_status == 2 || task.task_status == 1 ? 'disabled' : ''}>
                                    <i class="fas fa-play"> </i> Start
                                </button>
                                <button class="btn btn-success btn-sm mark-done-btn rounded ml-2" ${task.task_status == 2 ? 'disabled' : ''}>
                                    <i class="fas fa-check"> </i> Complete
                                </button>
                                <a href="project-task-list.php" class="btn btn-default btn-sm ml-2" style="background-color: #6c757d; color: #fff;">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                           
                                <h5 class="font-weight-bold">Task Details</h5>
                                <hr>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Project:</div><div class="col-sm-8">
                                 <a href="javascript:void(0);" onclick="window.open('view-project.php?id=' + btoa(${task.project_id}), '_blank')">
                                    ${task.project_name || 'N/A'}
                                </a>
                                </div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Department:</div><div class="col-sm-8">${task.dept_name || 'N/A'}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Task Name:</div><div class="col-sm-8">${task.task_name || 'N/A'}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Assigned To:</div><div class="col-sm-8">${task.assigned_emp_name || 'Not assigned'}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Priority:</div><div class="col-sm-8">${getPriorityBadge(task.task_priority)}</div></div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="font-weight-bold">Timeline</h5>
                                <hr>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Assigned Start Date:</div><div class="col-sm-8">${(task.assigned_start_date || '')}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Assigned End Date:</div><div class="col-sm-8">${(task.assigned_end_date || '')}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Actual Start Date:</div><div class="col-sm-8">${(task.start_date || '')}</div></div>
                                <div class="row mb-3"><div class="col-sm-4 font-weight-bold">Actual End Date:</div><div class="col-sm-8">${(task.end_date || '')}</div></div>
                            </div>
                        </div>
                        ${task.description ? `
                            <div class="mt-4">
                                <h5 class="font-weight-bold">Description</h5>
                                <hr>
                                <p>${task.description}</p>
                            </div>` : ''
                        }
                        
                    </div>
                </div>
            </div>
        `;
        $('#taskDetails').html(html).fadeIn();
    }

    function showError(message) {
        $('#errorMessage').text(message).fadeIn();
    }

    // Update Task Status to Completed
    function updateTaskStatus(newStatus, btn) {
    if (!taskData) {
        showToast("Task data not loaded yet.", false);
        return;
    }

    btn.prop('disabled', true);

    const updateData = {
        access_token: accessToken,
        task_id: taskData.task_id,
        task_name: taskData.task_name,
        assigned_emp_id: taskData.assigned_emp_id,
        dept_id: taskData.dept_id,
        task_status: newStatus,
        project_id: taskData.project_id
    };

    $.ajax({
        url: "<?php echo API_URL ?>project-task-update",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(updateData),
        success: function (res) {
            if (res.is_successful === "1") {
                showToast("Task status updated.");
                location.reload();
            } else {
                showToast(res.errors || "Failed to update.", false);
                btn.prop('disabled', false);
            }
        },
        error: function () {
            showToast("Error updating task.", false);
            btn.prop('disabled', false);
        }
    });
}

// Play Button = Start Task = Status 1
$(document).on('click', '.start-task-btn', function () {
    updateTaskStatus(1, $(this));
});

// Mark as Done = Complete = Status 2
$(document).on('click', '.mark-done-btn', function () {
    updateTaskStatus(2, $(this));
});


    function showToast(message, success = true) {
        const type = success ? 'success' : 'danger';
        const alertBox = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>`;
        $('#errorMessage').html(alertBox).fadeIn();
    }
});
</script>

<?php include 'common/footer.php'; ?>