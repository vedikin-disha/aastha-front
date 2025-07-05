<?php include 'common/header.php'; ?>
<!-- Add DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
<!-- Add Bootstrap Switch CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/css/bootstrap3/bootstrap-switch.min.css">

<!-- Add jQuery and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<!-- Buttons HTML5 -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<!-- Buttons ColVis -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>
<!-- Add Bootstrap Switch JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/js/bootstrap-switch.min.js"></script>
<style>
    /* Custom styling for the task done switch */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745 !important;
        /* border-color:black !important; */
    }

    .dropdown-item.active {
        background-color: #30b8b9 !important;
    }

    .dropdown-item:active {
        background-color: #30b8b9 !important;
    }
    .new-pms-ap {
      width: 100% !important;
      overflow-x: auto !important;
      overflow-y: hidden !important;
    }
   
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Project Tasks</h3>

                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
                    <div class="card-tools">
                    <a href="add-project-task" class="btn btn-success" ><i class="fas fa-plus"></i> Add New Task</a>
                    </div>
                        <?php endif; ?>
                
            </div>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <form method="get" class="mb-4">
               
            </form>
            <div class="new-pms-ap">
                <table id="taskTable" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th style="width: 5%;"></th>
                            <th class="sorting">Project Name</th>
                            <th>Total Tasks</th>
                            <th>Completed Tasks</th>
                            <th>Pending Tasks</th>
                        </tr>
                    </thead>    
                <tbody>

                </tbody>
            </table>
        </div>
        
        <!-- DataTables handles pagination now -->
    </div>
</div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this task?
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger btn-sm" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>


<style>
    /* Style for expand/collapse button */
    .expand-button {
        cursor: pointer;
        display: inline-block;
        width: 16px;
        text-align: center;
        transition: transform 0.2s;
    }
    
    .expand-button.expanded {
        transform: rotate(45deg);
    }
    
    /* Style for the child row */
    .child-row {
        padding: 10px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    
    /* Custom styling for DataTables */
    .dataTables_filter {
        text-align: right;
        float: right;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    
    .dataTables_length {
        padding-top: 0.5rem;
        float: left;
    }
    
    .dataTables_filter input {
        margin-left: 0.5rem;
        display: inline-block;
        width: auto;
    }
    
    /* Clear floats */
    .dataTables_wrapper .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete task: <span id="taskNameToDelete"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with server-side processing
    var table = $('#taskTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo API_URL; ?>project-task-listing',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: function(d) {
                return JSON.stringify({
                    access_token: '<?php echo $_SESSION["access_token"]; ?>',
                    page: Math.ceil((d.start / d.length) + 1), // Calculate page number
                    limit: d.length,
                    search: d.search.value,
                    order_by: 'project_name',
                    order_dir: d.order[0]?.dir || 'asc'
                });
            },
            dataSrc: function(json) {
                // Update DataTables with the total records count
                if (json && json.data) {
                    // Store the total count for pagination
                    json.recordsTotal = json.data.total || 0;
                    json.recordsFiltered = json.data.total || 0;
                    
                    // Return the projects array
                    if (json.data.projects && Array.isArray(json.data.projects)) {
                        return json.data.projects.map(project => ({
                            project_id: project.project_id,
                            project_name: project.project_name || 'Unnamed Project',
                            priority: project.priority || 'Regular',
                            total_tasks: project.total_tasks || 0,
                            completed_tasks: project.completed_task || 0,
                            pending_tasks: project.pending_task || 0,
                            ongoing_tasks: project.ongoing_task || 0,
                            overdue_tasks: project.overdue_task || 0,
                            tasks: project.tasks || []
                        }));
                    }
                }
                return [];
            }
        },
        columns: [
            {
                data: null,
                className: 'expand-control',
                orderable: false,
                defaultContent: '<span class="expand-button">+</span>',
                width: '5%'
            },
            { 
                data: 'project_name',
                render: function(data, type, row) {
                    let priorityBadge = '';
                    if (row.priority && row.priority.toLowerCase() === 'high') {
                        priorityBadge = '<span class="badge bg-danger">!</span>';
                    }
                    return priorityBadge + '    ' + (data || 'Unnamed Project');
                }
            },
            { 
                data: 'total_tasks',
                className: 'text-center'
            },
            { 
                data: 'completed_tasks',
                className: 'text-center',
                render: function(data) {
                    return `<span class="badge bg-success">${data || 0}</span>`;
                }
            },
            { 
                data: 'pending_tasks',
                className: 'text-center',
                render: function(data) {
                    return `<span class="badge bg-warning">${data || 0}</span>`;
                }
            }
        ],
        order: [[1, 'asc']], // Default sort by project name
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading....',
            emptyTable: 'No projects found',
            info: 'Showing _START_ to _END_ of _TOTAL_ projects',
            infoEmpty: 'No projects available',
            infoFiltered: '(filtered from _MAX_ total projects)',
            lengthMenu: 'Show _MENU_ projects',
            search: 'Search:',
            paginate: {
                first: 'First',
                last: 'Last',
                next: 'Next',
                previous: 'Previous'
            }
        }
    });

    // Track loading states for expandable rows
    const loadingStates = new Map();

    // Handle expand/collapse of project rows
    $('#taskTable tbody').on('click', 'td.expand-control', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        const rowData = row.data();
        const rowId = rowData.project_id;
        const expandButton = $(this).find('.expand-button');

        // Prevent multiple clicks while loading
        if (loadingStates.get(rowId)) return;
        
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            expandButton.removeClass('expanded');
            return;
        }

        // Show loading state
        loadingStates.set(rowId, true);
        expandButton.addClass('loading');
        const loadingRow = $('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading tasks...</div>');
        row.child(loadingRow).show();

        // Fetch task details
        const tasks = rowData.tasks || [];

