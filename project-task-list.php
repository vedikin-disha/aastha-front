<?php include 'common/header.php'; ?>
<!-- Add DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
<!-- Add Bootstrap Switch CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/css/bootstrap3/bootstrap-switch.min.css">
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
                <!-- <div class="card-tools">
                    <a href="add-project-task.php" class="btn btn-primary">Add New Task</a>
                </div> -->
            </div>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <form method="get" class="mb-4">
                <!-- <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="limit" class="form-control" onchange="this.form.submit()">
                            <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10 per page</option>
                            <option value="25" <?php echo $limit == 25 ? 'selected' : ''; ?>>25 per page</option>
                            <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50 per page</option>
                            <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100 per page</option>
                        </select>
                    </div>
                </div> -->
            </form>
            <div class="new-pms-ap">
                <table id="taskTable" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th class="sorting">Task Name</th>
                            <th class="sorting">Department</th>
                            <th class="sorting">Project Name</th>
                            <th class="sorting" style="width:40px;">Assigned Employees</th>
                            <th class="sorting">task Duration</th>
                            <th class="sorting">Status</th>
                            <th style="width: 100px;">Mark as Done</th>
                        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                        <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically by DataTables -->
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



<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<style>
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
    // Helper function to format dates as DD-MM-YYYY
    function formatDateDDMMYYYY(date) {
        if (!(date instanceof Date) || isNaN(date)) return '';
        
        var day = date.getDate().toString().padStart(2, '0');
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var year = date.getFullYear();
        
        return day + '-' + month + '-' + year;
    }
    
    // Initialize DataTable
    // Clean up URL by removing page and limit parameters if they exist
    if (window.history.replaceState && (window.location.search.includes('page=') || window.location.search.includes('limit='))) {
        var cleanURL = window.location.pathname + window.location.search.replace(/[?&]?(page|limit)=\d+/g, '').replace(/^&/, '?').replace(/\?$/, '');
        window.history.replaceState({}, document.title, cleanURL);
    }

    var table = $('#taskTable').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10, // Default page length
        "searching": true,
        "info": true,
        "ordering": true,
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": true, "targets": [0, 1, 2, 3, 4] }, // Enable sorting for first 5 columns
            { "orderable": false, "targets": [5] } // Disable sorting for action column
        ],
        "autoWidth": false,
        "responsive": true,
        "pagingType": "full_numbers",
        "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-12'i><'col-12'p>>",
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        "displayStart": 0, // Start from the first record
        "language": {
            "emptyTable": "No tasks available",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "",
            "lengthMenu": "Show _MENU_ entries",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "search": "Search:",
            "zeroRecords": "No matching records found",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        "order": [[0, "asc"]],
        "ajax": {
            url: '<?php echo API_URL; ?>project-task-listing',
            "type": "POST",
            "contentType": "application/json",
            "data": function(d) {
                // Create a data object that includes all necessary pagination parameters
                // Calculate page number ensuring it's at least 1
                var calculatedPage = Math.floor(d.start / d.length) + 1;
                var pageNumber = Math.max(1, calculatedPage); // Ensure page is never less than 1
                
                // Get column name based on index
                var columnMap = [
                    'task_name',
                    'dept_name',
                    'project_name',
                    'assigned_emp_name',
                    'task_status',
                    'task_duration'
                ];
                
                // Set default sorting to first column (task_name) ascending
                var orderColumn = columnMap[0];  // Default to task_name
                var orderDir = 'asc';           // Default to ascending
                
                // Calculate page number based on DataTables start and length
                var pageNumber = Math.max(1, Math.floor(Math.max(0, d.start) / d.length) + 1); // Ensure start is never negative
                
                // Override defaults if sorting is specified
                if (d.order && d.order.length > 0) {
                    orderColumn = columnMap[d.order[0].column] || columnMap[0];
                    orderDir = d.order[0].dir || 'asc';
                }


                var params = {
                    access_token: "<?php echo $_SESSION['access_token']; ?>",
                    limit: d.length,
                    page: pageNumber, // Already 1-based and validated
                    search: d.search.value,
                    order_by: orderColumn,
                    order_dir: orderDir
                };
                
                
                return JSON.stringify(params);
            },
            "dataSrc": function(json) {
                // Return the tasks array or empty array if not available
                if (json.data && json.data.tasks) {
                    return json.data.tasks;
                }
                return [];
            },
            "dataFilter": function(data) {
                var json = JSON.parse(data);
                
                
                // Check if the API call was successful
                if (json.is_successful === "1") {
                    // Make sure the response has the required DataTables properties
                    if (json.data && json.data.tasks) {
                        // If we have tasks array, use its length for filtered count
                        json.recordsFiltered = json.data.total;
                        
                        // For total records, use total_count if available, otherwise use filtered count
                        if (typeof json.data.total !== 'undefined') {
                            json.recordsTotal = parseInt(json.data.total) || 0;
                        } else {
                            // If no total_count provided, use the length of tasks array
                            json.recordsTotal = json.recordsFiltered;
                        }
                        
                        // Ensure we don't show more records than we actually have
                        if (json.recordsTotal < json.recordsFiltered) {
                            json.recordsTotal = json.recordsFiltered;
                        }
                    } else {
                        // If no tasks data, set both to 0
                        json.recordsTotal = 0;
                        json.recordsFiltered = 0;
                    }
                } else {
                    // Handle API error
                    
                    // Set empty data and show error message
                    json.data = { tasks: [] };
                    json.recordsTotal = 0;
                    json.recordsFiltered = 0;
                    
                    // Show error toast
                    setTimeout(function() {
                        var errorMsg = '';
                        if (json.errors) {
                            for (var key in json.errors) {
                                errorMsg += key + ': ' + json.errors[key].join(', ') + '<br>';
                            }
                        } else {
                            errorMsg = 'Error loading data from server.';
                        }
                        
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Error',
                            body: errorMsg,
                            autohide: true,
                            delay: 5000
                        });
                    }, 500);
                }
                
                
                return JSON.stringify(json);
            }
        },
        "columns": [
            { "data": "task_name" },
            { "data": "dept_name" },
            { 
                "data": "project_name",
                "render": function(data) {
                    return data || '';
                }
            },
            {
    "data": "assigned_emp_name",
    "render": function(data, type, row) {
        if (row.assigned_emp_id && row.assigned_emp_name) {
            if (row.assigned_emp_profile) {
                return '<div class="d-flex flex-column">' +
                    '<img src="' + row.assigned_emp_profile + '" class="rounded-circle mb-1" width="32" height="32" style="object-fit: cover;" onerror="this.src=\'https://cdn-icons-png.flaticon.com/512/149/149071.png\'">' +
                    '<small class="text-truncate" style="max-width: 100%;" title="' + row.assigned_emp_name + '">' + row.assigned_emp_name.split(' ')[0] + '</small>' +
                    '</div>';
            } else {
                return '<div class="d-flex flex-column">' +
                    '<i class="fas fa-user-circle" style="font-size: 32px; color: #6c757d; margin-bottom: 4px;"></i>' +
                    '<small class="text-truncate" style="max-width: 100%;" title="' + row.assigned_emp_name + '">' + row.assigned_emp_name.split(' ')[0] + '</small>' +
                    '</div>';
            }
        } else {
            return '<span class="text-muted"></span>';
        }
    }
},
            { 
                "data": "task_duration",
                "render": function(data, type, row) {
                    if (!data) return '';
                    try {
                        // Split the date range (format: "2025-06-17 00:00:00 - 2025-06-30 00:00:00")
                        var dateParts = data.split(' - ');
                        if (dateParts.length === 2) {
                            // Format start date (first part)
                            var startDateStr = dateParts[0].trim().split(' ')[0]; // Get just the date part
                            var startDateParts = startDateStr.split('-');
                            var startFormatted = startDateParts[2] + '-' + startDateParts[1] + '-' + startDateParts[0]; // DD-MM-YYYY
                            
                            // Format end date (second part)
                            var endDateStr = dateParts[1].trim().split(' ')[0]; // Get just the date part
                            var endDateParts = endDateStr.split('-');
                            var endFormatted = endDateParts[2] + '-' + endDateParts[1] + '-' + endDateParts[0]; // DD-MM-YYYY
                            
                            return startFormatted + ' to ' + endFormatted;
                        }
                        return data; // Return original if format is unexpected
                    } catch (e) {
                        console.error('Error formatting date:', e);
                        return data; // Return original data if there's an error
                    }
                }
            },
            { 
                "data": "task_status",
                "render": function(data, type, row) {
                    var statusClass = '';
                    var statusText = data;
                    if (row.task_status_value === 0) {
                        statusClass = 'badge-warning';
                        statusText = 'To Do';
                    } else if (row.task_status_value === 1) {
                        statusClass = 'badge-success';
                        statusText = 'Done';
                    } else if (row.task_status_value === 2) {
                        statusClass = 'badge-warning';
                        statusText = 'Pending';
                    } else {
                        statusClass = 'badge-secondary'; // Fallback
                    }
                    return '<span class="badge ' + statusClass + '">' + statusText + '</span>';
                }
            },
          
            {
                "data": "task_id",
                "render": function(data, type, row) {
                    // If task is already done, don't render any button
                    if (row.task_status_value === 1) {
                        return '';
                    }
                    
                    var buttonClass = 'btn-warning';
                    var buttonText = 'Mark as Done';
                    var currentStatusForButton = row.task_status_value;

                    // Only show button for To Do (0) or Pending (2) status
                    if (row.task_status_value === 0 || row.task_status_value === 2) {
                        return '<button type="button" class="btn ' + buttonClass + ' btn-sm task-status-btn" ' +
                               'data-task-id="' + data + '" ' +
                               'data-task-name="' + row.task_name + '" ' +
                               'data-project-id="' + row.project_id + '" ' +
                               'data-dept-id="' + row.dept_id + '" ' +
                               'data-task-duration="' + row.task_duration + '" ' +
                               'data-current-status="' + currentStatusForButton + '">' +
                               buttonText +
                               '</button>';
                    }
                    
                    return ''; // Hide button for unexpected statuses
                }
            }
            
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            ,{
                "data": "task_id",
                "render": function(data, type, row) {
                    return '<div class="btn-group" style="gap: 5px;">' +
                    // add reminder button 
                           '<button class="btn btn-warning btn-sm rounded" data-task-id="' + data + '" data-task-name="' + row.task_name.replace(/"/g, '&quot;') + '"><i class="fas fa-bell"></i> Reminder</button>' +
                           '<a href="edit-project-task?id=' + btoa(data) + '" class="btn btn-primary btn-sm rounded" style="background-color: #30b8b9;border:none;"><i class="fas fa-edit"></i> Edit</a> ' +
                           '<button class="btn btn-danger btn-sm delete-task rounded" data-task-id="' + data + '" data-task-name="' + row.task_name + '"><i class="fas fa-trash"></i> Delete</button>' +
                           '</div>';
                }
            }
            <?php endif; ?>
        ]
    });

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
    
    // Remove the custom click handler for pagination buttons
    // as it's causing conflicts with DataTables' built-in pagination
    // $(document).off('click', '.paginate_button:not(.disabled)');

    $('#taskTable').on('click', '.delete-task', function() {
        taskIdToDelete = $(this).data('task-id');
        taskNameToDelete = $(this).data('task-name');
        $('#taskNameToDelete').text(taskNameToDelete);
        $('#deleteModal').modal('show');
    });

    // Handle confirm delete
    // $('#confirmDelete').click(function() {
    //     if (!taskIdToDelete) return;

    //     var csrftoken = getCookie('csrftoken');
        
    //     $.ajax({
    //         url: 'project-task-list.php/delete/' + taskIdToDelete + '/',
    //         type: 'POST',
    //         headers: {
    //             'X-CSRFToken': csrftoken
    //         },
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 // Remove the row from the table
    //                 table.row($('button[data-task-id="' + taskIdToDelete + '"]').closest('tr')).remove().draw();
                    
    //                 // Show success message
    //                 $(document).Toasts('create', {
    //                     class: 'bg-success',
    //                     title: 'Success',
    //                     body: response.success_message,
    //                     autohide: true,
    //                     delay: 3000
    //                 });
    //             } else {
    //                 // Show error message
    //                 $(document).Toasts('create', {
    //                     class: 'bg-danger',
    //                     title: 'Error',
    //                     body: response.success_message,
    //                     autohide: true,
    //                     delay: 3000
    //                 });
    //             }
    //         },
    //         error: function(xhr) {
    //             // Show error message
    //             $(document).Toasts('create', {
    //                 class: 'bg-danger',
    //                 title: 'Error',
    //                 body: 'Error deleting task. Please try again.',
    //                 autohide: true,
    //                 delay: 3000
    //             });
    //         },
    //         complete: function() {
    //             $('#deleteModal').modal('hide');
    //             taskIdToDelete = null;
    //             taskNameToDelete = null;
    //         }
    //     });
    // });

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



<!-- Add DataTables JS -->
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


<?php include 'common/footer.php'; ?>