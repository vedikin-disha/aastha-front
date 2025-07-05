<div class="tab-pane fade" id="timeline" role="tabpanel">

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>



  

    <button id="markAsDoneBtn" class="btn btn-success">

    <i class="fas fa-check"></i> Mark as Done

</button>

        <!-- <select id="timeline-user-filter" class="form-control">

            <option value="">All Users</option>

        </select> -->

    </div>

    <div class="btn-group">

        <!-- <button type="button" class="btn btn-primary" onclick="openAssignmentModal('add')">

            <i class="fas fa-user-plus"></i> Add Assignment

        </button> -->

        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>

            

        <button type="button" class="btn btn-info" style="background-color: #30b8b9; border:1px solid #30b8b9;" onclick="openAssignmentModal('update')">

            <i class="fas fa-user-edit"></i> Update Assignment

        </button>

        <?php endif; ?>

        

    </div>

</div>

<div class="comment-section card mt-4" style="display: none;">

<div class="card-header">

    <h5 class="mb-0">Add Comment</h5>

</div>

<div class="card-body">

    <form id="comment-form">

        <div class="editor-toolbar">

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="strikeThrough" title="Strike through"><i class="fas fa-strikethrough"></i></button>

            </div>

            

            <!-- Heading Styles -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="fas fa-heading"></i>

                </button>

                <div class="dropdown-menu">

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="h1">Heading 1</a>

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="h2">Heading 2</a>

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="h3">Heading 3</a>

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="h4">Heading 4</a>

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="h5">Heading 5</a>

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="h6">Heading 6</a>

                    <a class="dropdown-item" href="#" data-command="formatBlock" data-value="p">Paragraph</a>

                </div>

            </div>

            

            <!-- Font Family -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="fas fa-font"></i>

                </button>

                <div class="dropdown-menu">

                    <a class="dropdown-item" href="#" data-command="fontName" data-value="Arial">Arial</a>

                    <a class="dropdown-item" href="#" data-command="fontName" data-value="Times New Roman">Times New Roman</a>

                    <a class="dropdown-item" href="#" data-command="fontName" data-value="Courier New">Courier New</a>

                    <a class="dropdown-item" href="#" data-command="fontName" data-value="Georgia">Georgia</a>

                    <a class="dropdown-item" href="#" data-command="fontName" data-value="Tahoma">Tahoma</a>

                    <a class="dropdown-item" href="#" data-command="fontName" data-value="Verdana">Verdana</a>

                </div>

            </div>

            

            <!-- Text Color -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="fas fa-tint"></i>

                </button>

                <div class="dropdown-menu color-dropdown">

                    <div class="color-row">

                        <a href="#" class="color-item" style="background-color: #000000;" data-command="foreColor" data-value="#000000"></a>

                        <a href="#" class="color-item" style="background-color: #434343;" data-command="foreColor" data-value="#434343"></a>

                        <a href="#" class="color-item" style="background-color: #666666;" data-command="foreColor" data-value="#666666"></a>

                        <a href="#" class="color-item" style="background-color: #999999;" data-command="foreColor" data-value="#999999"></a>

                        <a href="#" class="color-item" style="background-color: #b7b7b7;" data-command="foreColor" data-value="#b7b7b7"></a>

                        <a href="#" class="color-item" style="background-color: #cccccc;" data-command="foreColor" data-value="#cccccc"></a>

                        <a href="#" class="color-item" style="background-color: #d9d9d9;" data-command="foreColor" data-value="#d9d9d9"></a>

                        <a href="#" class="color-item" style="background-color: #ffffff;" data-command="foreColor" data-value="#ffffff"></a>

                    </div>

                    <div class="color-row">

                        <a href="#" class="color-item" style="background-color: #980000;" data-command="foreColor" data-value="#980000"></a>

                        <a href="#" class="color-item" style="background-color: #ff0000;" data-command="foreColor" data-value="#ff0000"></a>

                        <a href="#" class="color-item" style="background-color: #ff9900;" data-command="foreColor" data-value="#ff9900"></a>

                        <a href="#" class="color-item" style="background-color: #ffff00;" data-command="foreColor" data-value="#ffff00"></a>

                        <a href="#" class="color-item" style="background-color: #00ff00;" data-command="foreColor" data-value="#00ff00"></a>

                        <a href="#" class="color-item" style="background-color: #00ffff;" data-command="foreColor" data-value="#00ffff"></a>

                        <a href="#" class="color-item" style="background-color: #0000ff;" data-command="foreColor" data-value="#0000ff"></a>

                        <a href="#" class="color-item" style="background-color: #9900ff;" data-command="foreColor" data-value="#9900ff"></a>

                    </div>

                </div>

            </div>

            

            <!-- Background Color -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="fas fa-fill-drip"></i>

                </button>

                <div class="dropdown-menu color-dropdown">

                    <div class="color-row">

                        <a href="#" class="color-item" style="background-color: #000000;" data-command="hiliteColor" data-value="#000000"></a>

                        <a href="#" class="color-item" style="background-color: #434343;" data-command="hiliteColor" data-value="#434343"></a>

                        <a href="#" class="color-item" style="background-color: #666666;" data-command="hiliteColor" data-value="#666666"></a>

                        <a href="#" class="color-item" style="background-color: #999999;" data-command="hiliteColor" data-value="#999999"></a>

                        <a href="#" class="color-item" style="background-color: #b7b7b7;" data-command="hiliteColor" data-value="#b7b7b7"></a>

                        <a href="#" class="color-item" style="background-color: #cccccc;" data-command="hiliteColor" data-value="#cccccc"></a>

                        <a href="#" class="color-item" style="background-color: #d9d9d9;" data-command="hiliteColor" data-value="#d9d9d9"></a>

                        <a href="#" class="color-item" style="background-color: #ffffff;" data-command="hiliteColor" data-value="#ffffff"></a>

                    </div>

                    <div class="color-row">

                        <a href="#" class="color-item" style="background-color: #980000;" data-command="hiliteColor" data-value="#980000"></a>

                        <a href="#" class="color-item" style="background-color: #ff0000;" data-command="hiliteColor" data-value="#ff0000"></a>

                        <a href="#" class="color-item" style="background-color: #ff9900;" data-command="hiliteColor" data-value="#ff9900"></a>

                        <a href="#" class="color-item" style="background-color: #ffff00;" data-command="hiliteColor" data-value="#ffff00"></a>

                        <a href="#" class="color-item" style="background-color: #00ff00;" data-command="hiliteColor" data-value="#00ff00"></a>

                        <a href="#" class="color-item" style="background-color: #00ffff;" data-command="hiliteColor" data-value="#00ffff"></a>

                        <a href="#" class="color-item" style="background-color: #0000ff;" data-command="hiliteColor" data-value="#0000ff"></a>

                        <a href="#" class="color-item" style="background-color: #9900ff;" data-command="hiliteColor" data-value="#9900ff"></a>

                    </div>

                </div>

            </div>

            

            <!-- Lists -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light" data-command="insertUnorderedList" title="Bullet list"><i class="fas fa-list-ul"></i></button>

                <button type="button" class="btn btn-sm btn-light" data-command="insertOrderedList" title="Numbered list"><i class="fas fa-list-ol"></i></button>

            </div>

            

            <!-- Table -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light" data-command="insertTable" title="Insert table"><i class="fas fa-table"></i></button>

            </div>

            

            <!-- Links and Images -->

            <div class="btn-group mr-2">

                <button type="button" class="btn btn-sm btn-light" data-command="createLink" title="Insert link"><i class="fas fa-link"></i></button>

                

            </div>

        </div>

        <div class="form-group mt-2">

            <div id="comment-editor" class="form-control" contenteditable="true" style="min-height: 100px; overflow-y: auto;"></div>

            <input type="hidden" id="comment-text" name="comment">

        </div>  

        <div class="form-group mt-2">

            <label for="attachment">Attachment (Optional)</label>
            <!-- ADDED ONE LINE  ALLOW  allowed_extensions = {
        # Documents
        '.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx',
        '.txt', '.csv',
        # Images
        '.jpg', '.jpeg', '.png', '.gif', '.bmp', '.tiff',
        # Archives
        '.zip', '.rar',
        # CAD
        '.dwg',
        # Videos
        '.mp4', '.mov', '.avi', '.wmv', '.flv', '.mkv', '.webm', '.mpeg', '.mpg', '.3gp'
    }  and allow 2 gb file size --> 
        <input type="hidden" name="allowed_extensions" value=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.jpg,.jpeg,.png,.gif,.bmp,.tiff,.zip,.rar,.dwg,.mp4,.mov,.avi,.wmv,.flv,.mkv,.webm,.mpeg,.mpg,.3gp">
        <input type="hidden" name="max_file_size" value="2">

            <input type="file" class="form-control-file" id="attachment" name="attachment" multiple>

        </div>

        <button type="submit" class="btn btn-primary float-right mt-2" style="background-color: #30b8b9; border:1px solid #30b8b9;">Post Comment</button>

        <!-- <button type="button" class="btn btn-primary float-right mt-2">Add Attachment</button> -->

    </form>