loadingStates.set(rowId, false);
expandButton.removeClass('loading').addClass('expanded');

if (tasks.length > 0) {
    let taskList = `
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Task</th>
                            <th>Assigned To</th>
                            <th>Assigned Duration</th>
                            <th>Completed Duration</th>
                            <th>Task Statu</th>
                            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                            <th>Department</th>
                            <?php endif; ?>
                            <th>Actions</th>
                            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
                            <th>  </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>`;

    function formatDate(dateStr) {
        if (!dateStr) return '';
        
        try {
            // Handle case where dateStr is already a Date object
            const date = new Date(dateStr);
            
            // If date is invalid, try to parse as string
            if (isNaN(date.getTime())) {
                // Try different date formats
                const parts = String(dateStr).split(/[-/]/);
                if (parts.length === 3) {
                    const [day, month, year] = parts;
                    if (day && month && year) {
                        return `${String(day).padStart(2, '0')}/${String(month).padStart(2, '0')}/${year}`;
                    }
                }
                return ''; // Return empty string if can't parse
            }
            
            // Format the date as DD/MM/YYYY
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        } catch (e) {
            console.error('Error formatting date:', e);
            return '';
        }
    }

    tasks.forEach(task => {
        const assignedDuration = task.assigned_start_date && task.assigned_end_date
            ? `${formatDate(task.assigned_start_date)} to ${formatDate(task.assigned_end_date)}`
            : '';

        const completedDuration = task.completed_duration
            ? formatDate(task.completed_duration)
            : '';

        taskList += `
            <tr data-task-id="${task.task_id}"
                data-project-id="${rowData.project_id || ''}"
                data-assigned-emp="${task.assigned_emp_id || ''}"
                data-start-date="${task.assigned_start_date || ''}"
                data-end-date="${task.assigned_end_date || ''}"
                data-dept-id="${task.dept_id || ''}">
                <td class="task-name">
                    ${task.task_priority?.toLowerCase() === 'high' ? '<span class="badge bg-danger me-1">!</span>' : ''}
                    ${task.task_name || ''}
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        ${task.assigned_emp_profile
                            ? `<img src="${task.assigned_emp_profile}" class="rounded-circle mr-2" width="32" height="32" alt="${task.assigned_emp_name || 'User'}" />`
                            : ''}
                        <span>${task.assigned_emp_name || ''}</span>
                    </div>
                </td>
                <td align="center">${task.assigned_duration}</td>
                <td align="center">${task.completed_duration}</td>
                <td>${task.task_status || ''}</td>
                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                <td>${task.dept_name || ''}</td>
                <?php endif; ?>
                
               
                <td>
                    <div class="btn-group btn-group-sm" style="gap: 5px;">
                     <button class="btn btn-primary btn-sm start-task-btn rounded" data-task-id="${task.task_id}" ${task.task_status_value === 1 || task.task_status_value === 2 ? 'disabled' : ''}>
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn btn-success btn-sm mark-done-btn rounded" data-task-id="${task.task_id}" ${task.task_status_value === 2 || task.task_status_value === 0 ? 'disabled' : ''}>
                            <i class="fas fa-check"></i>
                        </button>
                       
                        <button class="btn btn-info btn-sm view-task-btn rounded" data-task-id="${task.task_id}" onclick="window.location.href='project-task-view?id=' + btoa(${task.task_id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
                
                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
                <td>
                    <div class="btn-group btn-group-sm" style="gap: 5px;">
                        <a href="edit-project-task?id=${btoa(task.task_id)}" class="btn btn-info btn-sm rounded">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-warning btn-sm text-white reminder-btn rounded" data-task-id="${task.task_id}">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-task-btn rounded" data-task-id="${task.task_id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
                <?php endif; ?>
            </tr>`;
    });

    taskList += `</tbody></table></div></div>`;
    row.child($(taskList)).show();

} else {
    row.child('<div class="text-center p-3">No tasks found for this project.</div>').show();
}

    });

    $(document).on('click', '.start-task-btn', function () {
    const button = $(this);
    const taskId = button.data('task-id');

    const row = button.closest('tr');
    const taskName = row.find('.task-name').text().trim();
    const projectId = row.data('project-id');
    const assignedEmpId = row.data('assigned-emp');
    const formatDate = (dateString) => {
        if (!dateString) return '';
        // If date is already in YYYY-MM-DD format, return as is
        if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) return dateString;
        // Try to parse and format other date formats if needed
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return ''; // Invalid date
        return date.toISOString().split('T')[0]; // Returns YYYY-MM-DD
    };
    
    const startDate = formatDate(row.data('start-date'));
    const endDate = formatDate(row.data('end-date'));
    const deptId = row.data('dept-id');

    const requestData = {
        access_token: "<?php echo $_SESSION['access_token']; ?>", // Replace or retrieve dynamically
        task_id: taskId,
        task_name: taskName,
        assigned_emp_id: assignedEmpId,
        // start_date: startDate,
        // end_date: endDate,
        dept_id: deptId,
        task_status: 1, // 1 = In Progress
        project_id: projectId
    };

    $.ajax({
        url: "<?php echo API_URL ?>project-task-update", // Replace with your actual API URL    
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(requestData),
        success: function (response) {
            if (response.is_successful === "1") {
                showToast(response.success_message);
                // location.reload();
                button.prop('disabled', true); // disable the button
                // Optionally refresh the row or task status
            } else {
                showToast(response.errors.error , false);
            }
        },
        error: function () {
            showToast("Failed to update task.", false);
        }
    });
});

    // Helper function to format task duration
    function formatTaskDuration(startDate, endDate) {
        if (!startDate) return 'Not started';
        const start = new Date(startDate);
        const end = endDate ? new Date(endDate) : new Date();
        return `${start.toLocaleDateString()} - ${endDate ? end.toLocaleDateString() : 'Present'}`;
    }

    // Helper function to get status badge
    function getStatusBadge(status) {
        const statusMap = {
            'To Do': 'secondary',
            'In Progress': 'primary',
            'Completed': 'success',
            'Overdue': 'danger'
        };
        const statusClass = statusMap[status] || 'secondary';
        return `<span class="badge bg-${statusClass}">${status || ''}</span>`;
    }

    $(document).on('click', '.mark-done-btn', function () {
    const button = $(this);
    const taskId = button.data('task-id');

    // Find task details from DOM
    const row = button.closest('tr');
    const taskName = row.find('.task-name').text().trim();
    
    // Get values and ensure proper types
    const projectId = parseInt(row.data('project-id') || 0);
    const assignedEmpId = parseInt(row.data('assigned-emp') || 0);
    
    // Format dates to YYYY-MM-DD
    const formatDate = (dateString) => {
        if (!dateString) return '';
        // If date is already in YYYY-MM-DD format, return as is
        if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) return dateString;
        // Try to parse and format other date formats if needed
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return ''; // Invalid date
        return date.toISOString().split('T')[0]; // Returns YYYY-MM-DD
    };
    
    const startDate = formatDate(row.data('start-date'));
    const endDate = formatDate(row.data('end-date'));
    const deptId = parseInt(row.data('dept-id') || 0);

    // Construct request body with proper types
    const requestData = {
        access_token: "<?php echo $_SESSION["access_token"]; ?>",
        task_id: parseInt(taskId) || 0,
        task_name: taskName,
        assigned_emp_id: assignedEmpId,
        // start_date: startDate,
        // end_date: endDate,
        dept_id: deptId,
        task_status: 2, // 'Done'
        project_id: projectId
    };

    $.ajax({
        url: "<?php echo API_URL; ?>project-task-update", // replace with actual endpoint or full path
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(requestData),
        success: function (response) {
            if (response.is_successful === "1") {
                    showToast(response.success_message);
                // location.reload();
                button.prop('disabled', true); // disable Done button
                // Optionally update task status text or reload table
            } else {
                showError(response.errors);
            }
        },
        error: function () {
            showError("Error calling API.");
        }
    });
});
});
</script>

