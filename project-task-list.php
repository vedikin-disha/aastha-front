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
   
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Project Tasks</h3>

                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
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
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete task "<span id="taskNameToDelete"></span>"?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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

<script>
$(document).ready(function() {
    // Check if table exists
    var $table = $('#taskTable');
    if ($table.length === 0) {
        console.error('Table with ID "taskTable" not found in the DOM');
        return;
    }
    
    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable($table)) {
        $table.DataTable().destroy();
    }
    
 // Initialize DataTable with configuration
try {
    var table = $table.DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '<?php echo API_URL; ?>project-task-listing',
            "type": "POST",
            "dataType": "json",
            "contentType": "application/json",
            "data": function(d) {
                return JSON.stringify({
                    access_token: '<?php echo $_SESSION["access_token"]; ?>',
                    page: Math.ceil((d.start / d.length) + 1),
                    limit: d.length,
                    search: d.search.value,
                    order_by: d.order[0].column > 0 ? d.columns[d.order[0].column].data : 'project_name',
                    order_dir: d.order[0].dir
                });
            },
            "dataSrc": function(json) {
                // Flatten the projects array to get project summary
                var data = [];
                if (json.data && json.data.projects) {
                    json.data.projects.forEach(function(project) {
                        data.push({
                            project_id: project.project_id,
                            project_name: project.project_name,
                            priority: project.priority, // Add priority field
                            total_tasks: project.total_tasks || 0,
                            completed_tasks: project.completed_task || 0,
                            pending_tasks: project.pending_task || 0
                        });
                    });
                }
                return data;
            }
        },
        "columns": [
            {
                "className": 'expand-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<span class="expand-button">+</span>',
                "width": '5%'
            },
            { 
                "data": "project_name",
                "title": "Project Name",
                "render": function(data, type, row) {
                    console.log('Project:', data, 'Priority:', row.priority); // Debug log
                    if (row.priority && (row.priority.toLowerCase() === 'high' || row.priority === '1' || row.priority === 1)) {
                        return data + ' <i class="fas fa-exclamation-circle text-danger" title="High Priority"></i>';
                    }
                    return data;
                }
            },
            { 
                "data": "total_tasks",
                "title": "Total Tasks",
                "className": "text-center"
            },
            { 
                "data": "completed_tasks",
                "title": "Completed Tasks",
                "className": "text-center",
                "render": function(data, type, row) {
                    return '<span class="badge bg-success">' + data + '</span>';
                }
            },
            { 
                "data": "pending_tasks",
                "title": "Pending Tasks",
                "className": "text-center",
                "render": function(data, type, row) {
                    return '<span class="badge bg-warning">' + data + '</span>';
                }
            }
        ],
        "order": [[0, "asc"]],
        "responsive": true,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        "language": {
            "emptyTable": "No projects found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total projects)",
            "lengthMenu": "Show _MENU_ projects",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "search": "Search Projects:",
            "zeroRecords": "No matching projects found",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });
    

} catch (e) {
    console.error('Error initializing DataTable:', e);
}
    // Helper function to format dates as DD-MM-YYYY
    function formatDateDDMMYYYY(date) {
        if (!(date instanceof Date) || isNaN(date)) return '';
        
        var day = date.getDate().toString().padStart(2, '0');
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var year = date.getFullYear();
        
        return day + '-' + month + '-' + year;
    }
    


    var taskIdToDelete = null;
    var taskNameToDelete = null;

    // Handle reminder button click
    $('#taskTable').on('click', '.btn-warning', function(e) {
        e.preventDefault();
        var taskId = $(this).data('task-id');
        var taskName = $(this).data('task-name');
        
        if (confirm('Are you sure you want to send a reminder for task: ' + taskName + '?')) {
            var token = '<?php echo isset($_SESSION['token']) ? $_SESSION['token'] : ''; ?>';
            
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
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Success',
                            body: response.success_message || 'Reminder sent successfully',
                            autohide: true,
                            delay: 3000
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Error',
                            body: response.errors || 'Error sending reminder',
                            autohide: true,
                            delay: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error sending reminder: ' + error);
                }
            });
        }
    });
    
    // Handle task status button click

    // Handle task status button click
    $('#taskTable').on('click', '.task-status-btn', function() {
        var $button = $(this);
        var taskId = $button.data('task-id');
        var taskName = $button.data('task-name');
        var currentStatus = String($button.data('current-status')); // Ensure string comparison
        var newStatus;
        if (currentStatus === '0') { // Was To Do
            newStatus = '1'; // Becomes Done
        } else if (currentStatus === '1') { // Was Done
            newStatus = '0'; // Becomes To Do
        } else if (currentStatus === '2') { // Was Pending
            newStatus = '1'; // Becomes Done
        } else {
            
            return; // Exit if status is not recognized
        }
        var isChecked = newStatus === '1'; // 'isChecked' effectively means 'is new status Done?'
        
        // Store the switch element to restore state if API call fails
        var $switch = $(this);
        
        // Get the row data from DataTable
        var row = table.row($(this).closest('tr')).data();
        
        $.ajax({
            url: '<?php echo API_URL; ?>project-task-update',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION["access_token"]; ?>',
                task_id: taskId,
                task_name: row.task_name,
                dept_id: row.dept_id,
                project_id: row.project_id,
                task_status: parseInt(newStatus)
            }),
            success: function(response) {
                if (response.is_successful === "1") {
                    // Remove the button if task is marked as done
                    if (newStatus === '1') {
                        $button.remove();
                    } else {
                        // Update button appearance for other statuses
                        var newButtonClass = 'btn-warning';
                        var newButtonText = 'Mark as Done';
                        
                        $button
                            .removeClass('btn-success btn-warning btn-info')
                            .addClass(newButtonClass)
                            .text(newButtonText)
                            .data('current-status', newStatus);
                    }
                    // If newStatus could be '2' (e.g. for reverting from Done to original Pending), add handling here

                    $button
                        .removeClass('btn-success btn-warning btn-info') // Clear relevant old classes
                        .addClass(newButtonClass)
                        .text(newButtonText)
                        .data('current-status', newStatus);
                    
                    // Show success message
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        body: newStatus === '1' ? 'Task marked as Done successfully' : (newStatus === '0' ? 'Task marked as To Do successfully' : 'Task status updated successfully'),
                        autohide: true,
                        delay: 3000
                    });
                    
                    // Clean up URL by removing page and limit parameters
                    if (window.history.replaceState) {
                        var cleanURL = window.location.pathname + window.location.search.replace(/[?&]?(page|limit)=\d+/g, '').replace(/^&/, '?');
                        window.history.replaceState({}, document.title, cleanURL);
                    }

                    // Refresh the DataTable to show updated data
                    table.ajax.reload(null, false);
                } else {
                    // Revert switch state
                    $switch.prop('checked', !isChecked);
                    
                    // Show error message
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: response.errors ? Object.values(response.errors).join('<br>') : 'Failed to update task status',
                        autohide: true,
                        delay: 3000
                    });
                }
            },
            error: function(xhr) {
                // Revert switch state
                $switch.prop('checked', !isChecked);
                
                // Show error message
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Error updating task status. Please try again.',
                    autohide: true,
                    delay: 3000
                });
            }
        });
    });
    
    // Function to format duration
    function formatDuration(startDate, endDate) {
        if (!startDate || !endDate) return 'N/A';
        
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffMs = end - start;
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        const diffHrs = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const diffMins = Math.round((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        
        let result = '';
        if (diffDays > 0) result += diffDays + 'd ';
        if (diffHrs > 0) result += diffHrs + 'h ';
        if (diffMins > 0 || result === '') result += diffMins + 'm';
        
        return result.trim();
    }

    // Function to get status badge HTML
    function getStatusBadge(status) {
        const statusClass = {
            'To Do': 'bg-secondary',
            'In Progress': 'bg-primary',
            'Completed': 'bg-success',
            'Overdue': 'bg-danger'
        }[status] || 'bg-secondary';
        
        return `<span class="badge ${statusClass}">${status}</span>`;
    }

    // Add event listener for expanding/collapsing rows
    $('#taskTable tbody').on('click', 'td.expand-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var expandButton = $(this).find('.expand-button');
        var rowData = row.data();
        
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            expandButton.removeClass('expanded');
        } else {
            // Show loading state
            var loadingRow = $('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> Loading tasks...</div>');
            row.child(loadingRow).show();
            
            // Fetch task details
            $.ajax({
                url: '<?php echo API_URL; ?>project-task-listing',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access_token: '<?php echo $_SESSION["access_token"]; ?>',
                    project_id: rowData.project_id,
                    limit: 10 // Adjust based on your needs
                }),
                success: function(response) {
                    // Check if we have tasks in the response
                    if (response && Array.isArray(response.tasks) && response.tasks.length > 0) {
                        // Group tasks by department
                        const tasksByDept = {};
                        response.tasks.forEach(task => {
                            if (!tasksByDept[task.dept_id]) {
                                tasksByDept[task.dept_id] = {
                                    dept_name: task.dept_name || 'Unassigned Department',
                                    tasks: []
                                };
                            }
                            tasksByDept[task.dept_id].tasks.push(task);
                        });
                        
                        // Create the task list HTML
                        let taskListHtml = `
                            <div class="child-row p-3">
                                <div class="row mb-3">
                                    <div class="col">
                                        <h5>Project: ${response.project_name || rowData.project_name || 'N/A'}</h5>
                                        <p class="mb-1"><strong>Priority:</strong> ${response.priority || rowData.priority || 'Not specified'}</p>
                                        <p class="mb-1"><strong>Total Tasks:</strong> ${response.total_tasks || 0} 
                                            | <span class="text-success">Completed: ${response.completed_task || 0}</span> 
                                            | <span class="text-warning">In Progress: ${response.ongoing_task || 0}</span>
                                            | <span class="text-danger">Overdue: ${response.overdue_task || 0}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Task ID</th>
                                                <th>Task Name</th>
                                                <th>Department</th>
                                                <th>Assigned To</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                        
                        // Add all tasks to the table
                        response.tasks.forEach(task => {
                            const startDate = task.start_date ? new Date(task.start_date).toLocaleString() : 'Not started';

                            const assignedTo = task.assigned_emp_name || 'Unassigned';
                            console.log("task details ......." , task.task_id , task.task_name , task.dept_name , assignedTo , task.task_status , startDate);
                            
                            taskListHtml += `
                                <tr>
                                    <td>#${task.task_id}</td>
                                    <td>${task.task_name || 'N/A'}</td>
                                    <td>${task.dept_name || 'N/A'}</td>
                                    <td>${assignedTo}</td>
                                    <td>${getStatusBadge(task.task_status)}</td>
                                    <td>${startDate}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            ${task.task_status_value !== 2 ? `
                                                <button class="btn btn-outline-success btn-sm task-action" 
                                                        data-task-id="${task.task_id}" 
                                                        data-action="start" 
                                                        title="Start Task">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button class="btn btn-outline-primary btn-sm task-action" 
                                                        data-task-id="${task.task_id}" 
                                                        data-action="complete" 
                                                        title="Mark as Done">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            ` : ''}
                                            <button class="btn btn-outline-info btn-sm task-action" 
                                                    data-task-id="${task.task_id}" 
                                                    data-action="edit" 
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm task-action" 
                                                    data-task-id="${task.task_id}" 
                                                    data-task-name="${task.task_name || ''}" 
                                                    data-action="delete" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                        
                        // Close the table
                        taskListHtml += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>`;
                        
                        row.child($(taskListHtml)).show();
                        expandButton.addClass('expanded');
                        
                        // Initialize tooltips
                        $('[data-toggle="tooltip"]').tooltip();
                        
                    } else {
                        row.child('<div class="text-center p-3">No tasks found for this project.</div>').show();
                        expandButton.addClass('expanded');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching task details:', error);
                    row.child('<div class="text-center p-3 text-danger">Error loading tasks. Please try again.</div>').show();
                    expandButton.addClass('expanded');
                }
            });
        }
    });
    
    // Handle task actions (start, complete, edit, delete)
    $('#taskTable').on('click', '.task-action', function() {
        const taskId = $(this).data('task-id');
        const action = $(this).data('action');
        const taskName = $(this).data('task-name') || 'this task';
        
        switch(action) {
            case 'start':
                // Handle start task
                alert(`Starting task #${taskId}`);
                // Add your start task logic here
                break;
                
            case 'complete':
                if (confirm(`Are you sure you want to mark task #${taskId} as complete?`)) {
                    // Handle complete task
                    alert(`Task #${taskId} marked as complete`);
                    // Add your complete task logic here
                }
                break;
                
            case 'edit':
                // Handle edit task
                alert(`Editing task #${taskId}`);
                // Add your edit task logic here
                break;
                
            case 'delete':
                if (confirm(`Are you sure you want to delete task: ${taskName}?`)) {
                    // Handle delete task
                    alert(`Task #${taskId} deleted`);
                    // Add your delete task logic here
                }
                break;
        }
    });

    // Initialize with the current page if it exists in the URL
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('page')) {
        var pageNum = parseInt(urlParams.get('page')) - 1; // DataTables uses 0-based index
        if (pageNum >= 0) {
            setTimeout(function() {
                table.page(pageNum).draw('page');
            }, 100);
        }
    }
    
    // Listen for page changes and update URL
    table.on('page.dt', function() {
        var info = table.page.info();
        // Update URL with current page
        var url = new URL(window.location);
        url.searchParams.set('page', info.page + 1);
        window.history.pushState({}, '', url);
    });
    
   

    $('#taskTable').on('click', '.delete-task', function() {
        taskIdToDelete = $(this).data('task-id');
        taskNameToDelete = $(this).data('task-name');
        $('#taskNameToDelete').text(taskNameToDelete);
        $('#deleteModal').modal('show');
    });

   

    // Add active class to navigation
    $('#project-task a').addClass('active nav-link');
});

