<?php

include 'common/header.php';

// Decode the base64 encoded project ID

$project_id = isset($_GET['id']) ? base64_decode($_GET['id']) : 0;

// Redirect if no valid ID

if (!$project_id) {
    header('Location: projects.php');

    exit();
}

if (!$project_id) {
    echo '<div class="alert alert-danger">Invalid project ID.</div>';

    echo '<a href="projects.php" class="btn btn-primary">Back to Projects</a>';

    include 'common/footer.php';

    exit;
}

$project = getProjectDetails($project_id);

?>

<link rel="stylesheet" href="css/project-view.css">

<script>
 function formatDate(dateStr) {

const date = new Date(dateStr);

const options = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' };

return date.toLocaleDateString('en-US', options);

}



// Function to format time

function formatTime(dateTimeStr) {

// For format like "Wed, 28 May 2025 17:33:11 GMT"

if (dateTimeStr && dateTimeStr.includes('GMT')) {

    // Extract the time part (17:33:11)

    const parts = dateTimeStr.split(' ');

    if (parts.length >= 5) {

        const timePart = parts[4];

        const timeParts = timePart.split(':');

        

        if (timeParts.length >= 2) {

            // Convert to 12-hour format

            let hours = parseInt(timeParts[0]);

            const minutes = timeParts[1];

            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;

            hours = hours ? hours : 12; // Convert 0 to 12

            

            return `${hours}:${minutes} ${ampm}`;

        }

    }

}



// Fallback to standard date object

const date = new Date(dateTimeStr);

return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

}



// Function to format date and time in the desired format

function formatDateTime(dateTimeStr) {

// Check if dateTimeStr is in the format "Day, DD Mon YYYY HH:MM:SS GMT"

if (dateTimeStr && dateTimeStr.includes('GMT')) {

    // Return it in the original format

    return dateTimeStr.replace(' GMT', '');

}



// Fallback to a standard format if the input is not as expected

const date = new Date(dateTimeStr);

const options = { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };

return date.toLocaleString('en-US', options);

}

</script>

<div class="container-fluid">

    <div class="row mb-2">

        <div class="col-sm-6">

            <h1>Project Details</h1>

        </div>

        <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

                <a href="projects.php" class="btn btn-secondary">

                    <i class="fas fa-arrow-left"></i> Back to Projects

                </a>

            </ol>

        </div>

    </div>



    <?php if ($project): ?>

    <div class="card">

        <div class="card-header  card-primary card-outline">

            <div class="project-header">

           

                <h3 class="project-title"><?php echo htmlspecialchars($project['project_name']); ?></h3>

                <div class="btn-group">

                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>

                    <a href="edit-project.php?id=<?php echo base64_encode($project['project_id']); ?>" class="btn btn-primary" style="background-color: #30b8b9;border:none;">

                        <i class="fas fa-edit"></i> Edit Project

                    </a>

                <?php endif; ?>

                   

                </div>

            </div>

        </div>

        <div class="card-body">

            <!-- Tabs navigation -->

            <ul class="nav nav-tabs" id="projectTabs" role="tablist">

                <li class="nav-item">

                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab">

                        <i class="fas fa-info-circle"></i> Project Details

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab">

                        <i class="fas fa-calendar-alt"></i> Project Timeline

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab">

                        <i class="fas fa-comments"></i> Project Compliances

                    </a>

                </li>

                <!-- //project-task -->

                <li class="nav-item">

                    <a class="nav-link" id="project-task-tab" data-toggle="tab" href="#project-task" role="tab">

                        <i class="fas fa-comments"></i> Project Task

                    </a>

                </li>
                <!-- add the new tab is attachments -->
                <li class="nav-item">

                    <a class="nav-link" id="attachments-tab" data-toggle="tab" href="#attachments" role="tab">

                        <i class="fas fa-file"></i> Attachments

                    </a>

                </li>
            </ul>



            <!-- Tab content -->

            <div class="tab-content" id="projectTabsContent">
                <!-- Timeline Tab -->
                <?php include 'view-project-timeline.php'; ?>
            </div>

            <div class="tab-pane fade show active" id="details" role="tabpanel">

                <?php include 'view-project-details.php'; ?>

            </div>


            <div class="tab-pane fade" id="comments" role="tabpanel">

                <?php include 'view-project-compliance.php'; ?>

            </div>

                <!-- Project Task Tab -->

                <div class="tab-pane fade" id="project-task-tab-content" role="tabpanel">

                    <?php include 'view-project-task.php'; ?>

                </div>


                <!-- attachments tab -->

                <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                    <?php include 'view-project-attachment.php'; ?>
                </div>

            </div>

        </div>

    </div>

    <?php else: ?>

    <div class="alert alert-warning">

        <i class="fas fa-exclamation-triangle"></i> Project details could not be loaded. Please try again later.

    </div>

    <?php endif; ?>

