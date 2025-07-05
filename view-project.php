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
            <h3 style="margin-top: 20px;">Project Details</h3>
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
                    <a href="edit-project?id=<?php echo base64_encode($project['project_id']); ?>" class="btn btn-primary" style="background-color: #30b8b9;border:none;">
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
        loadUsersToDropdown();
    });

    
});
</script>

<?php include 'view-project-modal-add-new-task.php'; ?>
</div>

<?php include 'view-project-modal-user-assignment.php'; ?>

<?php include 'view-project-modal-compliance-answer.php'; ?>

<?php include 'view-project-modal-add-attachment.php'; ?>

<?php include 'common/footer.php'; ?>