function getCookie(name) {
    let cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}
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

<script>
var taskIdToDelete = null;
var taskNameToDelete = null;

// Show delete confirmation modal
$('#taskTable').on('click', '.delete-task', function() {
    taskIdToDelete = $(this).data('task-id');
    taskNameToDelete = $(this).data('task-name');
    $('#taskNameToDelete').text(taskNameToDelete);
    $('#deleteModal').modal('show');
});

// Handle confirm delete
$('#confirmDelete').click(function() {
    if (!taskIdToDelete) return;
    
    $.ajax({
        url: '<?php echo API_URL; ?>project-task-delete',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            task_id: taskIdToDelete
        }),
        success: function(response) {
            if (response.is_successful === '1') {
                // Remove the row from the table
                var row = $('button[data-task-id="' + taskIdToDelete + '"]').closest('tr');
                $('#taskTable').DataTable().row(row).remove().draw();
                
                // Show success message
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: 'Success',
                    body: response.success_message || 'Task deleted successfully',
                    autohide: true,
                    delay: 3000
                });
            } else {
                // Show error message
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: response.errors || 'Error deleting task',
                    autohide: true,
                    delay: 3000
                });
            }
        },
        error: function(xhr) {
            // Show error message
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: 'Error deleting task. Please try again.',
                autohide: true,
                delay: 3000
            });
        },
        complete: function() {
            $('#deleteModal').modal('hide');
            taskIdToDelete = null;
            taskNameToDelete = null;
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