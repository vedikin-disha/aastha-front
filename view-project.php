<!-- server -->

<?php 

include 'common/header.php';



// Decode the base64 encoded project ID

$project_id = isset($_GET['id']) ? base64_decode($_GET['id']) : 0;



// Redirect if no valid ID

if (!$project_id) {

    header('Location: projects.php');

    exit();

}

?>





<?php

// Get project ID from URL parameter

//$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;



if (!$project_id) {

    echo '<div class="alert alert-danger">Invalid project ID.</div>';

    echo '<a href="projects.php" class="btn btn-primary">Back to Projects</a>';

    include 'common/footer.php';

    exit;

}



// Initialize project data

$project = null;



// API URL for fetching project details

$api_url = API_URL . "project-edit";



// Prepare API request data

$request_data = json_encode([

    'access_token' => $_SESSION['access_token'],

    'project_id' => $project_id

]);



// Set up cURL request

$ch = curl_init($api_url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_POST, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for testing

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL host verification for testing

curl_setopt($ch, CURLOPT_HTTPHEADER, [

    'Content-Type: application/json',

    'Content-Length: ' . strlen($request_data)

]);



// Execute the request

$response = curl_exec($ch);

$error = curl_error($ch);

curl_close($ch);



// Process the response

if ($error) {

    echo '<div class="alert alert-danger">Error fetching project details: ' . $error . '</div>';

} else {

    $result = json_decode($response, true);

    

    if (isset($result['is_successful']) && $result['is_successful'] === '1' && isset($result['data'])) {

        $project = $result['data'];

    } else {

        echo '<div class="alert alert-danger">Failed to retrieve project details: ' . 

            (isset($result['errors']) ? $result['errors'] : 'Unknown error') . '</div>';

    }

}

?>



<style>

/* Assignment modal styles */

.user-tag {

    font-size: 0.9rem;

    padding: 0.4rem 0.6rem;

    display: inline-flex;

    align-items: center;

}



.user-tag .fas.fa-times {

    font-size: 0.8rem;

    opacity: 0.7;

}



.user-tag .fas.fa-times:hover {

    opacity: 1;

}



#user-checkbox-list {

    background-color: #f8f9fa;

}



#selected-users {

    background-color: #fff;

    min-height: 50px;

}



.custom-checkbox .custom-control-label {

    cursor: pointer;

}



.project-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

}



.project-title {

    font-size: 1.5rem;

    margin-bottom: 0;

}



.section-title {

    font-size: 1.2rem;

    margin-bottom: 15px;

    padding-bottom: 8px;

    border-bottom: 1px solid #eee;

}



.info-row {

    margin-bottom: 20px;

}



.info-label {

    font-weight: bold;

    color: #555;

}



.badge {

    font-size: 85%;

    padding: 5px 10px;

}



.badge-in-progress {

    background-color: #17a2b8;

    color: white;

}



.nav-tabs .nav-link.active {

    font-weight: bold;

}



.tab-content {

    padding: 20px 0;

}



/* Timeline Styles */

.timeline {

    position: relative;

    padding: 20px 0;

    margin-bottom: 20px;

}



.timeline::before {

    content: '';

    position: absolute;

    top: 0;

    bottom: 0;

    width: 4px;

    background: #ddd;

    left: 31px;

    margin: 0;

    border-radius: 2px;

}



.time-label {

    position: relative;

    margin-bottom: 15px;

    z-index: 1;

}



.time-label > span {

    display: inline-block;

    padding: 5px 10px;

    font-weight: 600;

    border-radius: 4px;

    color: white;

}



.bg-blue {

    background-color: #30b8b9 !important;

}



.bg-red {

    background-color: #dc3545 !important;

}



.bg-gray {

    background-color: #6c757d !important;

}



.bg-green {

    background-color: #28a745 !important;

}



.bg-yellow {

    background-color: #ffc107 !important;

    color: #212529 !important;

}



.bg-orange {

    background-color: #fd7e14 !important;

}



.bg-purple {

    background-color: #6f42c1 !important;

}



