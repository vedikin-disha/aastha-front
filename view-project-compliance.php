<div class="card shadow mb-4">

    <div class="card-header py-3">

        <div class="d-flex justify-content-between align-items-center">

            <h6 class="m-0 font-weight-bold text-primary" style="color:#30b8b9 !important;">Project  Compliances</h6>

            <button type="button" class="btn btn-sm btn-success" id="toggle-comment-form">

                <i class="fas fa-plus"></i> Add New Comment

            </button>

        </div>

    </div>

    <div id="comment-form-container" style="display: none;" class="p-3 border-bottom">

        <h5 class="mb-3">Add Comment</h5>

        <form id="qna-form">

            <div class="editor-toolbar mb-2">

               
            <button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>

<button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>

<button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>

                <button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="strikeThrough" title="Strike through"><i class="fas fa-strikethrough"></i></button>

                <button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="insertUnorderedList" title="Bullet list"><i class="fas fa-list-ul"></i></button>

                <button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="insertOrderedList" title="Numbered list"><i class="fas fa-list-ol"></i></button>

                <button type="button" class="btn btn-sm btn-light modal-question-toolbar" data-command="createLink" title="Insert link"><i class="fas fa-link"></i></button>

                

            </div>

            <div class="form-group">

                <div id="question-editor" class="form-control" contenteditable="true" style="min-height: 150px; overflow-y: auto;"></div>

                <input type="hidden" id="question-text" name="question">

            </div>

            <div class="row">

                <!-- <div class="col-md-6">

                    <div class="form-group">

                        <label for="question_dept">Comment Department</label>

                        <select class="form-control" id="question_dept" name="question_dept">

                            <option value="">Select Department</option>

                            

                        </select>

                    </div>

                </div> -->

                <div class="col-md-6">

                    <div class="form-group">

                        <label for="answer_dept">Assign To</label>

                        <select class="form-control select2-multiple" id="answer_dept" name="answer_dept[]" multiple="multiple" style="width: 100%;">

                            <!-- Users will be loaded from API -->

                        </select>

                    </div>

                </div>

            </div>

            <div class="text-right">

                <button type="button" class="btn btn-secondary mr-2" id="cancel-comment">Cancel</button>

                <button type="button" class="btn btn-primary" id="submit-qna" style="background-color: #30b8b9; border:1px solid #30b8b9;">Post Comment</button>

            </div>

        </form>

    </div>

    <div class="card-body">

        <div id="qna-loading" class="text-center py-5">

            <div class="spinner-border text-primary" role="status">

                <span class="sr-only">Loading...</span>

            </div>

            <p class="mt-2">Loading comments...</p>

        </div>

        <div id="qna-content" style="display: none;">

            <div class="table-responsive">

                <table class="table table-striped table-bordered" id="task-table">

                    <thead class="thead-dark">

                        <tr>

                            <th>Question By</th>

                            <th>Question</th>

                            <th>Answer By</th>

                            <th>Answer</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody id="qna-table-body">

                        <!-- QNA data will be loaded here -->

                    </tbody>

                </table>

            </div>

        </div>

        <div id="qna-error" class="alert alert-danger" style="display: none;">

            <i class="fas fa-exclamation-circle"></i> Error loading comments. Please try again later.

        </div>

    </div>

</div>



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


<script>

    $("document").ready(function() {

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
        

    });