</div>



<script>

$(document).ready(function() {

    // Initialize tabs

    $('#projectTabs a').on('click', function (e) {

        e.preventDefault();

        $(this).tab('show');

        

        // Load timeline data when timeline tab is clicked

        if ($(this).attr('href') === '#timeline') {

            loadProjectTimeline();

            // Initialize rich text editor

            initRichTextEditor();

        }

    });

    

    // Add active class to navigation

    $('#projects-menu').addClass('active');
    // Check if timeline tab is active on page load

    if ($('#timeline-tab').hasClass('active')) {

        loadProjectTimeline();

    }

    

    // Check if comments tab is active on page load

    if ($('#comments-tab').hasClass('active')) {

        loadProjectQnA();

        loadDepartments();

    }

    if ($('#attachments-tab').hasClass('active')) {

        loadProjectAttachments();

    }

    

    // Load QnA data when comments tab is clicked

    $('#comments-tab').on('click', function() {

        loadProjectQnA();

        loadDepartments();

    });

    if ($('#attachments-tab').hasClass('active')) {

        loadProjectAttachments();

    }

    $('#project-task-tab').on('click', function() {

        loadProjectTasks();

        loadDepartmentsForTask();
        loadUsers();

    });


    // Format date for display

    function formatDate(dateString) {

        const date = new Date(dateString);

        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

    }


    // Load tasks when the project task tab is shown

    $('a[data-toggle="tab"][href="#project-task"]').on('shown.bs.tab', function (e) {

        loadProjectTasks();
        loadUsers();

    });



    







    // Handle checkbox changes

    $(document).on('change', '#user-checkbox-list input[type="checkbox"]', function() {

        const userId = $(this).val();

        const userName = $(this).data('name');

        const selectedUsers = $('#selected-users');

        

        if (this.checked) {

            // Add user tag

            selectedUsers.append(`

                <span class="badge badge-primary mr-2 mb-2 user-tag" data-id="${userId}">

                    ${userName}

                    <i class="fas fa-times ml-1" style="cursor: pointer;" onclick="removeUser('${userId}')"></i>

                </span>

            `);

        } else {

            // Remove user tag

            selectedUsers.find(`.user-tag[data-id="${userId}"]`).remove();

        }

    });



    // Function to remove user when clicking X on tag

    window.removeUser = function(userId) {

        $(`#user-${userId}`).prop('checked', false);

        $(`.user-tag[data-id="${userId}"]`).remove();

    };



    // Handle assignment save

    $('#save-assignment').on('click', function() {

        const selectedUsers = [];

        $('#user-checkbox-list input[type="checkbox"]:checked').each(function() {

            selectedUsers.push($(this).val());

        });

        

        if (selectedUsers.length === 0) {

            alert('Please select at least one user');

            return;

        }



        // Determine API endpoint based on mode

        const isAdd = window.assignmentMode === 'add';

        const apiUrl = '<?php echo API_URL; ?>' + 

            (isAdd ? 'assignment-add' : 'assignment-update');



        // Make API request

        $.ajax({

            url: apiUrl,

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION['access_token']; ?>',

                project_id: <?php echo $project_id; ?>,

                emp_ids: selectedUsers.map(Number)

            }),

            success: function(response) {

                if (response.is_successful === '1') {

                    $('#assignmentModal').modal('hide');

                    const message = isAdd ? 

                        'Project assignments added successfully' : 

                        'Project assignments updated successfully';

                    alert(message);

                    loadProjectTimeline(); // Reload timeline to show changes

                } else {

                    const action = isAdd ? 'add' : 'update';

                    alert(`Failed to ${action} assignments: ` + (response.errors || 'Unknown error'));

                }

            },

            error: function(xhr, status, error) {

                const action = isAdd ? 'adding' : 'updating';

                console.error(`Error ${action} assignments:`, error);

                alert(`Error ${action} assignments. Please try again.`);

            }

        });

    });

});



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

// $(document).ready(function() {

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

// });

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



</script>



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

   

</div>



<!-- Assignment Modal -->

<div class="modal fade" id="assignmentModal" tabindex="-1" role="dialog" aria-labelledby="assignmentModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="assignmentModalLabel">Project Assignment</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="form-group">

                    <label>Select Users</label>

                    <div id="user-checkbox-list" class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">

                        <!-- Checkboxes will be populated dynamically -->

                    </div>

                </div>

                <div class="form-group">

                    <label>Selected Users</label>

                    <div id="selected-users" class="border rounded p-2 min-height-50" style="min-height: 50px;">

                        <!-- Selected users will appear here as tags -->

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                <button type="button" class="btn btn-primary" id="save-assignment">Save Assignment</button>

            </div>

        </div>

    </div>