.timeline > div {

    margin-bottom: 15px;

    position: relative;

}



.timeline > div > i {

    width: 30px;

    height: 30px;

    font-size: 15px;

    line-height: 30px;

    position: absolute;

    color: #fff;

    background: #6c757d;

    border-radius: 50%;

    text-align: center;

    left: 18px;

    top: 0;

}



.timeline-item {

    margin-left: 60px;

    margin-right: 15px;

    margin-top: 0;

    background: #fff;

    color: #444;

    padding: 10px;

    position: relative;

    border-radius: 3px;

    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);

}



.timeline-item::before {

    content: '';

    position: absolute;

    left: -10px;

    top: 15px;

    width: 0;

    height: 0;

    border-top: 10px solid transparent;

    border-bottom: 10px solid transparent;

    border-right: 10px solid #fff;

}



.timeline-item > .time {

    float: right;

    color: #999;

    font-size: 12px;

}



.timeline-item > .timeline-header {

    margin: 0;

    color: #555;

    border-bottom: 1px solid #f4f4f4;

    padding: 10px;

    font-size: 16px;

    line-height: 1.1;

    font-weight: 500;

}



.timeline-item > .timeline-header > a {

    color: #30b8b9;

    font-weight: 600;

}



.timeline-item > .timeline-body {

    padding: 10px;

}



.timeline-item > .timeline-body a {

    color: #30b8b9;

}



.timeline-item > .timeline-footer {

    padding: 10px;

}



/* Comment section styles */

.comment-section .card-header {

    background-color: #f8f9fa;

    border-bottom: 1px solid rgba(0,0,0,.125);

}



.comment-section .form-control {

    border: 1px solid #ced4da;

    border-radius: .25rem;

}



.comment-section .btn-primary {

    background-color: #30b8b9;

    border-color: #30b8b9;

}



.badge {

    display: inline-block;

    padding: .25em .4em;

    font-size: 75%;

    font-weight: 700;

    line-height: 1;

    text-align: center;

    white-space: nowrap;

    vertical-align: baseline;

    border-radius: .25rem;

}



.badge-success {

    color: #fff;

    background-color: #28a745;

}



.badge-warning {

    color: #212529;

    background-color: #ffc107;

}



.badge-danger {

    color: #fff;

    background-color: #dc3545;

}



.badge-secondary {

    color: #fff;

    background-color: #6c757d;

}



.ms-2 {

    margin-left: .5rem !important;

}



/* Rich Text Editor Styles */

.editor-toolbar {

    padding: 8px;

    background-color: #f8f9fa;

    border: 1px solid #ced4da;

    border-radius: 4px 4px 0 0;

    display: flex;

    flex-wrap: wrap;

}



.editor-toolbar .btn-group {

    margin-bottom: 5px;

}



.editor-toolbar .btn {

    color: #495057;

}



.editor-toolbar .btn:hover {

    background-color: #e9ecef;

}



#comment-editor {

    border-top-left-radius: 0;

    border-top-right-radius: 0;

    min-height: 120px;

    padding: 10px;

}



.mr-2 {

    margin-right: 0.5rem !important;

}



/* Color Picker Styles */

.color-dropdown {

    width: 160px;

    padding: 5px;

}



.color-row {

    display: flex;

    flex-wrap: wrap;

    margin-bottom: 5px;

}



.color-item {

    width: 20px;

    height: 20px;

    margin: 2px;

    border-radius: 2px;

    border: 1px solid #dee2e6;

    display: inline-block;

}



.color-item:hover {

    border-color:#30b8b9;

    transform: scale(1.1);

}


Estimated Amount In lakhs
/* Table Dialog Styles */
Estimated Amount In lakhs
.table-grid {

    display: grid;

    grid-template-columns: repeat(6, 20px);

    grid-gap: 2px;

    margin: 10px;

}



.table-cell {

    width: 20px;

    height: 20px;

    background-color: #f8f9fa;

    border: 1px solid #dee2e6;

    cursor: pointer;

}



.table-cell:hover {

    background-color: #30b8b9;

}



