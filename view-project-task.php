


<div class="row">

    <div class="table-responsive">

        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h6 class="m-0 font-weight-bold text-primary" style="color:#30b8b9 !important;">
                    Project Task
                </h6>

            </div>
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
        
        <!-- Left side: dropdown + assign -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
            <select id="user-filter" class="form-control form-control-sm" style="width: 180px;margin-right: 10px;">
              <option value="">Select User</option>
            </select>
          <?php endif; ?>

          <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
            <button type="button" class="btn btn-sm btn-success" onclick="assignTask()" >
              <i class="fas fa-user"></i> Assign To
            </button>
          <?php endif; ?>
        </div>

        <!-- Right side: Add New Task -->
        <div>
          <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addTaskModal">
              <i class="fas fa-plus"></i> Add New Task
            </button>
          <?php endif; ?>
        </div>
    </div>

</div>

                        

                            <table class="table table-striped table-bordered" id="project-tasks-table">

                                <thead class="thead-dark">

                                    <tr>
                                    <th><input type="checkbox" id="select-all-tasks"></th>
                                        <th>Task Name</th>

                                        <th>Department</th>

                                        <!-- <th>Project Name</th> -->

                                        <th>Status</th>

                                        <th>Mark as Done</th>

                                    </tr>

                                </thead>

                                <tbody id="project-tasks-tbody">

                                    <!-- Tasks will be loaded here -->

                                </tbody>

                            </table>



                        </div>

                    </div>