</div>



<!-- Compliance Modal -->

<div class="modal fade" id="complianceModal" tabindex="-1" role="dialog" aria-labelledby="complianceModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="complianceModalLabel">Add Compliance</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="editor-toolbar mb-2">

                    <button type="button" class="btn btn-sm btn-light modal-answer-toolbar" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>

                    <button type="button" class="btn btn-sm btn-light modal-answer-toolbar" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>

                    <button type="button" class="btn btn-sm btn-light modal-answer-toolbar" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>

                </div>

                <div id="modal-answer-editor" class="form-control" contenteditable="true" style="min-height: 150px; overflow-y: auto;"></div>

                <div id="char-count" class="text-muted mt-2" style="font-size: 0.875rem;">Characters remaining: 10000</div>

                <div id="char-error" class="text-danger mt-2" style="display: none;">Your answer is too long. Maximum 10000 characters allowed.</div>

                <input type="hidden" id="modal-qna-id">

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                <button type="button" class="btn btn-primary" id="modal-submit-answer">Submit Answer</button>

            </div>

        </div>

    </div>

</div>


<!-- ---attachment tab--- -->

<div class="modal fade" id="attachmentsModal" tabindex="-1" role="dialog" aria-labelledby="attachmentsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="attachmentForm" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="attachmentsModalLabel">Add Attachment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="attachment_name">Attachment Name</label>
            <input type="text" class="form-control" id="attachment_name" name="attachment_name" required />
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
          </div>

          <div class="form-group">
            <label for="attachment">Choose File</label>
            <input type="file" class="form-control-file" id="attachment" name="attachment" required accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" />
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </div>
    </form>
  </div>
</div>




<script>

// Mark as Done functionality

$(document).ready(function() {

    $('#markAsDoneBtn').click(function() {

        if (confirm('Are you sure you want to mark this project as done?')) {

            $.ajax({

                url: '<?php echo API_URL; ?>mark-done',

                type: 'POST',

                contentType: 'application/json',

                data: JSON.stringify({

                    access_token: '<?php echo $_SESSION['access_token']; ?>',

                    project_id: <?php echo $project_id; ?>

                }),

                success: function(response) {

                    if (response.is_successful === '1') {

                        alert('Project marked as done successfully!');

                        location.reload(); // Reload to show updated status

                    } else {

                        alert('Error: ' + (response.errors || 'Unknown error occurred'));

                    }

                },

                error: function() {

                    alert('Error: Could not connect to the server');

                }

            });

        }

    });

});

$('#attachmentForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this); // Create FormData from the form
    formData.append('access_token', '<?php echo $_SESSION['access_token']; ?>');
    formData.append('project_id', '<?php echo $project_id; ?>');
    formData.append('attachment', $('#attachment')[0].files[0]);

    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...');

    $.ajax({
        url: '<?php echo API_URL; ?>attachment-add',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.is_successful === "1") {
                $('#attachmentsModal').modal('hide');
                $('#attachmentForm')[0].reset();
                loadProjectAttachments();
                $(document).Toasts('create', {

class: 'bg-success',

title: 'Success',

body: response.success_message || "Attachment uploaded successfully!",

autohide: true,

delay: 3000

}); 

            } else {
                $(document).Toasts('create', {

class: 'bg-danger',

title: 'Error',

body: response.errors || "Upload failed. Please try again.",

autohide: true,

delay: 3000

}); 
            }
        },
        error: function(xhr, status, error) {
            console.error('Upload error:', error);
            alert("Something went wrong while uploading the attachment. Please try again.");
        },
        complete: function() {
            // Re-enable the button
            submitBtn.prop('disabled', false).html(originalBtnText);
        }
    });
});


// Character count validation for modal answer editor

$('#modal-answer-editor').on('input', function() {

    const maxLength = 10000;

    const currentLength = $(this).text().length;

    const remaining = maxLength - currentLength;

    

    $('#char-count').text(`Characters remaining: ${remaining}`);

    

    if (currentLength > maxLength) {

        $('#char-error').show();

        $('#modal-submit-answer').prop('disabled', true);

    } else {

        $('#char-error').hide();

        $('#modal-submit-answer').prop('disabled', false);

    }

});

