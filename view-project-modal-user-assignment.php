
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


<script>

    

    // call user API to get all users. for each user, it needs to be added as checkbox within user-checkbox-list div. Don't user loadUsers function as it is for dropdown. need to write similar new function here.

    function loadUsersForAssignment() {
        $.ajax({

        url: '<?php echo API_URL; ?>user',

        type: 'POST',

        contentType: 'application/json',

        data: JSON.stringify({

            access_token: '<?php echo $_SESSION['access_token']; ?>',
            dept_id: <?php echo $_SESSION['dept_id']; ?>

        }),

        success: function(response) {

            if (response.is_successful === '1' && response.data) {

                let userSelect = "";

                response.data.forEach(function(user) {

                    userSelect += `<div class="custom-control custom-checkbox mb-2">

                    <input type="checkbox" class="custom-control-input" id="user-${user.emp_id}" value="${user.emp_id}" data-name="${user.emp_name}">

                    <label class="custom-control-label" for="user-${user.emp_id}">${user.emp_name}</label>

                </div>`;

                });

                $('#user-checkbox-list').html(userSelect);

            }

        }

        });
    }

    

    

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

                   showToast(response.success_message);

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

//         window.openAssignmentModal = function(mode) {

// window.assignmentMode = mode; // Store the current mode (add/update)



// // Update modal title based on mode

// const modalTitle = mode === 'add' ? 'Add Project Assignment' : 'Update Project Assignment';

// $('#assignmentModalLabel').text(modalTitle);





// // Update save button text

// const saveButtonText = mode === 'add' ? 'Add Assignment' : 'Update Assignment';

// $('#save-assignment').text(saveButtonText);




// $('#assignmentModal').modal('show');

// };



// Load users into assignment modal when opened

$('#assignmentModal').on('show.bs.modal', function() {

const checkboxList = $('#user-checkbox-list');

const selectedUsers = $('#selected-users');

checkboxList.empty();

selectedUsers.empty();



// Create checkboxes from timeline user filter options

$('#timeline-user-filter option').each(function() {

if ($(this).val()) { // Skip the "All Users" option

    const userId = $(this).val();

    const userName = $(this).text();

    

    const checkbox = `

        <div class="custom-control custom-checkbox mb-2">

            <input type="checkbox" class="custom-control-input" id="user-${userId}" value="${userId}" data-name="${userName}">

            <label class="custom-control-label" for="user-${userId}">${userName}</label>

        </div>

    `;

    checkboxList.append(checkbox);

}

});

});

    });

    // Remove modal backdrop when assignment modal is shown
    $('#assignmentModal').on('show.bs.modal', function () {
        $('.modal-backdrop').css('opacity', '0');
    });
    
    // Clean up when modal is hidden
    $('#assignmentModal').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
    });

</script>

<style>
/* Remove modal backdrop */
.modal-backdrop.show {
    opacity: 0 !important;
}

/* Ensure modal is still clickable */
.modal {
    pointer-events: none;
}

.modal-dialog {
    pointer-events: auto;
}
</style>