<script>
// $("document").ready(function() {
function loadUsers() {

$.ajax({

    url: '<?php echo API_URL; ?>user',

    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify({

        access_token: '<?php echo $_SESSION["access_token"]; ?>'

    }),

    success: function(response) {

        if (response.is_successful === '1' && response.data) {

            const userSelect = $('#timeline-user-filter');

            response.data.forEach(function(user) {

                userSelect.append(`<option value="${user.emp_id}">${user.emp_name}</option>`);

            });

        }

    }

});

}



    $(document).on('change', '#select-all-tasks', function() {
        const isChecked = $(this).is(':checked');

        $('.task-checkbox:enabled').prop('checked', isChecked);
    });



    function loadProjectTasks() {

        $.ajax({

            url: '<?php echo API_URL; ?>project-task-view',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                limit:100,

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

                project_id: <?php echo $project_id; ?>

            }),

            success: function(response) {

                if (response.is_successful === '1' && response.data && response.data.tasks) {

                    const tableBody = $('#project-tasks-tbody');

                    tableBody.empty();

                    

                    if (response.data.tasks.length === 0) {

                        const noTasksRow = `

                            <tr>

                                <td colspan="4" class="text-center py-4">

                                    <i class="fas fa-tasks fa-2x mb-3 d-block text-muted"></i>

                                    <p class="text-muted">Currently no tasks assigned</p>

                                </td>

                            </tr>`;

                        tableBody.html(noTasksRow);

                        return;

                    }

                    

                    response.data.tasks.forEach(function(task) {

                        const statusClass = task.task_status === 'Done' ? 'success' : 

                                           task.task_status === 'To Do' ? 'warning' : 'info';

                        

                        const isDone = task.task_status === 'Done';

                        const switchChecked = isDone ? 'checked' : '';

                        const row = `

                            <tr>
                                // add the checkbox if done then checkbox is remove checkbox
                               ${isDone 
            ? `<td><input type="checkbox" class="task-checkbox" disabled data-task-id="${task.task_id}"></td>` 
            : `<td><input type="checkbox" class="task-checkbox" ${switchChecked} data-task-id="${task.task_id}"> </td>`}
       
                                

                              <td>${task.task_name}</td>

                                <td>${task.dept_name}</td>

                               

                              

                                <td><span class="badge badge-${statusClass}">${task.task_status}</span></td>

                                <td>

                                

                                    <button type="button" class="btn ${isDone ? '' : 'btn-warning'} btn-sm task-status-btn"

                                        data-task-id="${task.task_id}"

                                        data-task-name="${task.task_name}"

                                        data-dept-id="${task.dept_id}"

                                        data-project-id="${task.project_id}"

                                        data-current-status="${isDone ? '1' : '2'}">

                                        ${isDone ? '' : 'Mark as Done'}

                                    </button>

                                </td>

                            </tr>

                        `;

                        tableBody.append(row);



                        // loop through all first level div under projectTabsContent div id and remove active and show class.

                        $('#projectTabsContent').find('div').removeClass('active show');

                        $('#project-task-tab-content').addClass('active show');

                    });

                } else {

                    $('#project-tasks-tbody').html('<tr><td colspan="4" class="text-center">No tasks found</td></tr>');

                }

            },

            error: function(xhr, status, error) {

                console.error('Error loading tasks:', error);

                $('#project-tasks-tbody').html('<tr><td colspan="4" class="text-center text-danger">Error loading tasks</td></tr>');

            }

        });

    }



    // Handle task status button click

    $(document).on('click', '.task-status-btn', function() {

        const $button = $(this);

        const taskId = $button.data('task-id');

        const taskName = $button.data('task-name');

        const deptId = $button.data('dept-id');

        const projectId = $button.data('project-id');

        const currentStatus = $button.data('current-status');

        const newStatus = currentStatus === '1' ? '2' : '1';

        const isChecked = newStatus === '1';



        // Make API request to update task status

        $.ajax({

            url: '<?php echo API_URL; ?>project-task-update',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

                task_id: taskId,

                task_name: taskName,

                dept_id: deptId,

                task_status: isChecked ? 1 : 0,

                project_id: projectId

            }),

            success: function(response) {

                if (response.is_successful === '1') {

                    // Update was successful, reload the tasks to show updated status

                    loadProjectTasks();

                    // Show success message

                    const message = isChecked ? 'Task marked as done!' : 'Task marked as pending';

                    showToast(response.success_message);

                } else {

                    // Update failed, revert switch state

                    $switch.prop('checked', !isChecked);

                    showToast(response.error_message);

                }

            },

            error: function(xhr, status, error) {

                console.error('Error updating task status:', error);

                    showToast('Error updating task status. Please try again.');

            }

        });

    });


    
    function loadUsersToDropdown() {
        $.ajax({
            url: '<?php echo API_URL; ?>user',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION['access_token']; ?>'
            }),
            success: function (response) {
                if (response.is_successful === "1") {
                    const users = response.data;
                    const $select = $('#user-filter');

                    // Clear existing (except first)
                    $select.find('option:not(:first)').remove();

                    users.forEach(user => {
                        const option = $('<option>', {
                            value: user.emp_id,
                            text: user.emp_name 
                        });
                        $select.append(option);
                    });
                } else {
                    console.error('API Error:', response.errors || 'Unknown error');
                }
            },
            error: function (xhr, status, error) {
                console.error('Request Failed:', status, error);
            }
        });
    }


    function assignTask() {
        const selectedTasks = [];
        $('.task-checkbox:checked:enabled').each(function() {
            selectedTasks.push($(this).data('task-id'));
        });

        if (selectedTasks.length === 0) {
            alert('Please select at least one task to assign.');
            return;
        }

        const userId = $('#user-filter').val();
        if (!userId) {
            alert('Please select a user to assign tasks to.');
            return;
        }

        if (confirm(`Are you sure you want to assign ${selectedTasks.length} task(s) to the selected user?`)) {
            $.ajax({
                url: '<?php echo API_URL; ?>task-assign-multiple',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    access_token: '<?php echo $_SESSION["access_token"]; ?>',
                    task_ids: selectedTasks,
                    assigned_emp_id: userId
                }),
                success: function(response) {
                    if (response.is_successful === '1') {
                        showToast(response.success_message);
                        // Refresh the task list
                        loadProjectTasks();
                    } else {
                        showToast(response.error_message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error assigning tasks:', error);
                    showToast('An error occurred while assigning tasks. Please try again.');
                }
            });
        }
    }


// });
</script>