loadProjectAttachments();
function loadProjectAttachments() {
    $.ajax({
        url: '<?php echo API_URL; ?>project-attachments',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: '<?php echo $_SESSION['access_token']; ?>',
            project_id: '<?php echo $project_id; ?>'
        }),
        success: function(response) {
            try {
                if (response.is_successful === "1" && response.success_message) {
                    const attachments = response.success_message.attachments || [];
                    const totalAttachments = response.success_message.total_attachments || 0;

                    // Clear existing table rows
                    $('#attachments-table-body').empty();

                    if (attachments.length === 0) {
                        $('#attachments-table-body').html('<tr><td colspan="5">No attachments found.</td></tr>');
                        return;
                    }

                    // Helper to choose icon based on extension
                    function iconByExt(path){
                        const ext = path.split('.').pop().toLowerCase().split('?')[0];
                        if(['pdf'].includes(ext)) return 'fas fa-file-pdf text-danger';
                        if(['doc','docx'].includes(ext)) return 'fas fa-file-word text-primary';
                        if(['xls','xlsx','csv'].includes(ext)) return 'fas fa-file-excel text-success';
                        if(['png','jpg','jpeg','gif','bmp','webp'].includes(ext)) return 'fas fa-file-image text-info';
                        return 'fas fa-file text-secondary';
                    }

                    // Create and append each attachment as a table row
                    attachments.forEach(function(attachment) {
                        const icon = iconByExt(attachment.file_path);
                        const attachmentHtml = `
    <tr>
        <td>${attachment.attachment_name}</td>
        <td>${attachment.description}</td>
        <td>
            <a href="${attachment.file_path}" target="_blank"><i class="${icon} fa-2x"></i></a>
        </td>
        <td>
           <button class="btn btn-danger btn-sm" onclick="deleteAttachment(${attachment.attachment_id})"><i class="fas fa-trash"> </i> Delete </button>
        </td>
    </tr>
`;
                        $('#attachments-table-body').append(attachmentHtml);
                    });

                    $('#attachmentsCount').text(totalAttachments);
                } else {
                    console.error('Failed to load attachments:', response.errors || 'Unknown error');
                }
            } catch (error) {
                console.error('Error processing attachments:', error);
            }
        }
    });
}

function deleteAttachment(attachmentId) {
    if (!confirm("Are you sure you want to delete this attachment?")) return;

    $.ajax({
        url: '<?php echo API_URL; ?>attachment-delete',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: '<?php echo $_SESSION['access_token']; ?>',
            attachment_id: attachmentId
        }),
        success: function(response) {
            if (response.is_successful === "1") {
                $(document).Toasts('create', {

                    class: 'bg-success',

                    title: 'Success',

                    body: 'Attachment deleted successfully!',

                    autohide: true,

                    delay: 3000

                });
                loadProjectAttachments(); // Reload the table
            } else {
                $(document).Toasts('create', {

                    class: 'bg-danger',

                    title: 'Error',

                    body: 'Failed to delete attachment: ' + (response.errors || "Unknown error"),

                    autohide: true,

                    delay: 3000

                });
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", error);
            $(document).Toasts('create', {

                class: 'bg-danger',

                title: 'Error',

                body: 'An error occurred while deleting the attachment.',

                autohide: true,

                delay: 3000

            });
        }
    });
}


function loadProjectTypes() {

    console.log('Loading project types...');

    $.ajax({

        url: '<?php echo API_URL; ?>template-listing',

        type: 'POST',

        contentType: 'application/json',

        data: JSON.stringify({

            access_token: "<?php echo $_SESSION['access_token']; ?>"

        }),

        success: function(response) {

            console.log('Project types API response:', response);

            if (response.is_successful === "1" && response.data) {

                // Save the current selection if any

                var currentSelection = $('#project_type_id').val();

                

                let options = '<option value="">Select Project Type</option>';

                response.data.forEach(function(type) {

                    options += `<option value="${type.project_type_id}">${type.project_type_name}</option>`;

                });

                $('#project_type_id').html(options);

                

                // Restore selection if we had one

                if (currentSelection) {

                    $('#project_type_id').val(currentSelection);

                }

                

                // If we have stored project type data from the API, use it

                if (window.projectTypeData && window.projectTypeData.id) {

                    $('#project_type_id').val(window.projectTypeData.id);

                }

            } else {

                console.error('Failed to load project types:', response);

                $(document).Toasts('create', {

                    class: 'bg-warning',

                    title: 'Warning',

                    body: 'Unable to load project types. Please try refreshing the page.',

                    autohide: true,

                    delay: 3000

                });

            }

        },

        error: function(error) {

            console.error('Error loading project types:', error);

            $(document).Toasts('create', {

                class: 'bg-danger',

                title: 'Error',

                body: 'Failed to load project types. Please check your connection and try again.',

                autohide: true,

                delay: 3000

            });

        }

    });

}



</script>

<script>
$(document).ready(function () {
    loadUsersToDropdown();
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
</script>


<?php include 'common/footer.php'; ?>