.table-size-display {

    text-align: center;

    margin-top: 5px;

    font-size: 12px;

}



.nav-link {

    color: #30b8b9;

}



.nav-link:hover {

    color: #30b8b9;

}

.file-icon {

    font-size: 45px;

    margin: 0 5px;

}

</style>



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

            

            <!-- Assigned Employees Section -->

            <!-- <div class="row mb-4">

                <div class="col-md-12">

                    <h5 class="section-title">Assigned Employees</h5>

                    <div class="assigned-employees">

                        <?php if (!empty($project['assigned_emp_names'])): ?>

                            <?php foreach($project['assigned_emp_names'] as $emp_name): ?>

                                <span class="badge badge-info mr-2 mb-2">

                                    <i class="fas fa-user mr-1"></i>

                                    <?php echo htmlspecialchars($emp_name); ?>

                                </span>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <p class="text-muted">No employees assigned</p>

                        <?php endif; ?>

                    </div>

                </div>

            </div> -->



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

                                <input type="file" class="form-control-file" id="attachment" name="attachment" multiple>

                            </div>

                            <button type="submit" class="btn btn-primary float-right mt-2">Post Comment</button>

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

                </div>

                

                <!-- Project Task Tab -->

                



                <!-- Details Tab -->

                <div class="tab-pane fade show active" id="details" role="tabpanel">

                    <div class="row">

                        <!-- Basic Information -->

                        <div class="col-md-6">

                            <h4 class="section-title">Basic Information</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Project Name</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['project_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Job No</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['job_no']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Job No Reference Date</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['job_no_reference_date'])); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Project Type</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['project_type_name']); ?></div>

                            </div>

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Priority</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['priority']); ?></div>

                            </div>
                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Current Department</div>

                                <div class="col-md-8"><?php echo  htmlspecialchars($project['current_dept_name']) ; ?></div>

                            </div>







                            <div class="row info-row">

                                <div class="col-md-4 info-label">Assigned Employees</div>

                                <div class="col-md-8">

                    <div class="assigned-employees">

                        <?php if (!empty($project['assigned_emp_names'])): ?>

                            <?php foreach($project['assigned_emp_names'] as $emp_name): ?>

                                <!-- <span class="badge badge-info mr-2 mb-2"> -->

                                    <!-- <i class="fas fa-user mr-1"></i> -->

                                    <?php echo htmlspecialchars($emp_name); ?>

                                <!-- </span> -->

                            <?php endforeach; ?>

                        <?php else: ?>

                            <p class="text-muted">No employees assigned</p>

                        <?php endif; ?>

                    </div>

                    </div>

                    </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Client Name</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['client_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Description</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['description']); ?></div>

                            </div>

                        </div>

                        

                        <!-- Location & Duration -->

                        <div class="col-md-6">

                            <h4 class="section-title">Location & Duration</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Circle</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['circle_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Division</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['division_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Subdivision</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['sub_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Taluka</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['taluka_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Start Date</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['start_date'])); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">End Date</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['end_date'])); ?></div>

                            </div>

                             <!-- add the new filed is probable_date_of_completion but in my respinse is null -->

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Probable Date of Completion</div>

                                <div class="col-md-8"><?php echo date('d/m/Y', strtotime($project['probable_date_of_completion'])); ?></div>

                            </div>

                        </div>

                    </div>

                    

                    <div class="row mt-4">

                        <!-- Financial Information -->

                        <div class="col-md-6">

                            <h4 class="section-title">Financial Information</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Estimated Amount</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['estimated_amount']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Budget Head</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['budget_head']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Length</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['length']); ?> meters</div>

                            </div>

                        </div>

                        

                        <!-- Status & Metadata -->

                        <div class="col-md-6">

                            <h4 class="section-title">Status & Metadata</h4>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Status</div>

                                <div class="col-md-8">

                                    <span class="badge badge-in-progress"><?php echo htmlspecialchars($project['status']); ?></span>

                                </div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Created By</div>

                                <div class="col-md-8"><?php echo $project['created_by'] ? htmlspecialchars($project['creator_name']) : '-'; ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Created At</div>

                                <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($project['created_at'])); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Last Updated By</div>

                                <div class="col-md-8"><?php echo htmlspecialchars($project['updater_name']); ?></div>

                            </div>

                            

                            <div class="row info-row">

                                <div class="col-md-4 info-label">Last Updated At</div>

                                <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($project['updated_at'])); ?></div>

                            </div>

                        </div>

                    </div>

                </div>

                

                <!-- Timeline Tab -->

                <div class="tab-pane fade" id="timeline" role="tabpanel">

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

                                <input type="file" class="form-control-file" id="attachment" name="attachment" multiple>

                            </div>

                            <button type="submit" class="btn btn-primary float-right mt-2">Post Comment</button>

                            <!-- add attachment button -->

                         

                        </form>

                    </div>

                </div>

                    <div class="row">

                        <div class="col-12">

                            <div class="card shadow mb-4">

                                <div class="card-header py-3">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div class="d-flex align-items-center">

                                            <h6 class="m-0 font-weight-bold text-primary mr-3" style="color: #007bff;">Project Timeline</h6>

                                            <select id="timeline-user-filter" class="form-control form-control-sm" style="width: 200px;">

                                                <option value="">All Users</option>

                                            </select>

                                        </div>

                                        <?php if (isset($project['end_date']) && !empty($project['end_date'])): ?>

                                            <?php 

                                            $end_date = date('Y-m-d', strtotime($project['end_date']));

                                            $current_date = date('Y-m-d');

                                            $badge_class = '';

                                            $badge_text = '';

                                            

                                            if ($end_date < $current_date) {

                                                $badge_class = 'badge-danger';

                                                $badge_text = 'Due: ' . date('d M Y', strtotime($project['end_date']));

                                            } elseif ($end_date == $current_date) {

                                                $badge_class = 'badge-warning';

                                                $badge_text = 'Due: ' . date('d M Y', strtotime($project['end_date'])) . ' (Today)';

                                            } else {

                                                $badge_class = 'badge-success';

                                                $badge_text = 'Due: ' . date('d M Y', strtotime($project['end_date']));

                                            }

                                            ?>

                                            <span class="badge <?php echo $badge_class; ?> ms-2">

                                                <?php echo $badge_text; ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-secondary ms-2">No end date</span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                                <div class="card-body">

                                    <div id="timeline-loading" class="text-center py-5">

                                        <div class="spinner-border text-primary" role="status">

                                            <span class="sr-only">Loading...</span>

                                        </div>

                                        <p class="mt-2">Loading timeline...</p>

                                    </div>

                                    <div id="timeline-content" class="timeline" style="display: none;">

                                        <!-- Timeline content will be loaded here -->

                                    </div>

                                    <div id="timeline-error" class="alert alert-danger" style="display: none;">

                                        <i class="fas fa-exclamation-circle"></i> Error loading timeline data. Please try again later.

                                    </div>

                                    

                                    <!-- Comment Section -->

                                    

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                

                <!-- Comments Tab -->

                <div class="tab-pane fade" id="comments" role="tabpanel">

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

                </div>





                <!-- Project Task Tab -->

                <div class="tab-pane fade" id="project-task-tab-content" role="tabpanel">

                    <div class="row">

                        <div class="table-responsive">

                        <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">