<script>
var taskIdToDelete = null;
var taskNameToDelete = null;

// Update the mark-done-btn click handler



// Show delete confirmation modal
$('#taskTable').on('click', '.delete-task', function() {
    taskIdToDelete = $(this).data('task-id');
    taskNameToDelete = $(this).data('task-name');
    $('#taskNameToDelete').text(taskNameToDelete);
    $('#deleteModal').modal('show');
});

// Handle confirm delete
// let taskIdToDelete = null;

// When delete button is clicked
$(document).on('click', '.delete-task-btn', function () {
    taskIdToDelete = $(this).data('task-id');
    $('#deleteModal').modal('show');
});

// When confirm delete is clicked
    // Handle reminder button click
    $(document).on('click', '.reminder-btn', function() {
        const button = $(this);
        const taskId = button.data('task-id');
        
        // Show loading state
        const originalHtml = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Sending...').prop('disabled', true);
        
        // Call the API to send reminder
        $.ajax({
            url: '<?php echo API_URL; ?>send-task-reminder',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION["access_token"]; ?>',
                task_id: taskId
            }),
            success: function(response) {
                if (response.is_successful === '1') {

                    showToast(response.success_message);
                    // Show success message
                    const toast = $('<div class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">' +
                        '<div class="toast-body">' +
                        '<i class="fas fa-check-circle me-2"></i> Reminder sent successfully!' +
                        '</div></div>');
                    $('.toast-container').append(toast);
                    const bsToast = new bootstrap.Toast(toast[0]);
                    bsToast.show();
                    // location.reload();
                    
                    // Remove toast after it hides
                    toast.on('hidden.bs.toast', function() {
                        $(this).remove();
                    });
                } else {
                    showToast(response.success_message || 'Failed to send reminder');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error sending reminder:', error);
                showToast('Error sending reminder: ' + (xhr.responseJSON?.errors || 'Please try again later'));
            },
            complete: function() {
                // Reset button state
                button.html(originalHtml).prop('disabled', false);
            }
        });
    });

    $('#confirmDelete').click(function () {
        if (!taskIdToDelete) return;

        $.ajax({
        url: '<?php echo API_URL; ?>project-task-delete',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            task_id: taskIdToDelete
        }),
        success: function (response) {
            if (response.is_successful === '1') {
                // Remove task row from table
                const row = $('button[data-task-id="' + taskIdToDelete + '"]').closest('tr');
                $('#taskTable').DataTable().row(row).remove().draw();
                location.reload();
                // Success toast
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: 'Success',
                    body: response.success_message || 'Task deleted successfully',
                    autohide: true,
                    delay: 3000
                });
            } else {
                // Error toast
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: response.errors || 'Error deleting task',
                    autohide: true,
                    delay: 3000
                });
            }
        },
        error: function () {
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: 'Error deleting task. Please try again.',
                autohide: true,
                delay: 3000
            });
        },
        complete: function () {
            $('#deleteModal').modal('hide');
            taskIdToDelete = null;
        }
    });
});
</script>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete task: <span id="taskNameToDelete"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php include 'common/footer.php'; ?>