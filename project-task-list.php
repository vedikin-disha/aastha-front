<?php include 'common/header.php'; ?>
<style>
    /* Custom styling for the task done switch */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745 !important;
        /* border-color:black !important; */
    }
    
   
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Project Tasks</h3>

                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                    <div class="card-tools">
                    <a href="add-project-task.php" class="btn btn-primary">Add New Task</a>
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
            <table id="taskTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Department</th>
                        <th>Project Name</th>
                        <th>Status</th>
                        <th style="width: 100px;">Mark as Done</th>
                        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                        <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->

                    <?php
                    // Get page and limit from URL parameters, default to 1 and 10
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                    
                    // Fetch tasks from API
                    $api_url = API_URL . 'project-task-list';
                    $headers = array(
                        'Content-Type: application/json'
                    );
                    
                    $body = array(
                        'access_token' => $_SESSION['access_token'],
                        'page' => $current_page,
                        'limit' => $limit
                    );
                    
                    // Add search parameter if provided
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $body['search'] = $_GET['search'];
                    }
                    
                    $ch = curl_init($api_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    
                    $response = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    if ($http_code == 200) {
                        $result = json_decode($response, true);
                        if ($result['is_successful'] == '1' && !empty($result['data'])) {
                            foreach ($result['data'] as $task) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($task['task_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($task['dept_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($task['project_name']) . '</td>';
                                echo '<td>';
                                switch($task['task_status']) {
                                    case '1':
                                        echo '<span class="badge bg-warning">Pending</span>';
                                        break;
                                    case '2':
                                        echo '<span class="badge bg-info">In Progress</span>';
                                        break;
                                    case '3':
                                        echo '<span class="badge bg-success">Completed</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-secondary">Unknown</span>';
                                }
                                echo '</td>';
                                echo '<td>';
                                echo '<a href="edit-project-task.php?id=' . $task['task_id'] . '" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a> ';
                                echo '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $task['task_id'] . ')"><i class="fas fa-trash"></i> Delete</button>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No tasks found</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Error fetching tasks</td></tr>';
                    }
                    ?>
                <?php
                // Get page and limit from URL parameters, default to 1 and 10
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                
                // Fetch tasks from API
                $api_url = API_URL . 'project-task-list';
                $headers = array(
                    'Content-Type: application/json'
                );
                
                $body = array(
                    'access_token' => $_SESSION['access_token'],
                    'page' => $current_page,
                    'limit' => $limit
                );
                
                // Add search parameter if provided
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $body['search'] = $_GET['search'];
                }
                
                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($http_code == 200) {
                    $result = json_decode($response, true);
                    if ($result['is_successful'] == '1' && !empty($result['data'])) {
                        foreach ($result['data'] as $task) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($task['task_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($task['dept_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($task['project_name']) . '</td>';
                            echo '<td>';
                            switch($task['task_status']) {
                                case '1':
                                    echo '<span class="badge bg-warning">Pending</span>';
                                    break;
                                case '2':
                                    echo '<span class="badge bg-info">In Progress</span>';
                                    break;
                                case '3':
                                    echo '<span class="badge bg-success">Completed</span>';
                                    break;
                                default:
                                    echo '<span class="badge bg-secondary">Unknown</span>';
                            }
                            echo '</td>';
                            echo '<td>';
                            echo '<a href="edit-project-task.php?id=' . $task['task_id'] . '" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a> ';
                            echo '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(' . $task['task_id'] . ')"><i class="fas fa-trash"></i> Delete</button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        
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
    // Initialize DataTable
    var table = $('#taskTable').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": <?php echo isset($_GET['limit']) ? (int)$_GET['limit'] : 10; ?>,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pagingType": "full_numbers",
        "dom": '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
        "displayStart": <?php echo (isset($_GET['page']) ? ((int)$_GET['page'] - 1) * (isset($_GET['limit']) ? (int)$_GET['limit'] : 10) : 0); ?>,
        "pageLength": <?php echo isset($_GET['limit']) ? (int)$_GET['limit'] : 10; ?>,
        "drawCallback": function(settings) {
            // Update URL with current page information without reloading
            var api = this.api();
            var pageInfo = api.page.info();
            var pageNum = pageInfo.page + 1;
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('page', pageNum);
            currentUrl.searchParams.set('limit', pageInfo.length);
            window.history.replaceState({}, '', currentUrl.toString());
            
            console.log('Draw callback - Page info:', pageInfo);
            console.log('Total records:', pageInfo.recordsTotal);
            console.log('Records per page:', pageInfo.length);
            console.log('Current page:', pageInfo.page);
            
            // Re-attach handlers to pagination buttons
            $('.paginate_button').off('click').on('click', function() {
                var $this = $(this);
                var page = $this.data('dt-idx');
                console.log('Pagination button clicked:', page);
            });
        },
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
                
                var params = {
                    access_token: "<?php echo $_SESSION['access_token']; ?>",
                    limit: d.length,
                    page: pageNumber,
                    search: d.search.value
                };
                
                // Debug information
                console.log('Start:', d.start, 'Length:', d.length, 'Calculated page:', calculatedPage, 'Final page:', pageNumber);
                
                // Add search parameter if provided
                if (d.search && d.search.value) {
                    params.search = d.search.value;
                }
                
                // Log the request parameters for debugging
                console.log('DataTables Request:', params);
                
                return JSON.stringify(params);
            },
            "dataSrc": function(json) {
                // Update total record count for pagination
                if (json.data && json.data.tasks) {
                    return json.data.tasks;
                }
                return [];
            },
            "dataFilter": function(data) {
                var json = JSON.parse(data);
                console.log('API Response:', json); // Log the API response for debugging
                
                // Check if the API call was successful
                if (json.is_successful === "1") {
                    // Make sure the response has the required DataTables properties
                    if (json.data && typeof json.data.total_count !== 'undefined') {
                        json.recordsTotal = parseInt(json.data.total_count) || 0;
                        json.recordsFiltered = parseInt(json.data.total_count) || 0;
                        
                        // Ensure we have at least as many records as we have items in the current page
                        if (json.data.tasks && json.data.tasks.length > 0 && json.recordsTotal < json.data.tasks.length) {
                            json.recordsTotal = json.data.tasks.length;
                            json.recordsFiltered = json.data.tasks.length;
                        }
                    } else {
                        // Fallback values if the API doesn't return total_count
                        json.recordsTotal = (json.data && json.data.tasks) ? json.data.tasks.length * 3 : 0; // Multiply by estimated page count
                        json.recordsFiltered = json.recordsTotal;
                    }
                } else {
                    // Handle API error
                    console.error('API Error:', json.errors);
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
                
                console.log('Modified Response:', json); // Log the modified response
                return JSON.stringify(json);
            }
        },
        "columns": [
            { "data": "task_name" },
            { "data": "dept_name" },
            { 
                "data": "project_name",
                "render": function(data) {
                    return data || 'N/A';
                }
            },
            { 
                "data": "task_status",
                "render": function(data, type, row) {
                    var statusClass = row.task_status_value === 1 ? 'badge-success' : 'badge-warning';
                    return '<span class="badge ' + statusClass + '">' + data + '</span>';
                }
            },
            {
                "data": "task_id",
                "render": function(data, type, row) {
                    var isDone = row.task_status_value === 1;
                    return '<div class="custom-control custom-switch">' +
                           '<input type="checkbox" class="custom-control-input task-done-switch" id="taskDoneSwitch' + data + '" ' + 
                           'data-task-id="' + data + '" data-task-name="' + row.task_name + '" ' +
                           'data-project-id="' + row.project_id + '" ' +
                           'data-dept-id="' + row.dept_id + '" ' +
                           (isDone ? 'checked' : '') + '>' +
                           '<label class="custom-control-label" for="taskDoneSwitch' + data + '"></label>' +
                           '</div>';
                }
            }
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            ,{
                "data": "task_id",
                "render": function(data, type, row) {
                    return '<div class="btn-group">' +
                           '<a href="edit-project-task.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a> ' +
                           '<button class="btn btn-danger btn-sm delete-task" data-task-id="' + data + '" data-task-name="' + row.task_name + '"><i class="fas fa-trash"></i></button>' +
                           '</div>';
                }
            }
            <?php endif; ?>
        ]
    });

    var taskIdToDelete = null;
    var taskNameToDelete = null;

    // Handle task done toggle switch change
    $('#taskTable').on('change', '.task-done-switch', function() {
        var taskId = $(this).data('task-id');
        var taskName = $(this).data('task-name');
        var isChecked = $(this).prop('checked');
        
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
                task_status: isChecked ? 1 : 2
            }),
            success: function(response) {
                if (response.is_successful === "1") {
                    // Show success message
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        body: isChecked ? 'Task marked as done successfully' : 'Task marked as not done successfully',
                        autohide: true,
                        delay: 3000
                    });
                    
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

    // Handle delete button click
    // Use event delegation for dynamically created elements
    // Make sure pagination is working correctly
    $(document).on('click', '.paginate_button', function(e) {
        console.log('Pagination button clicked');
    });
    
    // Initialize with the current page if it exists in the URL
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('page')) {
        var pageNum = parseInt(urlParams.get('page')) - 1; // DataTables uses 0-based index
        if (pageNum >= 0) {
            setTimeout(function() {
                table.page(pageNum).draw('page');
                console.log('Set page to:', pageNum);
            }, 100);
        }
    }
    
    // Listen for page changes
    table.on('page.dt', function() {
        var info = table.page.info();
        console.log('Page changed to:', info.page);
        
        // Force a redraw to ensure the server gets the correct page request
        setTimeout(function() {
            table.ajax.reload(null, false); // null callback, false to keep current page
        }, 0);
    });
    
    // Add direct click handler for pagination buttons for better reliability
    $(document).on('click', '.paginate_button:not(.disabled)', function(e) {
        var $this = $(this);
        var classList = $this.attr('class');
        
        // Handle next/previous buttons
        if (classList.indexOf('next') >= 0) {
            var info = table.page.info();
            table.page(info.page + 1).draw('page');
        } else if (classList.indexOf('previous') >= 0) {
            var info = table.page.info();
            table.page(info.page - 1).draw('page');
        }
    });

    $('#taskTable').on('click', '.delete-task', function() {
        taskIdToDelete = $(this).data('task-id');
        taskNameToDelete = $(this).data('task-name');
        $('#taskNameToDelete').text(taskNameToDelete);
        $('#deleteModal').modal('show');
    });

    // Handle confirm delete
    $('#confirmDelete').click(function() {
        if (!taskIdToDelete) return;

        var csrftoken = getCookie('csrftoken');
        
        $.ajax({
            url: 'project-task-list.php/delete/' + taskIdToDelete + '/',
            type: 'POST',
            headers: {
                'X-CSRFToken': csrftoken
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Remove the row from the table
                    table.row($('button[data-task-id="' + taskIdToDelete + '"]').closest('tr')).remove().draw();
                    
                    // Show success message
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        body: response.success_message,
                        autohide: true,
                        delay: 3000
                    });
                } else {
                    // Show error message
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: response.success_message,
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
<?php include 'common/footer.php'; ?>