<h6 class="m-0 font-weight-bold text-primary" style="color:#30b8b9 !important;">
    Project Task
</h6>

<div class="d-flex align-items-center ms-auto gap-2">
<?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
    <select id="user-filter" class="form-control form-control-sm me-2" style="width: 180px; margin-right: 10px;">
        <option value="">Select User</option>
    </select>
<?php endif; ?>


<?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
    <button type="button" class="btn btn-sm btn-success" onclick="assignTask()" style="margin-right: 10px;">
        <i class="fas fa-user"></i> Assign To
    </button>
<?php endif; ?>
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

                </div>


                <!-- attachments tab -->

                <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                <div class="d-flex justify-content-between align-items-center">

<h6 class="m-0 font-weight-bold text-primary" style="color:#30b8b9 !important;">Project Attachments</h6>

<?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 ): ?>

<button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#attachmentsModal" style="margin-bottom: 20px;">

    <i class="fas fa-plus"></i> Add New Attachment

</button>

<?php endif; ?>


</div>


                            <table class="table table-striped table-bordered" id="attachments-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Attachment Name</th>
                                        <th>Description</th>
                                        <th>File</th>
                                    <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 ): ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="attachments-table-body">
                                    <!-- Attachments will be loaded here -->
                                </tbody>
                            </table>
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

    

    // Load users for timeline filter

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



    // Load users when page loads

    loadUsers();



    // Handle user filter change

    $('#timeline-user-filter').on('change', function() {

        loadProjectTimeline();
        loadUsers();

    });



    // Function to load project timeline data from API

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

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

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

    

    // Function to display timeline data

    // Function to generate HTML for attachments

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

    

    // Function to format date

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

    

    // Function to get icon for activity type

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

    

    // Function to get background class for activity type

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

    

    // Add CSS for attachments

    const attachmentStyles = `

        <style>

            .timeline-attachments {

                padding: 10px;

                background: #f8f9fa;

                border-radius: 4px;

                margin-top: 10px;

            }

            .image-gallery {

                display: flex;

                flex-wrap: wrap;

                gap: 5px;

            }

            .image-gallery a {

                display: inline-block;

                transition: transform 0.2s;

            }

            .image-gallery a:hover {

                transform: scale(1.05);

            }

            .video-gallery {

                display: flex;

                flex-wrap: wrap;

                gap: 10px;

            }

            .video-wrapper {

                position: relative;

                cursor: pointer;

            }

            .video-wrapper video {

                max-width: 200px;

                max-height: 200px;

            }

            .video-play-icon {

                position: absolute;

                top: 50%;

                left: 50%;

                transform: translate(-50%, -50%);

                color: white;

                font-size: 48px;

                text-shadow: 0 0 10px rgba(0,0,0,0.5);

                opacity: 0.8;

                transition: all 0.3s;

            }

            .video-play-icon:hover {

                opacity: 1;

                transform: translate(-50%, -50%) scale(1.1);

            }

            .other-files {

                margin-top: 10px;

            }

            .file-item {

                padding: 5px 0;

            }

            .file-item a {

                color: #007bff;

                text-decoration: none;

            }

            .file-item a:hover {

                text-decoration: underline;

            }

        </style>

    `;

    

    // Add styles to head

    $('head').append(attachmentStyles);

    

  

    

    // Add styles to head if not already added

    if ($('style.timeline-attachments-styles').length === 0) {

        $('head').append('<style class="timeline-attachments-styles">' + 

            '.timeline-attachments { padding: 10px; background: #f8f9fa; border-radius: 4px; margin-top: 10px; }' +

            '.image-gallery { display: flex; flex-wrap: wrap; gap: 5px; }' +

            '.image-gallery a { display: inline-block; transition: transform 0.2s; }' +

            '.image-gallery a:hover { transform: scale(1.05); }' +

            '.video-gallery { display: flex; flex-wrap: wrap; gap: 10px; }' +

            '.video-wrapper { position: relative; cursor: pointer; }' +

            '.video-play-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 48px; text-shadow: 0 0 10px rgba(0,0,0,0.5); opacity: 0.8; transition: all 0.3s; }' +

            '.video-play-icon:hover { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }' +

            '.other-files { margin-top: 10px; }' +

            '.file-item { padding: 5px 0; }' +

            '.file-item a { color: #007bff; text-decoration: none; }' +

            '.file-item a:hover { text-decoration: underline; }' +

            '</style>');

    }

    

    // Initialize Fancybox for image gallery if not already loaded

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

    

    // Handle comment form submission

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



    // First API call: activity-add

    const requestData = {

        access_token: '<?php echo $_SESSION["access_token"]; ?>',

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

            console.log('Comment added:', response);



            if (response.is_successful === '1') {

                const activityId = response.data.activity_id;



                // Upload each file one by one

                const uploadPromises = [];

                for (let i = 0; i < files.length; i++) {

                    const formData = new FormData();

                    formData.append('activity_id', activityId);

                    formData.append('access_token', '<?php echo $_SESSION["access_token"]; ?>');

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



                // Wait for all attachments to upload

                Promise.all(uploadPromises)

                    .then(function (results) {

                        console.log('All attachments uploaded successfully', results);



                        $('#comment-editor').html('');

                        $('#comment-text').val('');

                        $('#attachment').val('');

                        $submitBtn.prop('disabled', false).html(originalBtnText);



                        alert('Comment and attachments uploaded successfully!');

                        //refersh the page 

                        

                        // loadTimeline(); // or loadProjectTimeline();

						loadProjectTimeline();

                        //refresh the page in 1 second in the actibvity tab

                       

                    })

                    .catch(function (error) {

                        console.error('Attachment upload failed', error);

                        $submitBtn.prop('disabled', false).html(originalBtnText);

                        alert('Comment added but one or more attachments failed.');

                    });

            } else {

                $submitBtn.prop('disabled', false).html(originalBtnText);

                alert('Failed to add comment: ' + (response.errors || 'Unknown error'));

            }

        },

        error: function (xhr, status, error) {

            console.error('Comment API error:', error);

            $submitBtn.prop('disabled', false).html(originalBtnText);

            alert('Failed to add comment. Please try again.');

        }

    });

});

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

    function showAlert(message, type = 'success') {

        // Create alert element

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

    

    // Helper function to convert month name to month number (0-11)

    function getMonthNumber(monthName) {

        const months = {

            'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,

            'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11

        };

        return months[monthName] || 0;

    }

    

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

    

    // Format date for display

    function formatDate(dateString) {

        const date = new Date(dateString);

        return date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

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



    function projectTask(){

       $() 

    }

    

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

});