</div>

</div>



<div id="timeline-loading" style="display: none;">

    <div class="text-center">

        <div class="spinner-border text-primary" role="status">

            <span class="sr-only">Loading...</span>

        </div>

    </div>

</div>



<div id="timeline-error" class="alert alert-danger" style="display: none;"></div>



<div id="timeline-content" class="timeline"></div>


<script>


function loadProjectTimeline() {

    // Show loading state

    $('#timeline-content').hide();

    $('#timeline-error').hide();

    $('#timeline-loading').show();



    // Make API request to get project activity data

    $.ajax({

        url: '<?php echo API_URL; ?>activity-edit',

        type: 'POST',

        contentType: 'application/json',

        data: JSON.stringify({

            access_token: '<?php echo $_SESSION['access_token']; ?>',

            project_id: <?php echo $project_id; ?>,

            // śemp_id: $('#timeline-user-filter').val() || null

        }),

        success: function(response) {

            console.log('Timeline API response:', response);

            

            // Hide loading indicator

            $('#timeline-loading').hide();

            

            if (response.is_successful === '1' && Array.isArray(response.data)) {

                // Process and display timeline data

                displayTimeline(response.data);

                // Show comment section after timeline is loaded

                $('.comment-section').show();

            } else {

                // Show error message

                $('#timeline-error').text('Failed to load timeline data: ' + 

                    (response.errors || 'No activity data available'));

                $('#timeline-error').show();

            }

        },

        error: function(xhr, status, error) {

            // Hide loading indicator and show error

            $('#timeline-loading').hide();

            $('#timeline-error').text('Error loading timeline data: ' + error).show();

            console.error('API Error:', error);

        }

    });

}


