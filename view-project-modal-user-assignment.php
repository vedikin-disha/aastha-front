
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

</script>