</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Bootstrap Multiselect CSS & JS -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/css/bootstrap-multiselect.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.0/dist/js/bootstrap-multiselect.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- JS -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Initialize editor toolbar buttons
    $(document).on('click', '.editor-toolbar button', function(e) {
        e.preventDefault();
        const command = $(this).data('command');
        const editor = $('#question-editor').get(0);
        
        // Save the current selection
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        
        // Apply the command
        document.execCommand(command, false, null);
        
        // Restore focus to the editor
        editor.focus();
    });
    
    // Handle modal toolbar buttons
    $(document).on('click', '.modal-answer-toolbar', function(e) {
        e.preventDefault();
        const command = $(this).data('command');
        const editor = $('#modal-answer-editor').get(0);
        
        // Save the current selection
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        
        // Apply the command
        document.execCommand(command, false, null);
        
        // Restore focus to the editor
        editor.focus();
    });

    // $(document).ready(function() {

    // Function to load departments from API

    $(document).ready(function() {

        // Initialize Select2 with multiple selection

        $('.select2-multiple').select2({
            placeholder: 'Select Users',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        // Handle form submission
        $('#qna-form').on('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const question = $('#question-editor').html().trim();
            const selectedUsers = $('#answer_dept').val() || [];
            
            if (!question) {
                alert('Please enter a question');
                return;
            }
            
            if (selectedUsers.length === 0) {
                alert('Please select at least one user');
                return;
            }
            
            // Prepare the request data
            const requestData = {
                access_token: '<?php echo $_SESSION["access_token"]; ?>',
                project_id: '<?php echo $project_id; ?>',
                question: question,
                question_to: selectedUsers
            };
            
            console.log('Submitting QnA:', requestData);
            
            // Show loading state
            const submitBtn = $('#submit-qna');
            const originalBtnText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
            
            // Make API request
            $.ajax({
                url: '<?php echo API_URL; ?>qna_add',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function(response) {
                    console.log('QnA submission response:', response);
                    if (response.is_successful === '1') {
                        alert('Comment posted successfully!');
                        $('#qna-form')[0].reset();
                        $('#question-editor').html('');
                        $('#answer_dept').val(null).trigger('change');
                        loadProjectQnA(); // Reload QnA list
                    } else {
                        alert('Failed to post comment: ' + (response.errors || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting QnA:', error);
                    alert('Error submitting comment. Please try again.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            });
        });

    });

    function loadDepartments() {

        $.ajax({

            url: '<?php echo API_URL; ?>user',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION["access_token"]; ?>'

            }),

            success: function(response) {

                console.log('Department API response:', response);

                // console.log(response.data);

                if (response.is_successful === '1' && Array.isArray(response.data)) {

                    // Clear existing options except the first one

                    $('#question_dept option:not(:first)').remove();

                    $('#answer_dept option:not(:first)').remove();

                    // Add departments to dropdowns

                    response.data.forEach(function(user) {

                        const option = new Option(user.emp_name, user.emp_id, false, false);

                        $('#answer_dept').append(option);

                    });

                    // Refresh Select2 to update the dropdown with new options

                    $('#answer_dept').trigger('change');
                    // Set default value for answer_dept (e.g., Drafting Department)

                    $('#answer_dept').val(); // Assuming 2 is Drafting Department

                } else {

                    console.error('Failed to load departments:', response.errors || 'Unknown error');

                }

            },

            error: function(xhr, status, error) {

                console.error('Department API Error:', error);

            }

        });

    }


    // Function to load project QnA data from API

    function loadProjectQnA() {

        // Show loading state

        $('#qna-loading').show();

        $('#qna-content').hide();

        $('#qna-error').hide();



        // Make API request

        $.ajax({

            url: '<?php echo API_URL; ?>qna-edit',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

                project_id: '<?php echo $project_id; ?>'

            }),

            success: function(response) {

                console.log('QnA API response:', response);

                

                if (response.is_successful === '1' && response.data) {

                    const tableBody = $('#qna-table-body');

                    tableBody.empty();

                    

                    // Process each QnA item

                    const userDeptId = <?php echo isset($_SESSION['emp_id']) ? $_SESSION['emp_id'] : ''; ?>;

                    console.log(userDeptId,"userDeptId");

                    response.data.forEach(function(item) {
                        // Check if user's department is in question_to array
                        const isUserInQuestionTo = userDeptId && item.question_to && item.question_to.includes(parseInt(userDeptId));
                        const showAnswerButton = isUserInQuestionTo || (userDeptId && parseInt(userDeptId) === parseInt(item.answer_dept));

                        const actionButton = showAnswerButton ? 
                            `<button class="btn btn-primary btn-sm add-answer-btn" style="background-color: #30b8b9;border:none;" data-qna-id="${item.qna_id}">
                                ${item.answer ? 'Update Answer' : 'Add Answer'}
                            </button>` : '';



                        const row = `

                            <tr>

                                <td>${item.question_by_name || ''} (${item.question_dept_name})</td>

                                <td>${item.question || ''}</td>

                                <td>${item.answer_by_name || ''} (${item.answer_dept_name || 'Pending'})</td>

                                <td>${item.answer || 'No answer yet'}</td>

                                <td>${actionButton}</td>

                            </tr>

                        `;

                        tableBody.append(row);

                    });



                    // Show content container

                    $('#qna-loading').hide();

                    $('#qna-content').show();



                    sessionStorage.setItem('activeTab', '#comments');



        // Reload the page

        window.location.href = window.location.pathname + '?id=<?php echo base64_encode($project_id); ?>#comments';

                } else {

                    // Show error message

                    $('#qna-error').text('Failed to load questions: ' + (response.errors || 'No data available'));

                    $('#qna-error').show();

                    $('#qna-loading').hide();

                }

            },

            error: function(xhr, status, error) {

                console.error('Error loading QnA:', error);

                $('#qna-loading').hide();

                $('#qna-error').text('Failed to load questions: ' + error);

                $('#qna-error').show();

            }

        });

        }



        // Function to display QnA data

        function displayQnA(qnaItems) {

        if (!qnaItems || qnaItems.length === 0) {

            $('#questions-container').html('<div class="alert alert-info">No comments have been added for this project yet.</div>');

            $('#answers-container').html('<div class="alert alert-info">No compliances available yet.</div>');

            $('#qna-content').show();

            return;

        }



        // Sort QnA items by date (newest first)

        qnaItems.sort(function(a, b) {

            return new Date(b.created_at) - new Date(a.created_at);

        });



        // Create HTML for questions and answers

        let questionsHtml = '';

        let answersHtml = '';



        // Group QnA items by qna_id to match questions with their answers

        const qnaMap = {};



        // First, organize all items by their qna_id

        qnaItems.forEach(function(item) {

            if (item.qna_id) {

                qnaMap[item.qna_id] = item;

            }

        });



        // Now generate the HTML for each QnA pair

        Object.values(qnaMap).forEach(function(item) {

            // Format the question for the left column

            questionsHtml += `

                <div class="card mb-3" id="question-${item.qna_id}">

                    <div class="card-body">

                        <div>${item.question_by_name}</div>

                        <div class="card-text" style="white-space: pre-wrap;">${item.question}</div>

                    

                    </div>

                </div>

            `;

            

            // Format the answer for the right column if it exists

            if (item.answer && item.answer.trim() !== '') {

                // Get the user's department ID from session storage

                const userDeptId = <?php echo isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : ''; ?>;

                

                // Check if this answer belongs to the user's department

                if (userDeptId && parseInt(userDeptId) === parseInt(item.answer_dept)) {

                    // If the user's department matches, show the answer with an update button

                    answersHtml += `

                        <div class="card mb-3" id="answer-${item.qna_id}">

                            <div class="card-body">

                            <div>${item.answer_by_name || 'Pending'}</div>

                                <div class="card-text mb-3" style="white-space: pre-wrap;">${item.answer}</div>

                                <div class="text-right">

                                    <button type="button" class="btn btn-primary btn-sm update-compliance-btn" data-qna-id="${item.qna_id}" data-answer="${encodeURIComponent(item.answer)}">Update Compliance</button>

                                </div>

                            </div>

                        </div>

                    `;

                } else {

                    // If it's not the user's department, just show the answer

                    answersHtml += `

                        <div class="card mb-3" id="answer-${item.qna_id}">

                            <div class="card-body">

                                <div>${item.answer_by_name || 'Pending'}</div>

                                <div class="card-text" style="white-space: pre-wrap;">${item.answer}</div>

                            </div>

                        </div>

                    `;

                }

            } else {

                // Get the user's department ID from session storage

                const userDeptId = <?php echo isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : ''; ?>;

                console.log(userDeptId,"userDeptId");

                console.log(item.answer_dept,"item.answer_dept");

                

                // Check if this question is assigned to the user's department

                if (userDeptId && parseInt(userDeptId) === parseInt(item.answer_dept)) {

                    // If the user's department matches, show the appropriate UI

                    answersHtml += `

                        <div class="card mb-3" id="answer-${item.qna_id}">

                            <div class="card-body text-center">

                                <h6 class="mb-3">Add Compliance</h6>

                                <button type="button" class="btn btn-primary add-compliance-btn" data-qna-id="${item.qna_id}">Add Compliance</button>

                            </div>

                        </div>

                    `;

                } else {

                    // If there's no answer and it's not assigned to the user's department, add an empty placeholder card

                    answersHtml += `

                        <div class="card mb-3" id="answer-${item.qna_id}">

                            <div class="card-body text-center text-muted">

                                <p>No compliance available yet</p>

                            </div>

                        </div>

                    `;

                }

            }

        });



        // Update the content containers

        if (questionsHtml) {

            $('#questions-container').html(questionsHtml);

        } else {

            $('#questions-container').html('<div class="alert alert-info">No comments have been added for this project yet.</div>');

        }



        if (answersHtml) {

            $('#answers-container').html(answersHtml);

        } else {

            $('#answers-container').html('<div class="alert alert-info">No compliances available yet.</div>');

        }



        // Show the content

        $('#qna-content').show();



        // Ensure matching heights for question-answer pairs

        setTimeout(function() {

            Object.keys(qnaMap).forEach(function(qnaId) {

                const questionCard = $(`#question-${qnaId}`);

                const answerCard = $(`#answer-${qnaId}`);

                const maxHeight = Math.max(questionCard.height(), answerCard.height());

                questionCard.height(maxHeight);

                answerCard.height(maxHeight);

            });

        }, 100);

    }


    // Initialize answer editor toolbar buttons

    $(document).on('click', '.answer-toolbar', function() {

        const command = $(this).data('command');

        const qnaId = $(this).data('qna-id');



        // Focus on the specific answer editor

        const editorId = `answer-editor-${qnaId}`;

        $(`#${editorId}`).focus();



        // Execute the command

        if (command === 'createLink') {

            const url = prompt('Enter the link URL');

            if (url) {

                document.execCommand(command, false, url);

            }

        } else if (command === 'insertImage') {

            const url = prompt('Enter the image URL');

            if (url) {

                document.execCommand(command, false, url);

            }

        } else {

            document.execCommand(command, false, null);

        }

        });



        // Handle Add/Update Answer button click

        $(document).on('click', '.add-answer-btn', function() {

        const qnaId = $(this).data('qna-id');

        $('#modal-qna-id').val(qnaId);

        $('#complianceModal').modal('show');

        });



        // Handle Add Compliance button click

        $(document).on('click', '.add-compliance-btn', function() {

        const qnaId = $(this).data('qna-id');



        // Set the QnA ID in the modal

        $('#modal-qna-id').val(qnaId);



        // Clear the editor content

        $('#modal-answer-editor').html('');



        // Update modal title

        $('#complianceModalLabel').text('Add Compliance');



        // Show the modal

        $('#complianceModal').modal('show');



        // Focus on the editor after modal is shown

        $('#complianceModal').on('shown.bs.modal', function() {

            $('#modal-answer-editor').focus();

        });

        });



        // Handle Update Compliance button click

        $(document).on('click', '.update-compliance-btn', function() {

        const qnaId = $(this).data('qna-id');

        const answer = decodeURIComponent($(this).data('answer'));



        // Set the QnA ID in the modal

        $('#modal-qna-id').val(qnaId);



        // Set the existing answer in the editor

        $('#modal-answer-editor').html(answer);



        // Update modal title

        $('#complianceModalLabel').text('Update Compliance');



        // Show the modal

        $('#complianceModal').modal('show');



        // Focus on the editor after modal is shown

        $('#complianceModal').on('shown.bs.modal', function() {

            $('#modal-answer-editor').focus();

        });

        });


        $(document).on('click', '.modal-question-toolbar', function(e) {
        e.preventDefault();
        const command = $(this).data('command');
        const editor = $('#question-editor').get(0);
        
        // Save the current selection
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        
        // Apply the command
        document.execCommand(command, false, null);
        
        // Restore focus to the editor
        editor.focus();
    });



        // Initialize modal editor toolbar

        $(document).on('click', '.modal-answer-toolbar', function() {

        const command = $(this).data('command');



        // Focus on the editor

        $('#modal-answer-editor').focus();



        // Execute the command

        if (command === 'createLink') {

            const url = prompt('Enter the link URL');

            if (url) {

                document.execCommand(command, false, url);

            }

        } else if (command === 'insertImage') {

            const url = prompt('Enter the image URL');

            if (url) {

                document.execCommand(command, false, url);

            }

        } else {

            document.execCommand(command, false, null);

        }

        });



        // Handle modal submit button click

        $('#modal-submit-answer').on('click', function() {

        const qnaId = $('#modal-qna-id').val();

        const answerHtml = $('#modal-answer-editor').html();



        // Validate input

        if (!answerHtml.trim()) {

            alert('Please enter a compliance');

            return;

        }



        // Get the submit button reference

        const submitBtn = $(this);

        const originalBtnText = submitBtn.html();



        // Disable button and show loading state

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');



        // Make API request to update answer

        $.ajax({

            url: '<?php echo API_URL; ?>qna-update',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

                qna_id: qnaId,

                answer: answerHtml,

                answer_by: <?php echo $_SESSION['emp_id']; ?>

            }),

            success: function(response) {

                console.log('Answer Update API response:', response);

                

                // Reset button

                submitBtn.prop('disabled', false).html(originalBtnText);

                

                if (response.is_successful === '1') {

                    // Hide the modal

                    $('#complianceModal').modal('hide');

                    

                    // Show success message

                    const successAlert = $('<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' +

                        '<i class="fas fa-check-circle"></i> Compliance submitted successfully!' +

                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +

                        '<span aria-hidden="true">&times;</span></button></div>');

                    

                    $('#qna-content').before(successAlert);

                    $('.alert').remove();

                    

                    // Reload QnA data after a short delay

                    

                        setTimeout(function() {

                        //refresh the page and open the project compleance tab

                        sessionStorage.setItem('activeTab', '#comments');



        // Reload the page

        window.location.href = window.location.pathname + '?id=<?php echo base64_encode($project_id); ?>#comments';

                            loadProjectQnA();   

                        

                    }, 2000);

                } else {

                    // Show error message

                    alert('Error submitting compliance: ' + (response.errors || 'Unknown error'));

                }

            },

            error: function(xhr, status, error) {

                console.error('Answer Update API Error:', error);

                

                // Reset button

                submitBtn.prop('disabled', false).html(originalBtnText);

                

                // Show error message

                alert('Error submitting compliance: ' + error);

            }

        });

    });

        // Add character count elements after question editor

    if (!$('#question-char-count').length) {

        $('#question-editor').after(

            '<div id="question-char-count" class="text-muted mt-2" style="font-size: 0.875rem;">Characters remaining: 10000</div>' +

            '<div id="question-char-error" class="text-danger mt-2" style="display: none;">Your question is too long. Maximum 10000 characters allowed.</div>'

        );

        }



        // Character count validation for question editor

        $('#question-editor').on('input', function() {

        const maxLength = 10000;

        const currentLength = $(this).text().length;

        const remaining = maxLength - currentLength;



        $('#question-char-count').text(`Characters remaining: ${remaining}`);



        if (currentLength > maxLength) {

            $('#question-char-error').show();

            $('#submit-qna').prop('disabled', true);

        } else {

            $('#question-char-error').hide();

            $('#submit-qna').prop('disabled', false);

        }

        });



        // Initialize rich text editors for QnA form

        function initQnAEditors() {

        // Handle toolbar button clicks for question editor

        $('.editor-toolbar button[data-command]').on('click', function() {

            const command = $(this).data('command');

            

            if (command === 'createLink') {

                const url = prompt('Enter the link URL');

                if (url) {

                    document.execCommand(command, false, url);

                }

            } else if (command === 'insertImage') {

                const url = prompt('Enter the image URL');

                if (url) {

                    document.execCommand(command, false, url);

                }

            } else {

                document.execCommand(command, false, null);

            }

            

            // Focus back on the editor

            $('#question-editor').focus();

        });

        }



        // Toggle comment form visibility

        $('#toggle-comment-form').on('click', function() {

        $('#comment-form-container').slideToggle();

        initQnAEditors();

        $('#question-editor').focus();

        });



        // Cancel comment button

        $('#cancel-comment').on('click', function() {

        $('#comment-form-container').slideUp();

        $('#question-editor').html('');

    });

    // Handle QnA form submission

    $('#submit-qna').on('click', function() {

// Get form data

        const questionHtml = $('#question-editor').html();

        const questionDept = $('#question_dept').val();

        const answerDept = $('#answer_dept').val();



        // Validate inputs

        if (!questionHtml.trim()) {

            alert('Please enter a comment');

            return;

        }



        // Validate character limit

        const questionText = $('#question-editor').text();

        if (questionText.length > 10000) {

            alert('Your question exceeds the maximum character limit of 10000');

            return;

        }



        // Update hidden fields

        $('#question-text').val(questionHtml);



        // Get the submit button reference

        const submitBtn = $(this);

        const originalBtnText = submitBtn.html();



        // Disable button and show loading state

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');



        // Make API request to add QnA

        $.ajax({

            url: '<?php echo API_URL; ?>qna-add',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

                project_id: <?php echo $project_id; ?>,

                question: questionHtml,

                question_to: answerDept

            }),

            success: function(response) {

                console.log('Comment Add API response:', response);

                

                // Reset button

                submitBtn.prop('disabled', false).html(originalBtnText);

                

                if (response.is_successful === '1') {

                    // Hide the comment form

                    $('#comment-form-container').slideUp();

                    

                    // Clear form

                    $('#question-editor').html('');

                    

                    // Show success message

                    const successAlert = $('<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">' +

                        '<i class="fas fa-check-circle"></i> Comment added successfully!' +

                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +

                        '<span aria-hidden="true">&times;</span></button></div>');

                    

                    $('#qna-content').before(successAlert);

                    

                    // Reload QnA data



                    setTimeout(function() {

                        successAlert.alert('close');

                    }, 2000);



                    setTimeout(function() {

                        sessionStorage.setItem('activeTab', '#comments');



                    // Reload the page

                    window.location.href = window.location.pathname + '?id=<?php echo base64_encode($project_id); ?>#comments';

                    loadProjectQnA();   

                    }, 1000);

                

                } else {

                    // Show error message

                    alert('Error adding comment: ' + (response.errors || 'Unknown error'));

                }

            },

            error: function(xhr, status, error) {

                console.error('Comment Add API Error:', error);

                

                // Reset button

                submitBtn.prop('disabled', false).html(originalBtnText);

                

                // Show error message

                alert('Error adding comment: ' + error);

            }

        });

    });
// });
</script>