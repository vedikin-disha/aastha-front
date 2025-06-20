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

                <button type="button" class="btn btn-sm btn-light" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="strikeThrough" title="Strike through"><i class="fas fa-strikethrough"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="insertUnorderedList" title="Bullet list"><i class="fas fa-list-ul"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="insertOrderedList" title="Numbered list"><i class="fas fa-list-ol"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="createLink" title="Insert link"><i class="fas fa-link"></i></button>

                

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

                        <label for="answer_dept">Compliance Department</label>

                        <select class="form-control" id="answer_dept" name="answer_dept">

                            <option value="">Select Department</option>

                            <!-- Departments will be loaded from API -->

                        </select>

                    </div>

                </div>

            </div>

            <div class="text-right">

                <button type="button" class="btn btn-secondary mr-2" id="cancel-comment">Cancel</button>

                <button type="button" class="btn btn-primary" id="submit-qna">Post Comment</button>

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


<script>
// $(document).ready(function() {
    
    // Function to load departments from API

    function loadDepartments() {

        $.ajax({

            url: '<?php echo API_URL; ?>department',

            type: 'POST',

            contentType: 'application/json',

            data: JSON.stringify({

                access_token: '<?php echo $_SESSION["access_token"]; ?>'

            }),

            success: function(response) {

                console.log('Department API response:', response);

                

                if (response.is_successful === '1' && Array.isArray(response.data)) {

                    // Clear existing options except the first one

                    $('#question_dept option:not(:first)').remove();

                    $('#answer_dept option:not(:first)').remove();

                    

                    // Add departments to dropdowns

                    response.data.forEach(function(dept) {

                        const option = `<option value="${dept.dept_id}">${dept.dept_name}</option>`;

                        $('#question_dept').append(option);

                        $('#answer_dept').append(option);

                    });

                    

                    // Set default value for answer_dept (e.g., Drafting Department)

                    $('#answer_dept').val(2); // Assuming 2 is Drafting Department

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

                    const userDeptId = <?php echo isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : 'null'; ?>;

                    console.log(userDeptId,"userDeptId");

                    response.data.forEach(function(item) {

                        const showAnswerButton = userDeptId && parseInt(userDeptId) === parseInt(item.answer_dept);

                        const actionButton = showAnswerButton ? 

                            `<button class="btn btn-primary btn-sm add-answer-btn" style="background-color: #30b8b9;border:none;" data-qna-id="${item.qna_id}">

                                ${item.answer ? 'Update Answer' : 'Add Answer'}

                            </button>` : '';



                        const row = `

                            <tr>

                                <td>${item.question_by_name || ''} (${item.question_dept_name})</td>

                                <td>${item.question || ''}</td>

                                <td>${item.answer_by_name || 'Pending'} (${item.answer_dept_name})</td>

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

                const userDeptId = <?php echo isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : 'null'; ?>;

                

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

                const userDeptId = <?php echo isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : 'null'; ?>;

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

                question_dept: <?php echo isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : 'null'; ?>,

                answer_dept: answerDept

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