// Initialize rich text editor

function initRichTextEditor() {

    // Handle toolbar button clicks

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

        } else if (command === 'insertTable') {

            showTableDialog();

        } else {

            document.execCommand(command, false, null);

        }

        

        // Focus back on the editor

        $('#comment-editor').focus();

    }); 



    // Handle dropdown menu items

    $('.editor-toolbar .dropdown-item[data-command]').on('click', function(e) {

        e.preventDefault();

        const command = $(this).data('command');

        const value = $(this).data('value');

        

        document.execCommand(command, false, value);

        

        // Focus back on the editor

        $('#comment-editor').focus();

    });



    // Handle color picker items

    $('.editor-toolbar .color-item').on('click', function(e) {

        e.preventDefault();

        const command = $(this).data('command');

        const value = $(this).data('value');

        

        document.execCommand(command, false, value);

        

        // Close dropdown

        $(this).closest('.dropdown-menu').prev().dropdown('toggle');

        

        // Focus back on the editor

        $('#comment-editor').focus();

    });



    // Update hidden input with HTML content when editor content changes

    $('#comment-editor').on('input', function() {

        $('#comment-text').val($(this).html());

    });

}



function displayTimeline(activities) {

    if (!activities || activities.length === 0) {

        $('#timeline-content').html('<div class="alert alert-info">No activity records found for this project.</div>');

        $('#timeline-content').show();

        return;

    }



    // Group activities by date

    const groupedActivities = groupActivitiesByDate(activities);



    // Build timeline HTML

    let timelineHtml = '';



    // Get all dates and sort them in reverse chronological order (newest first)

    const sortedDates = Object.keys(groupedActivities).sort(function(a, b) {

        // For dates in format "Wed, 28 May 2025"

        // Extract the day, month, and year parts

        const aMatch = a.match(/(\w+), (\d+) (\w+) (\d+)/);

        const bMatch = b.match(/(\w+), (\d+) (\w+) (\d+)/);

        

        if (!aMatch || !bMatch) return 0;

        

        const aDay = parseInt(aMatch[2]);

        const aMonth = getMonthNumber(aMatch[3]);

        const aYear = parseInt(aMatch[4]);

        

        const bDay = parseInt(bMatch[2]);

        const bMonth = getMonthNumber(bMatch[3]);

        const bYear = parseInt(bMatch[4]);

        

        // Compare years first

        if (aYear !== bYear) return aYear - bYear;

        // Then months

        if (aMonth !== bMonth) return aMonth - bMonth;

        // Then days

        return aDay - bDay;

    }).reverse(); // Reverse to get newest first



    // Loop through each date group in sorted order

    sortedDates.forEach(function(date) {

        const dateActivities = groupedActivities[date];

        const formattedDate = date; // Use the date as is since it's already formatted

        

        // Add date label

        timelineHtml += `

        <div class="time-label">

            <span class="bg-blue" >${formattedDate}</span>

        </div>`;

        

        // Loop through activities for this date

        dateActivities.forEach(function(activity) {

            const activityTime = formatTime(activity.activity_date);

            const activityIcon = getActivityIcon(activity.activity_type);

            const activityBgClass = getActivityBgClass(activity.activity_type);

            let activityMessage = '';

            

            // Handle assignment update and add activities

            if (activity.activity_type === 'assignment_update' || activity.activity_type === 'assignment_add') {

                const empNames = activity.emp_names ? activity.emp_names.join(', ') : 'Unknown';

                activityMessage = activity.activity_type === 'assignment_update' ?

                    `Updated project assignments for: ${empNames}` :

                    `Added new project assignments for: ${empNames}`;

            }

            

            // Generate attachments HTML if they exist

            const attachmentsHtml = activity.attachments ? generateAttachmentsHtml(activity.attachments) : '';

            

            timelineHtml += `

            <div>

                <i class="${activityIcon} ${activityBgClass}"></i>

                <div class="timeline-item">

                    <span class="time"><i class="fas fa-clock"></i> ${activityTime}</span>

                    <h3 class="timeline-header">

                        <a href="#">${activity.emp_name || 'System'}</a>   <span>${activity.activity_type.replace('_', ' ')}</span>

                    </h3>

                    ${activity.comment ? `<div class="timeline-body">${activity.comment}</div>` : ''}

                    ${activityMessage ? `<div class="timeline-body">${activityMessage}</div>` : ''}

                    ${attachmentsHtml}

                </div>

            </div>`;

        });

    });



    // Add end label

    timelineHtml += `

    <div>

        <i class="fas fa-clock bg-gray"></i>

    </div>`;



    // Update timeline content and show it

    $('#timeline-content').html(timelineHtml);

    $('#timeline-content').show();

}


    // Function to group activities by date

    function groupActivitiesByDate(activities) {

const grouped = {};



activities.forEach(function(activity) {

    // Extract just the date portion (e.g., "Wed, 28 May 2025" from "Wed, 28 May 2025 17:33:11 GMT")

    const dateParts = activity.activity_date.split(' ');

    // Take the first 4 parts which contain the day of week, day, month, and year

    const dateKey = dateParts.slice(0, 4).join(' ');

    

    if (!grouped[dateKey]) {

        grouped[dateKey] = [];

    }

    

    grouped[dateKey].push(activity);

});



return grouped;

}




    // Helper function to convert month name to month number (0-11)

    function getMonthNumber(monthName) {

const months = {

    'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,

    'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11

};

return months[monthName] || 0;

}




