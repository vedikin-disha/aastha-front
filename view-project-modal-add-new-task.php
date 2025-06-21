<!-- Add Task Modal -->

<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="card-header" style="background-color: #30b8b9;border:none;color:white;">

                <h5 class="modal-title" id="addTaskModalLabel">Add Project Task</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form id="projectTaskForm" method="post" onsubmit="handleTaskFormSubmission(event)">

                    <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>">

                    

                    <!-- Department Selection -->

                    <div class="form-group mb-3">

                        <label for="id_dept" class="form-label">Department</label>

                        <div class="input-group">

                            <select class="form-control select2" id="id_dept" name="dept_id" required>

                                <option value="">Select Department</option>

                            </select>

                            <div class="input-group-append">

                                <span class="input-group-text"><i class="fas fa-building"></i></span>

                            </div>

                        </div>

                        <div class="invalid-feedback" id="deptError" style="display: none;"></div>

                    </div>



                    <!-- Task Name -->

                    <div class="form-group mb-3">

                        <label for="task_name" class="form-label">Task Name</label>

                        <div class="input-group">

                            <input type="text" class="form-control" id="task_name" name="task_name" required>

                            <div class="input-group-append">

                                <span class="input-group-text"><i class="fas fa-tasks"></i></span>

                            </div>

                        </div>

                        <div class="invalid-feedback" id="taskError" style="display: none;"></div>

                    </div>



                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9; border: none;">

                            <i class="fas fa-save"></i> Save Task

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>

    
// Load departments for task modal

function loadDepartmentsForTask() {

    $.ajax({

        url: '<?php echo API_URL; ?>department',

        type: 'POST',

        data: JSON.stringify({

            access_token: '<?php echo $_SESSION['access_token']; ?>',

            // is_active: '1' // Only active departments

        }),

        contentType: 'application/json',

        success: function(response) {

            const $select = $('#id_dept');

            $select.empty().append('<option value="">Select Department</option>');

            

            if (response.is_successful === '1' && response.data && response.data.length > 0) {

                response.data.forEach(function(dept) {

                    $select.append(new Option(dept.dept_name, dept.dept_id));

                });

            }

            

            // Initialize Select2

            // $select.select2({

            //     theme: 'bootstrap-5',

            //     width: '100%',

            //     dropdownParent: $('#addTaskModal')

            // });

        },

        error: function(xhr, status, error) {

            console.error('Error loading departments:', error);

            Swal.fire('Error', 'Failed to load departments. Please try again.', 'error');

        }

    });

}



// Handle task form submission

function handleTaskFormSubmission(e) {

    



    // $('#projectTaskForm').on('submit', function(e) {

    e.preventDefault();

    

    const $form = $('#projectTaskForm');

    const $submitBtn = $form.find('button[type="submit"]');

    const originalBtnText = $submitBtn.html();

    

    // Reset error states

    $('.is-invalid').removeClass('is-invalid');

    $('.invalid-feedback').hide();

    

    // Get form data

    const formData = {

        access_token: '<?php echo $_SESSION['access_token']; ?>',

        project_id: <?php echo $project_id; ?>,

        dept_id: $('#id_dept').val(),

        task_name: $('#task_name').val().trim(),

        task_status: 0

    };

    

    // Client-side validation

    let isValid = true;

    

    if (!formData.dept_id) {

        $('#id_dept').addClass('is-invalid');

        $('#deptError').text('Please select a department').show();

        isValid = false;

    }

    

    if (!formData.task_name) {

        $('#task_name').addClass('is-invalid');

        $('#taskError').text('Please enter a task name').show();

        isValid = false;

    }

    

    if (!isValid) {

        return;

    }

    

    // Disable button and show loading state

    // $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

    

    // Make API call to save task

    $.ajax({

        url: '<?php echo API_URL; ?>project-task-add',

        type: 'POST',

        contentType: 'application/json',

        data: JSON.stringify(formData),

        success: function(response) {

            if (response.is_successful === '1') {

                //toast success message

                const successAlert = $('<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' +

                        '<i class="fas fa-check-circle"></i> Task added successfully!' +

                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +

                        '<span aria-hidden="true">&times;</span></button></div>');

                    

                    $('#project-tasks-table').before(successAlert);

                    

                    // Reload QnA data



                    setTimeout(function() {

                        successAlert.alert('close');

                    }, 2000);

                

                

                    $('#addTaskModal').modal('hide');

                    loadProjectTasks(); // Reload tasks
                    loadUsers();

                    // Reset form

                    $form[0].reset();

                    $('.select2').val(null).trigger('change');

                

            } else {

                toastError(response.errors || 'Failed to add task');

            }

        },

        error: function(xhr, status, error) {

            console.error('Error adding task:', error);

            toastError('Failed to add task. Please try again.');

        },

        complete: function() {

            $submitBtn.prop('disabled', false).html(originalBtnText);

        }

    });

// });

}


$("document").ready(function() {

    // Load departments when modal is shown

    $('#addTaskModal').on('show.bs.modal', function () {

        if ($('#id_dept').find('option').length <= 1) {

            loadDepartmentsForTask();

    }

    });



    // Reset form when modal is hidden

    $('#addTaskModal').on('hidden.bs.modal', function () {

        $('#projectTaskForm')[0].reset();

        $('.select2').val(null).trigger('change');

        $('.is-invalid').removeClass('is-invalid');

        $('.invalid-feedback').hide();

    });

});

</script>