</script>



<!-- Project Task JavaScript -->

<script type="text/javascript">
$(document).on('change', '#select-all-tasks', function() {
    const isChecked = $(this).is(':checked');

    $('.task-checkbox:enabled').prop('checked', isChecked);
});



    function loadProjectTasks() {

        $.ajax({

            url: '<?php echo API_URL; ?>project-task-listing',

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





	$(document).ready(function() {

	

    // Load tasks when the project task tab is clicked

    $('#project-task-tab').on('click', function() {

        loadProjectTasks();

        loadDepartmentsForTask();
        loadUsers();

    });

    

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

                    alert(message);

                } else {

                    // Update failed, revert switch state

                    $switch.prop('checked', !isChecked);

                    alert('Failed to update task status: ' + (response.errors || 'Unknown error'));

                }

            },

            error: function(xhr, status, error) {

                console.error('Error updating task status:', error);

                alert('Error updating task status. Please try again.');

            }

        });

    });



    // Load tasks when the project task tab is shown

    $('a[data-toggle="tab"][href="#project-task"]').on('shown.bs.tab', function (e) {

        loadProjectTasks();
        loadUsers();

    });



    // Function to open assignment modal

    window.openAssignmentModal = function(mode) {

        window.assignmentMode = mode; // Store the current mode (add/update)

        

        // Update modal title based on mode

        const modalTitle = mode === 'add' ? 'Add Project Assignment' : 'Update Project Assignment';

        $('#assignmentModalLabel').text(modalTitle);

        

        // Update save button text

        const saveButtonText = mode === 'add' ? 'Add Assignment' : 'Update Assignment';

        $('#save-assignment').text(saveButtonText);

        

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

                access_token: '<?php echo $_SESSION["access_token"]; ?>',

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

            access_token: '<?php echo $_SESSION["access_token"]; ?>',

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

        access_token: '<?php echo $_SESSION["access_token"]; ?>',

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

                    access_token: '<?php echo $_SESSION["access_token"]; ?>',

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
    formData.append('access_token', '<?php echo $_SESSION["access_token"]; ?>');
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
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
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
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
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
            access_token: '<?php echo $_SESSION["access_token"]; ?>'
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