function getActivityIcon(activityType) {

switch(activityType) {

    case 'project_created':

        return 'fas fa-plus';

    case 'project_updated':

        return 'fas fa-edit';

    case 'question_asked':

        return 'fas fa-question';

    case 'task_created':

        return 'fas fa-tasks';

    case 'task_completed':

        return 'fas fa-check';

    case 'comment_added':

        return 'fas fa-comment';

    case 'assignment_update':

    case 'assignment_add':

        return 'fas fa-user-friends';

    default:

        return 'fas fa-info-circle';

}

}





function getActivityBgClass(activityType) {

switch(activityType) {

    case 'project_created':

        return 'bg-green';

    case 'project_updated':

        return 'bg-blue';

    case 'question_asked':

        return 'bg-yellow';

    case 'task_created':

        return 'bg-purple';

    case 'task_completed':

        return 'bg-success';

    case 'assignment_update':

        return 'bg-orange';

    case 'assignment_add':

        return 'bg-green';

    default:

        return 'bg-gray';

}

}


function generateAttachmentsHtml(attachments) {

if (!attachments || (!attachments.images?.length && !attachments.videos?.length && !attachments.others?.length)) {

    return '';

}



let html = '<div class="timeline-attachments mt-2">';



// Add images

if (attachments.images && attachments.images.length > 0) {

    html += '<div class="image-gallery">';

    attachments.images.forEach(imageUrl => {

        html += `

        <a href="${imageUrl}" data-fancybox="gallery" data-caption="Image">

            <img src="${imageUrl}" class="img-thumbnail m-1" style="width:200px; height:200px;" alt="Image">

        </a>`;

    });

    html += '</div>';

}



// Add videos

if (attachments.videos && attachments.videos.length > 0) {

    html += '<div class="video-gallery mt-2">';

    attachments.videos.forEach(videoUrl => {

        html += `

        <div class="video-wrapper m-1" style="display: inline-block; position: relative;">

            <video width="200" height="200" controls class="img-thumbnail">

                <source src="${videoUrl}" type="video/mp4">

                Your browser does not support the video tag.

            </video>

            <a href="${videoUrl}" class="video-play-icon" target="_blank" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 24px;">

                <i class="fas fa-play-circle"></i>

            </a>

        </div>`;

    });

    html += '</div>';

}



// Add other files

if (attachments.others && attachments.others.length > 0) {

    html += '<div class="other-files mt-2">';

    html += '<strong>Attachments:</strong><br>';

    attachments.others.forEach(fileUrl => {

        const fileName = fileUrl.split('/').pop();



        const extension = fileUrl.split('.').pop().toLowerCase();

        console.log("extension: ");

        console.log(extension);

        let iconClass = 'far fa-file-alt';

        let iconColor = "blue";

        switch (extension) {

            case 'pdf':

                iconClass = 'far fa-file-pdf';

                iconColor = "red";

                break;

            case 'xls':

            case 'xlsx':

                iconClass = 'far fa-file-excel';

                iconColor = "green";

                break;

            case 'doc':

            case 'docx':

                iconClass = 'far fa-file-word';

                iconColor = "blue";

                break;

            case 'ppt':

            case 'pptx':

                iconClass = 'far fa-file-powerpoint';

                iconColor = "orange";

                break;

            case 'dwg':

                iconClass = 'far fa-file-cad';

                iconColor = "purple";

                break;

        }

        html += `

        <div class="file-item" style="display:inline-block">

            <a href="${fileUrl}" target="_blank" class="text-primary" alt="${fileName}" title="${fileName}">

            <i class="${iconClass} file-icon" style="color:${iconColor}"></i>

            </a>

        </div>`;

    });

    html += '</div>';

}



html += '</div>';

return html;

}




    $(document).ready(function() {
    
/*


// Load users when page loads
loadUsers();
*/












    // Function to get icon for activity type

    

// }



    // Function to get background class for activity type

    if (typeof $.fancybox === 'undefined') {

        $('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />');

        $.getScript('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js')

            .done(function() {

                // Initialize Fancybox after it's loaded

                $(document).on('click', '[data-fancybox]', function() {

                    $('[data-fancybox]').fancybox({

                        buttons: [

                            'zoom',

                            'slideShow',

                            'fullScreen',

                            'download',

                            'thumbs',

                            'close'

                        ]

                    });

                });

            });

    }

    // Function to show table insertion dialog

    function showTableDialog() {

        // Create modal dialog for table insertion

        const tableDialog = $(`

            <div class="modal fade" id="tableInsertModal" tabindex="-1" role="dialog" aria-labelledby="tableInsertModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-sm" role="document">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title" id="tableInsertModalLabel">Insert Table</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                <span aria-hidden="true">&times;</span>

                            </button>

                        </div>

                        <div class="modal-body">

                            <div class="table-grid" id="tableGrid"></div>

                            <div class="table-size-display" id="tableSizeDisplay">0 x 0</div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                            <button type="button" class="btn btn-primary" id="insertTableBtn">Insert</button>

                        </div>

                    </div>

                </div>

            </div>

        `);



        // Append dialog to body

        $('body').append(tableDialog);



        // Generate table grid cells

        const tableGrid = $('#tableGrid');

        let selectedRows = 0;

        let selectedCols = 0;



        // Create a 6x6 grid

        for (let row = 0; row < 6; row++) {

            for (let col = 0; col < 6; col++) {

                const cell = $(`<div class="table-cell" data-row="${row}" data-col="${col}"></div>`);

                tableGrid.append(cell);

            }

        }



        // Handle cell hover to highlight grid

        $('.table-cell').on('mouseover', function() {

            const row = parseInt($(this).data('row'));

            const col = parseInt($(this).data('col'));

            

            selectedRows = row + 1;

            selectedCols = col + 1;

            

            // Update size display

            $('#tableSizeDisplay').text(`${selectedRows} x ${selectedCols}`);

            

            // Highlight cells

            $('.table-cell').each(function() {

                const cellRow = parseInt($(this).data('row'));

                const cellCol = parseInt($(this).data('col'));

                

                if (cellRow <= row && cellCol <= col) {

                    $(this).css('background-color', '#30b8b9');

                } else {

                    $(this).css('background-color', '#f8f9fa');

                }

            });

        });



        // Handle insert button click

        $('#insertTableBtn').on('click', function() {

            if (selectedRows > 0 && selectedCols > 0) {

                insertTable(selectedRows, selectedCols);

            }

            $('#tableInsertModal').modal('hide');

        });



        // Show the modal

        $('#tableInsertModal').modal('show');



        // Clean up when modal is hidden

        $('#tableInsertModal').on('hidden.bs.modal', function() {

            $(this).remove();

        });

    }



    // Function to insert a table into the editor

    function insertTable(rows, cols) {

        let tableHtml = '<table class="table table-bordered"><tbody>';



        for (let i = 0; i < rows; i++) {

            tableHtml += '<tr>';

            for (let j = 0; j < cols; j++) {

                tableHtml += '<td>&nbsp;</td>';

            }

            tableHtml += '</tr>';

        }



        tableHtml += '</tbody></table><p></p>';



        // Insert the table at cursor position

        document.execCommand('insertHTML', false, tableHtml);



        // Focus back on the editor

        $('#comment-editor').focus();

    }



    // Function to add a new comment directly to the timeline without reloading

    function addNewCommentToTimeline(commentData) {

        // Format the date for display

        const today = new Date();

        const dateStr = commentData.activity_date;

        const formattedDate = dateStr.split(' ').slice(0, 4).join(' '); // Extract "Thu, 29 May 2025" part

        const activityTime = formatTime(dateStr);



        // Get icon and background class for comment

        const activityIcon = getActivityIcon(commentData.activity_type);

        const activityBgClass = getActivityBgClass(commentData.activity_type);



        // Check if we already have this date in the timeline

        let dateGroup = $('#timeline-content').find(`.time-label span:contains("${formattedDate}")`).closest('.time-label');



        // If this date doesn't exist in the timeline yet, add it at the top

        if (dateGroup.length === 0) {

            const dateHtml = `

            <div class="time-label">

                <span class="bg-blue">${formattedDate}</span>

            </div>`;

            

            // Add at the beginning of the timeline

            $('#timeline-content').prepend(dateHtml);

            dateGroup = $('#timeline-content').find('.time-label').first();

        }



        // Create the comment HTML

        const commentHtml = `

            <div>

                <i class="${activityIcon} ${activityBgClass}"></i>

                <div class="timeline-item">

                    <span class="time"><i class="fas fa-clock"></i> ${activityTime}</span>

                    <h3 class="timeline-header">

                        <a href="#">${commentData.emp_name || 'System'}</a>   <span>${commentData.activity_type.replace('_', ' ')}</span>

                    </h3>

                    ${commentData.comment ? `<div class="timeline-body">${commentData.comment}</div>` : ''}

                </div>

            </div>`;



        // Insert the new comment right after the date label

        $(commentHtml).insertAfter(dateGroup);

    }



 $('#comment-form').on('submit', function (e) {
    e.preventDefault();

    const commentHtml = $('#comment-editor').html();
    $('#comment-text').val(commentHtml);
    const commentText = $('#comment-text').val().trim();
    const files = $('#attachment')[0].files;

    if (!commentText || commentText === '<br>' || commentText === '<div><br></div>') {
        alert('Please enter a comment before submitting.');
        return;
    }

    const $submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = $submitBtn.html();
    $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Posting...');

    const requestData = {
        access_token: '<?php echo $_SESSION['access_token']; ?>',
        project_id: <?php echo $project_id; ?>,
        activity_type: 'comment_added',
        comment: commentText
    };

    $.ajax({
        url: '<?php echo API_URL; ?>activity-add',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(requestData),
        success: function (response) {
            if (response.is_successful === '1') {
                const activityId = response.data.activity_id;

                
                // Start uploading files in background
                if (files.length > 0) {

                    // Show success popup immediately
                    showSuccessPopupAndRedirect("Uploading attachments in background. Do not close this tab/browser. <a href='view-project?id=<?php echo base64_encode($project_id); ?>' target='_blank'>It may take some time. In the mean time, you can continue to use the application. Click here to use application. </a>", '#timeline');

                    const uploadPromises = [];

                    for (let i = 0; i < files.length; i++) {
                        const formData = new FormData();
                        formData.append('activity_id', activityId);
                        formData.append('access_token', '<?php echo $_SESSION['access_token']; ?>');
                        formData.append('attachment', files[i]);

                        uploadPromises.push(
                            $.ajax({
                                url: '<?php echo API_URL; ?>attachment-add',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false
                            })
                        );
                    }

                    // When all uploads complete
                    Promise.all(uploadPromises)
                        .then(function () {
                            // Hide the popup first
                            hideSuccessPopup();
                            
                            // Show success toast after popup is hidden
                            showToast("Activity added successfully.", true);
                        })
                        .catch(function () {
                            // Hide the popup first
                            hideSuccessPopup();
                            
                            // Show error toast after popup is hidden
                            showToast("Some attachments failed to upload.", false);
                        });

                } else {
                    hideSuccessPopup();
                }

                resetForm();
            } else {
                showToast(response.errors || 'Failed to add comment', false);
                $submitBtn.prop('disabled', false).html(originalBtnText);
            }
        },
        error: function (xhr, status, error) {
            console.error('Comment API error:', error);
            showToast('Failed to add comment. Please try again.', false);
            $submitBtn.prop('disabled', false).html(originalBtnText);
        }
    });

    function resetForm() {
        $('#comment-editor').html('');
        $('#comment-text').val('');
        $('#attachment').val('');
        $submitBtn.prop('disabled', false).html(originalBtnText);
        loadProjectTimeline();
    }
});

// Show popup immediately after comment added
// function showSuccessPopupAndRedirect(message, redirectTarget) {
//     $('#success-popup .popup-message').text(message);
//     $('#success-popup').modal('show');

//     // Optional: redirect if needed
//     // setTimeout(() => {
//     //     window.location.href = redirectTarget;
//     // }, 3000);
// }

// Hide popup when uploads are done
function hideSuccessPopup() {
    $('#successModal').modal('hide');
}


    // Function to update the file list display

    function updateFileList() {

        const fileInput = document.getElementById('attachment');

        const fileList = document.getElementById('file-list');

        fileList.innerHTML = '';



        if (fileInput.files.length > 0) {

            const list = document.createElement('ul');

            list.className = 'list-unstyled';

            

            for (let i = 0; i < fileInput.files.length; i++) {

                const file = fileInput.files[i];

                const listItem = document.createElement('li');

                listItem.className = 'd-flex justify-content-between align-items-center mb-1';

                

                const fileInfo = document.createElement('span');

                fileInfo.innerHTML = `

                    <i class="fas fa-file-alt mr-2"></i>

                    ${file.name} 

                    <small class="text-muted ml-2">(${(file.size / 1024).toFixed(2)} KB)</small>

                `;

                

                const removeBtn = document.createElement('button');

                removeBtn.className = 'btn btn-sm btn-outline-danger';

                removeBtn.innerHTML = '<i class="fas fa-times"></i>';

                removeBtn.onclick = function(e) {

                    e.preventDefault();

                    const newFiles = Array.from(fileInput.files).filter((_, index) => index !== i);

                    

                    // Create new DataTransfer to update the file input

                    const dataTransfer = new DataTransfer();

                    newFiles.forEach(file => dataTransfer.items.add(file));

                    fileInput.files = dataTransfer.files;

                    

                    // Update the file list display

                    updateFileList();

                };

                

                listItem.appendChild(fileInfo);

                listItem.appendChild(removeBtn);

                list.appendChild(listItem);

            }

            

            fileList.appendChild(list);

            fileList.style.display = 'block';

        } else {

            fileList.style.display = 'none';

        }

    }



    // Show alert message

    function showSuccessPopupAndRedirect(message, redirectUrl) {
        // Remove any existing modals first
        $('.modal').remove();
        
        // Create modal HTML
        const modalHtml = `
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>`;
    
        // Add modal to body
        $('body').append(modalHtml);
        
        // Initialize and show modal
        const $modal = $('#successModal');
        $modal.modal({backdrop: 'static', keyboard: false});
        
        // Handle modal close events
        $modal.on('hidden.bs.modal', function() {
            $(this).remove();
            if (redirectUrl) {
                window.open(redirectUrl, '_blank');
            }
        });
        
        // Show the modal
        $modal.modal('show');
    }

    function showAlert(message, type = 'success') {
        const alertHtml = `

            <div class="alert alert-${type} alert-dismissible fade show" role="alert">

                ${message}

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

        `;



        // Prepend the alert to the form

        $('.comment-section .card-body').prepend(alertHtml);



        // Auto-remove the alert after 5 seconds

        setTimeout(() => {

            $('.alert').alert('close');

        }, 5000);

    }



    // Update file list when files are selected

    $('#attachment').on('change', updateFileList);


    // Function to open assignment modal

    window.openAssignmentModal = function(mode) {

            window.assignmentMode = mode; // Store the current mode (add/update)



            // Update modal title based on mode

            const modalTitle = mode === 'add' ? 'Add Project Assignment' : 'Update Project Assignment';

            $('#assignmentModalLabel').text(modalTitle);



            // Update save button text

            const saveButtonText = mode === 'add' ? 'Add Assignment' : 'Update Assignment';

            $('#save-assignment').text(saveButtonText);

            loadUsersForAssignment();

            // Show the modal

            $('#assignmentModal').modal('show');

        };



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


    $('#markAsDoneBtn').click(function () {
    if (confirm('Are you sure you want to mark this project as done?')) {
        $.ajax({
            url: '<?php echo API_URL; ?>mark-done',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION['access_token']; ?>',
                project_id: <?php echo $project_id; ?>
            }),
            success: function (response) {
                if (response.is_successful === '1') {
                    console.log(response.success_message);
                    showToast(response.success_message, true);
                    location.reload(); // Reload to show updated status
                } else {
                    // Check deeper error message if success_message is empty
                    const errorMsg = response.error_message || 
                                     (response.errors && response.errors.error) || 
                                     'An unknown error occurred.';
                    showToast('Error: ' + errorMsg, false);
                }
            },
            error: function (xhr) {
                try {
                    const response = xhr.responseJSON || JSON.parse(xhr.responseText);
                    const errorMsg = (response.errors && response.errors.error) ||
                                     response.error_message ||
                                     'Unexpected error occurred.';
                    showToast('Error: ' + errorMsg, false);
                } catch (e) {
                    showToast('Error: Could not process the server response', false);
                }
            }
        });
    }
});


});


</script> 


<!-- Include common.js -->
<script src="js/common.js"></script>

</body>
