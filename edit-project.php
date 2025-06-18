<?php 
include 'common/header.php';

// Decode the base64 encoded project ID
$project_id = isset($_GET['id']) ? base64_decode($_GET['id']) : 0;

// Redirect if no valid ID
if (!$project_id) {
    header('Location: projects');
    exit();
}
?>

<!-- Additional CSS -->
<link rel="stylesheet" href="css/daterangepicker.css">
<link rel="stylesheet" href="css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="css/select2.min.css">
<link rel="stylesheet" href="css/select2-bootstrap4.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">

<style>
    .required-label::after {
        content: ' *';
        color: red;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .error-feedback {
        width: 100%;
        margin-top: .25rem;
        font-size: 80%;
        color: #dc3545;
    }
    
    .select2-default {
        /* background-color: #30b8b9 !important; */
        background-color: #f8f9fa !important;
    }
    .select2-default:hover {
        background-color: #f8f9fa !important;
    }
    
    /* Base style for dropdown options */
    .select2-container--bootstrap-5 .select2-results__option {
        color: #212529;
    }
    
    /* Hover state */
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: rgb(236, 236, 236) !important;
        color: #212529 !important;
    }
    
    /* Selected item in closed dropdown */
    .select2-container--bootstrap-5 .select2-selection--single {
        /* background-color: #30b8b9 !important; */
        background-color: #f8f9fa !important;
        color: white !important;
        border-color: #30b8b9 !important;
    }
    
    /* Dropdown arrow */
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow b {
        border-color: white transparent transparent transparent !important;
    }
    
    /* Selected item in dropdown list */
    .select2-container--bootstrap-5 .select2-results__option--selected,
    .select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #30b8b9 !important;
        color: white !important;
    }
    
    /* Hover state for non-selected items */
    .select2-container--bootstrap-5 .select2-results__option:not(.select2-results__option--selected):hover {
        background-color: rgb(236, 236, 236) !important;
        color: #212529 !important;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Project</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <a href="projects.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Projects
            </a>
            </ol>
        </div>
    </div>

   
    
    <!-- Alert for form submission result -->
    <div id="formResult" class="alert" style="display: none;"></div>

    <div class="card">
        <div class="card-header card-primary card-outline">
            <h3 class="card-title">Project Details</h3>
        </div>
        <div class="card-body">
            <form id="editProjectForm">
                <div class="row">
                    <!-- Basic Information Section -->
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Project Type -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="project_type_id" class="required-label">Project Type:</label>
                                            <select id="project_type_id" name="project_type_id"class="form-control select2 custom-project-type-select" required>
                                                <option value="" class="select2-default" style="background-color: #f8f9fa;">Select Project Type</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="project_type_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Project Name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="project_name" class="required-label">Project Name:</label>
                                            <input type="text" class="form-control" id="project_name" name="project_name" 
                                                placeholder="Enter project name" required>
                                            <div class="error-feedback" id="project_name_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Job No -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="job_no">Job No:</label>
                                            <input type="text" class="form-control" id="job_no" name="job_no" 
                                                placeholder="Enter job number">
                                            <div class="error-feedback" id="job_no_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Job No Reference Date (Hidden) -->
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="job_no_reference_date">Job No Reference Date:</label>
                                        <input type="date" class="form-control" id="job_no_reference_date" name="job_no_reference_date">
                                        <div class="error-feedback" id="job_no_reference_date_error"></div>
                                    </div>
                                    </div>
                                    
                                    <!-- Priority -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="priority" class="required-label">Priority:</label>
                                            <select id="priority" name="priority" class="form-control" required>
                                                <option value="">-- Select Priority --</option>
                                                <option value="High">High</option>
                                                <option value="Regular">Regular</option>
                                            </select>
                                            <div class="error-feedback" id="priority_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Current Department -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Current Department:</label>
                                            <input type="text" class="form-control" id="current_dept_name" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Assigned Employees -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assigned Employees:</label>
                                            <div id="assigned_employees" class="form-control-plaintext">No employees assigned</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Client Name -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="client_name">Client Name:</label>
                                            <input type="text" class="form-control" id="client_name" name="client_name" 
                                                placeholder="Enter client name">
                                            <div class="error-feedback" id="client_name_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="status" class="required-label">Status:</label>
                                            <select id="status" name="status" class="form-control" required>
                                                <option value="">-- Select Status --</option>
                                                <option value="Pending">Pending</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Internal Done">Internal Done</option>
                                                <option value="Ext - Taluka">Ext - Taluka</option>
                                                <option value="Ext - Sub Division">Ext - Sub Division</option>
                                                <option value="Ext - Division">Ext - Division</option>
                                                <option value="Ext - Circle">Ext - Circle</option>
                                                <option value="Ext - Govt.">Ext - Govt.</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                            <div class="error-feedback" id="status_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" 
                                                placeholder="Enter project description"></textarea>
                                            <div class="error-feedback" id="description_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location & Duration Section -->
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Location & Duration</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Start Date -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="start_date">Start Date:</label>
                                            <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                                <input type="date" class="form-control datetimepicker-input" id="start_date" 
                                                    name="start_date" data-target="#start_date_picker">
                                                <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                                    
                                                </div>
                                            </div>
                                            <div class="error-feedback" id="start_date_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- End Date -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                                <input type="date" class="form-control datetimepicker-input" id="end_date" 
                                                    name="end_date" data-target="#end_date_picker">
                                                <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                                   
                                                </div>
                                            </div>
                                            <div class="error-feedback" id="end_date_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Proposed Work -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="proposed_work_id">Proposed Work:</label>
                                            <select id="proposed_work_id" name="proposed_work_id" class="form-control select2">
                                                <option value="">-- Select Proposed Work --</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="proposed_work_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Proposed Work -->
                                    <!-- <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="proposed_work_id">Proposed Work:</label>
                                            <select id="proposed_work_id" name="proposed_work_id" class="form-control select2">
                                                <option value="">-- Select Proposed Work --</option> -->
                                                <!-- Options will be loaded from API -->
                                            <!-- </select>
                                            <div class="error-feedback" id="proposed_work_id_error"></div>
                                        </div>
                                    </div> -->
                                    
                                    <!-- Circle -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_circle">Circle:</label>
                                            <select id="id_circle" name="circle_id" class="form-control select2">
                                                <option value="">Select Circle</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="circle_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Division -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_division">Division:</label>
                                            <select id="id_division" name="division_id" class="form-control select2">
                                                <option value="">Select Division</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="division_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Subdivision -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_sub">Subdivision:</label>
                                            <select id="id_sub" name="sub_id" class="form-control select2">
                                                <option value="">Select Subdivision</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="sub_id_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Taluka -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="id_taluka">Taluka:</label>
                                            <select id="id_taluka" name="taluka_id" class="form-control select2">
                                                <option value="">Select Taluka</option>
                                                <!-- Options will be loaded from API -->
                                            </select>
                                            <div class="error-feedback" id="taluka_id_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Remove duplicate date fields -->
                <div class="d-none">
                    <input type="date" id="end_date_duplicate" name="end_date_duplicate">
                    <input type="date" id="start_date_duplicate" name="start_date_duplicate">
                </div>

                <!-- Estimated Amount In lakhs and Metadata Section -->
                <div class="row" style="margin-top: 20px;">
                    <!-- Estimated Amount In lakhs -->
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Estimated Amount In lakhs</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Estimated Amount -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="estimated_amount">Estimated Amount:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₹</span>
                                                </div>
                                                <input type="number" class="form-control" id="estimated_amount" name="estimated_amount" 
                                                    step="0.01" min="0" value="0" placeholder="0.00">
                                            </div>
                                            <div class="error-feedback" id="estimated_amount_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Budget Head -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="budget_head">Budget Head:</label>
                                            <input type="text" class="form-control" id="budget_head" name="budget_head" 
                                                placeholder="Enter budget head">
                                            <div class="error-feedback" id="budget_head_error"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Length -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="length">Length (in meters):</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="length" name="length" 
                                                    step="0.01" min="0" value="0" placeholder="0.00">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                            <div class="error-feedback" id="length_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status & Metadata -->
                    <div class="col-md-6">
                        <div class="card mb-4 h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Project Metadata</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Created By -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Created By:</label>
                                            <input type="text" class="form-control" id="created_by" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Created At -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Created At:</label>
                                            <input type="text" class="form-control" id="created_at" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Last Updated By -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Last Updated By:</label>
                                            <input type="text" class="form-control" id="updated_by" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Last Updated At -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Last Updated At:</label>
                                            <input type="text" class="form-control" id="updated_at" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Assignment Section -->
                <div class="card mt-4 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Department Assignments</h5>
                    </div>
                    <div class="card-body">
                        <!-- Department 1 -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept1_id">Department 1:</label>
                                    <select id="dept1_id" name="dept1_id" class="form-control select2">
                                        <!-- <option value="">Select Department</option>
                                        <option value="1">Survey Department</option>
                                        <option value="2">Drafting Department</option>
                                        <option value="3">Design Department</option>
                                        <option value="4">Data Entry Operator</option>
                                        <option value="5">Admin</option> -->
                                    </select>
                                    <div class="error-feedback" id="dept1_id_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept1_assigned_days">Assigned Days:</label>
                                    <input type="number" class="form-control" id="dept1_assigned_days" name="dept1_assigned_days" min="1" placeholder="Enter days">
                                    <div class="error-feedback" id="dept1_assigned_days_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept1_due_date">Due Date:</label>
                                    <div class="input-group date" id="dept1_due_date_picker" data-target-input="nearest">
                                        <input type="date" class="form-control" id="dept1_due_date" name="dept1_due_date" placeholder="yyyy-mm-dd">
                                    </div>
                                    <div class="error-feedback" id="dept1_due_date_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Department 2 -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept2_id">Department 2:</label>
                                    <select id="dept2_id" name="dept2_id" class="form-control select2">
                                        <!-- <option value="">Select Department</option>
                                        <option value="1">Survey Department</option>
                                        <option value="2">Drafting Department</option>
                                        <option value="3">Design Department</option>
                                        <option value="4">Data Entry Operator</option>
                                        <option value="5">Admin</option> -->
                                    </select>
                                    <div class="error-feedback" id="dept2_id_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept2_assigned_days">Assigned Days:</label>
                                    <input type="number" class="form-control" id="dept2_assigned_days" name="dept2_assigned_days" min="1" placeholder="Enter days">
                                    <div class="error-feedback" id="dept2_assigned_days_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept2_due_date">Due Date:</label>
                                    <div class="input-group date" id="dept2_due_date_picker" data-target-input="nearest">
                                        <input type="date" class="form-control" id="dept2_due_date" name="dept2_due_date" placeholder="yyyy-mm-dd">
                                    </div>
                                    <div class="error-feedback" id="dept2_due_date_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Department 3 -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept3_id">Department 3:</label>
                                    <select id="dept3_id" name="dept3_id" class="form-control select2">
                                        <!-- <option value="">Select Department</option>
                                        <option value="1">Survey Department</option>
                                        <option value="2">Drafting Department</option>
                                        <option value="3">Design Department</option>
                                        <option value="4">Data Entry Operator</option>
                                        <option value="5">Admin</option> -->
                                    </select>
                                    <div class="error-feedback" id="dept3_id_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept3_assigned_days">Assigned Days:</label>
                                    <input type="number" class="form-control" id="dept3_assigned_days" name="dept3_assigned_days" min="1" placeholder="Enter days">
                                    <div class="error-feedback" id="dept3_assigned_days_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept3_due_date">Due Date:</label>
                                    <div class="input-group date" id="dept3_due_date_picker" data-target-input="nearest">
                                        <input type="date" class="form-control" id="dept3_due_date" name="dept3_due_date" placeholder="yyyy-mm-dd">
                                    </div>
                                    <div class="error-feedback" id="dept3_due_date_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Department 4 -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept4_id">Department 4:</label>
                                    <select id="dept4_id" name="dept4_id" class="form-control select2">
                                        <!-- <option value="">Select Department</option>
                                        <option value="1">Survey Department</option>
                                        <option value="2">Drafting Department</option>
                                        <option value="3">Design Department</option>
                                        <option value="4">Data Entry Operator</option>
                                        <option value="5">Admin</option> -->
                                    </select>
                                    <div class="error-feedback" id="dept4_id_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept4_assigned_days">Assigned Days:</label>
                                    <input type="number" class="form-control" id="dept4_assigned_days" name="dept4_assigned_days" min="1" placeholder="Enter days">
                                    <div class="error-feedback" id="dept4_assigned_days_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept4_due_date">Due Date:</label>
                                    <div class="input-group date" id="dept4_due_date_picker" data-target-input="nearest">
                                        <input type="date" class="form-control" id="dept4_due_date" name="dept4_due_date" placeholder="yyyy-mm-dd">
                                    </div>
                                    <div class="error-feedback" id="dept4_due_date_error"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Department 5 -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept5_id">Department 5:</label>
                                    <select id="dept5_id" name="dept5_id" class="form-control select2">
                                        <!-- <option value="">Select Department</option>
                                        <option value="1">Survey Department</option>
                                        <option value="2">Drafting Department</option>
                                        <option value="3">Design Department</option>
                                        <option value="4">Data Entry Operator</option>
                                        <option value="5">Admin</option> -->
                                    </select>
                                    <div class="error-feedback" id="dept5_id_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept5_assigned_days">Assigned Days:</label>
                                    <input type="number" class="form-control" id="dept5_assigned_days" name="dept5_assigned_days" min="1" placeholder="Enter days">
                                    <div class="error-feedback" id="dept5_assigned_days_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dept5_due_date">Due Date:</label>
                                    <div class="input-group date" id="dept5_due_date_picker" data-target-input="nearest">
                                        <input type="date" class="form-control" id="dept5_due_date" name="dept5_due_date" placeholder="yyyy-mm-dd">
                                    </div>
                                    <div class="error-feedback" id="dept5_due_date_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success" style="background-color: #30b8b9;border:none;" id="saveProject">
                            <span id="saveIcon"><i class="fas fa-save"></i> Save Project</span>
                            <span id="savingLoader" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>
                        <a href="projects.php" class="btn btn-secondary ml-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Required JS files -->
<script src="js/jquery-3.5.1.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>
// API functions defined first so they can be called immediately
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
                    options += `<option class="select2-default" value="${type.project_type_id}">${type.project_type_name}</option>`;
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

function loadProposedWorks() {
    console.log('Loading proposed works...');
    return new Promise((resolve, reject) => {
        $.ajax({    
            url: '<?php echo API_URL; ?>proposed-work-listing',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: "<?php echo $_SESSION['access_token']; ?>"
            }),
            success: function(response) {
                console.log('Proposed works API response:', response);
                if (response.is_successful === "1" && Array.isArray(response.data)) {
                    const $proposedWorkSelect = $('#proposed_work_id');
                    const currentValue = $proposedWorkSelect.val(); // Save current value
                    
                    $proposedWorkSelect.empty().append('<option value="">-- Select Proposed Work --</option>');
                    
                    response.data.forEach(function(work) {
                        $proposedWorkSelect.append(
                            $('<option>', {
                                value: work.proposed_work_id,
                                text: work.proposed_work_name,
                                selected: (work.proposed_work_id == currentValue)
                            })
                        );
                    });
                    
                    // Restore or set the selected value
                    if (currentValue) {
                        $proposedWorkSelect.val(currentValue).trigger('change');
                    }
                    
                    resolve(response);
                } else {
                    console.error('Failed to load proposed works:', response);
                    $(document).Toasts('create', {
                        class: 'bg-warning',
                        title: 'Warning',
                        body: 'Unable to load proposed works. Please try refreshing the page.',
                        autohide: true,
                        delay: 3000
                    });
                    reject(new Error('Invalid response format'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading proposed works:', error);
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Failed to load proposed works. Please check your connection.',
                    autohide: true,
                    delay: 3000
                });
                reject(error);
            }
        });
    });
}

function loadCircles() {
    console.log('Loading circles...');
    $.ajax({
        url: '<?php echo API_URL; ?>circle',
        type: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
            console.log('Circles API response:', response);
            if (response && Array.isArray(response)) {
                var circleSelect = $('#id_circle');
                circleSelect.empty();
                circleSelect.append('<option value="">Select Circle</option>');
                
                response.forEach(function(circle) {
                    circleSelect.append('<option value="' + circle.circle_id + '">' + circle.circle_name + '</option>');
                });
            } else {
                console.error('API Error:', 'Invalid response format');
            }
        },
        error: function(error) {
            console.error('Error loading circles:', error);
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: 'Failed to load circle data. Please refresh the page.',
                autohide: true,
                delay: 3000
            });
        }
    });
}

function loadDivisions(circleId) {
    console.log('Loading divisions for circle ID:', circleId);
    $.ajax({
        url: '<?php echo API_URL; ?>division',
        type: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>",
            circle_id: circleId
        }),
        success: function(response) {
            console.log('Divisions API response:', response);
            var divisionSelect = $('#id_division');
            divisionSelect.empty();
            divisionSelect.append('<option value="">Select Division</option>');
            
            if (response && Array.isArray(response)) {
                response.forEach(function(division) {
                    divisionSelect.append('<option value="' + division.division_id + '">' + division.division_name + '</option>');
                });
            } else {
                console.error('API Error:', 'Invalid division response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching divisions:', error);
        }
    });
}

function loadSubDivisions(divisionId) {
    console.log('Loading subdivisions for division ID:', divisionId);
    $.ajax({
        url: '<?php echo API_URL; ?>subdivision',
        type: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>",
            division_id: divisionId
        }),
        success: function(response) {
            console.log('Subdivisions API response:', response);
            var subDivisionSelect = $('#id_sub');
            subDivisionSelect.empty();
            subDivisionSelect.append('<option value="">Select Subdivision</option>');
            
            if (response && Array.isArray(response)) {
                response.forEach(function(subdivision) {
                    subDivisionSelect.append('<option value="' + subdivision.sub_id + '">' + subdivision.subdivision_name + '</option>');
                });
            } else {
                console.error('API Error:', 'Invalid subdivision response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching subdivisions:', error);
        }
    });
}


function loadTalukas(subId) {
        $.ajax({
            url: '<?php echo API_URL; ?>taluka',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                access_token: "<?php echo $_SESSION['access_token']; ?>",
                sub_id: subId
            }),
            success: function(response) {
                var talukaSelect = $('#id_taluka');
                talukaSelect.empty();
                talukaSelect.append('<option value="">All Talukas</option>');
                
                if (response && Array.isArray(response)) {
                    response.forEach(function(taluka) {
                        talukaSelect.append('<option value="' + taluka.taluka_id + '">' + taluka.taluka_name + '</option>');
                    });
                } else {
                    console.error('API Error:', 'Invalid taluka response format');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching talukas:', error);
            }
        });
    }

// Function to load project data for editing
function loadProjectData() {
    // Get project ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get('id');
    
    if (!projectId) {
        $('#formResult').removeClass('alert-success').addClass('alert-danger')
            .html('Invalid project ID. Please go back to the projects list.').show();
        return;
    }
    
    // Decode the base64 project ID
    const decodedId = atob(projectId);
    
    // Show loading state
    $('#formResult').removeClass('alert-danger alert-success').addClass('alert-info')
        .html('<i class="fas fa-spinner fa-spin"></i> Loading project data...').show();
    
    // Make API request to get project data
    $.ajax({
        url: '<?php echo API_URL; ?>project-edit',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>",
            project_id: decodedId
        }),
        success: function(response) {
            console.log('Project data:', response);
            
            if (response.is_successful === "1" && response.data) {
                // Hide the loading message
                $('#formResult').hide();
                
                // Set current department
                $('#current_dept_name').val(response.data.current_dept_name || 'Not assigned');
                
                // Populate form fields with project data
                populateFormFields(response.data);
            } else {
                // Show error message
                $('#formResult').removeClass('alert-info alert-success').addClass('alert-danger')
                    .html('Failed to load project data: ' + (response.errors || 'Unknown error')).show();
            }
        },
        error: function(xhr, status, error) {
            // Show error message
            $('#formResult').removeClass('alert-info alert-success').addClass('alert-danger')
                .html('Error loading project data: ' + error).show();
            console.error('API Error:', error);
            console.error('Response:', xhr.responseText);
        }
    });
}
// for the priority

// Function to set department values after they are loaded
// Function to display assigned employees
function displayAssignedEmployees(employees) {
    const container = $('#assigned_employees');
    
    if (!employees || employees.length === 0) {
        container.html('No employees assigned');
        return;
    }
    
    // Create a list of employee names with profile images
    const employeeList = employees.map(emp => {
        let html = '<div class="d-flex align-items-center mb-2">';
        if (emp.profile_pic) {
            html += `<img src="${emp.profile_pic}" class="rounded-circle mr-2" width="30" height="30" alt="${emp.emp_name}">`;
        }
        html += `<span>${emp.emp_name || 'Unnamed Employee'}</span>`;
        html += '</div>';
        return html;
    }).join('');
    
    container.html(employeeList);
}

// Function to check if a department is the current department
function isDepartmentEditable(deptId, currentDeptId) {
    // If current_dept_id is null, make all departments editable
    if (currentDeptId === null || currentDeptId === undefined) {
        return true;
    }
    return deptId && deptId.toString() === currentDeptId.toString();
}

// Function to set department field as read-only
function setDepartmentReadOnly(deptNumber, isReadOnly) {
    // Always enable department ID and assigned days fields
    $(`#dept${deptNumber}_id`).prop('disabled', false);
    $(`#dept${deptNumber}_assigned_days`).prop('readonly', false);
    
    // Only set due date as read-only if specified
    $(`#dept${deptNumber}_due_date`).prop('readonly', isReadOnly);
    
    // Add a visual indicator for read-only fields
    const readOnlyClass = '#e9ecef';
    if (isReadOnly) {
        $(`#dept${deptNumber}_due_date`).css('background-color', readOnlyClass);
    } else {
        $(`#dept${deptNumber}_due_date`).css('background-color', '');
    }
    
    // Remove any background color from other fields
    $(`#dept${deptNumber}_id`).css('background-color', '');
    $(`#dept${deptNumber}_assigned_days`).css('background-color', '');
}

function setDepartmentValues(data) {
    // Store current department ID, default to null if not provided
    const currentDeptId = data.current_dept_id || null;
    console.log('Current Department ID:', currentDeptId);
    console.log('Department data:', {
        dept1: {id: data.dept1_id, name: data.dept1_name},
        dept2: {id: data.dept2_id, name: data.dept2_name},
        dept3: {id: data.dept3_id, name: data.dept3_name},
        dept4: {id: data.dept4_id, name: data.dept4_name},
        dept5: {id: data.dept5_id, name: data.dept5_name}
    });
    
    // Department 1
    if (data.dept1_id) {
        const isEditable = isDepartmentEditable(data.dept1_id, currentDeptId);
        setDepartmentReadOnly(1, !isEditable);
        
        $('#dept1_id').val(data.dept1_id).trigger('change');
        $('#dept1_assigned_days').val(data.dept1_assigned_days);
        if (data.dept1_due_date) {
            const dept1DueDate = new Date(data.dept1_due_date);
            const formattedDept1DueDate = dept1DueDate.toISOString().split('T')[0];
            $('#dept1_due_date').val(formattedDept1DueDate);
        }
    } else {
        setDepartmentReadOnly(1, true);
    }
    
    // Department 2
    if (data.dept2_id) {
        const isEditable = isDepartmentEditable(data.dept2_id, currentDeptId);
        setDepartmentReadOnly(2, !isEditable);
        
        $('#dept2_id').val(data.dept2_id).trigger('change');
        $('#dept2_assigned_days').val(data.dept2_assigned_days);
        if (data.dept2_due_date) {
            const dept2DueDate = new Date(data.dept2_due_date);
            const formattedDept2DueDate = dept2DueDate.toISOString().split('T')[0];
            $('#dept2_due_date').val(formattedDept2DueDate);
        }
    } else {
        setDepartmentReadOnly(2, true);
    }
    
    // Department 3
    if (data.dept3_id) {
        const isEditable = isDepartmentEditable(data.dept3_id, currentDeptId);
        setDepartmentReadOnly(3, !isEditable);
        
        $('#dept3_id').val(data.dept3_id).trigger('change');
        $('#dept3_assigned_days').val(data.dept3_assigned_days);
        if (data.dept3_due_date) {
            const dept3DueDate = new Date(data.dept3_due_date);
            const formattedDept3DueDate = dept3DueDate.toISOString().split('T')[0];
            $('#dept3_due_date').val(formattedDept3DueDate);
        }
    } else {
        setDepartmentReadOnly(3, true);
    }
    
    // Department 4
    if (data.dept4_id) {
        const isEditable = isDepartmentEditable(data.dept4_id, currentDeptId);
        setDepartmentReadOnly(4, !isEditable);
        
        $('#dept4_id').val(data.dept4_id).trigger('change');
        $('#dept4_assigned_days').val(data.dept4_assigned_days);
        if (data.dept4_due_date) {
            const dept4DueDate = new Date(data.dept4_due_date);
            const formattedDept4DueDate = dept4DueDate.toISOString().split('T')[0];
            $('#dept4_due_date').val(formattedDept4DueDate);
        }
    } else {
        setDepartmentReadOnly(4, true);
    }
    
    // Department 5
    if (data.dept5_id) {
        const isEditable = isDepartmentEditable(data.dept5_id, currentDeptId);
        setDepartmentReadOnly(5, !isEditable);
        
        $('#dept5_id').val(data.dept5_id).trigger('change');
        $('#dept5_assigned_days').val(data.dept5_assigned_days);
        if (data.dept5_due_date) {
            const dept5DueDate = new Date(data.dept5_due_date);
            const formattedDept5DueDate = dept5DueDate.toISOString().split('T')[0];
            $('#dept5_due_date').val(formattedDept5DueDate);
        }
    } else {
        setDepartmentReadOnly(5, true);
    }
}

// Function to populate form fields with project data
function populateFormFields(data) {
    // Store project type information for reference
    window.projectTypeData = {
        id: data.project_type_id,
        name: data.project_type_name,
        // Store department data to be used after loading
        departmentData: data
    };
    
    // Check if options exist in project_type_id dropdown
    if ($('#project_type_id option').length > 1) {
        // If options exist, set the value
        $('#project_type_id').val(data.project_type_id).trigger('change');
    } else {
        // If options don't exist yet, create a temporary option with the current project type
        $('#project_type_id').append(`<option value="${data.project_type_id}">${data.project_type_name}</option>`);
        $('#project_type_id').val(data.project_type_id).trigger('change');
    }
    console.log('Setting project type ID:', data.project_type_id, 'Name:', data.project_type_name);
    
    // Set proposed work if it exists
    if (data.proposed_work_id) {
        // First, ensure the proposed works are loaded
        if ($('#proposed_work_id option').length <= 1) { // Only default option exists
            // Load proposed works and then set the value
            loadProposedWorks().then(() => {
                if ($(`#proposed_work_id option[value="${data.proposed_work_id}"]`).length) {
                    $('#proposed_work_id').val(data.proposed_work_id).trigger('change');
                } else {
                    // If still not found, add it manually
                    $('#proposed_work_id').append(
                        $('<option>', {
                            value: data.proposed_work_id,
                            text: data.proposed_work_name || 'Selected Work',
                            selected: true
                        })
                    ).trigger('change');
                }
            });
        } else {
            // If options are already loaded, just set the value
            if ($(`#proposed_work_id option[value="${data.proposed_work_id}"]`).length) {
                $('#proposed_work_id').val(data.proposed_work_id).trigger('change');
            } else {
                // If the proposed work doesn't exist in the dropdown, add it
                $('#proposed_work_id').append(
                    $('<option>', {
                        value: data.proposed_work_id,
                        text: data.proposed_work_name || 'Selected Work',
                        selected: true
                    })
                ).trigger('change');
            }
        }
    }
    
    $('#project_name').val(data.project_name);
    $('#job_no').val(data.job_no);
    $('#client_name').val(data.client_name);
    // Format job_no_reference_date if it exists
    if (data.job_no_reference_date) {
        const refDate = new Date(data.job_no_reference_date);
        const formattedRefDate = refDate.toISOString().split('T')[0];
        $('#job_no_reference_date').val(formattedRefDate);
    } else {
        $('#job_no_reference_date').val('');
    }
    //asingment 
    $('#assignment_id').val(data.assignment_id);
    $('#estimated_amount').val(data.estimated_amount);
    $('#budget_head').val(data.budget_head);
    $('#length').val(data.length);
    $('#assigned_days').val(data.assigned_days);
    //Created By: , Created At:Last Updated By:Last Updated At:
    $('#created_by').val(data.creator_name);
    $('#created_at').val(data.created_at);
    $('#updated_by').val(data.updater_name);
    $('#updated_at').val(data.updated_at);
    //current department
    $('#current_dept_name').val(data.current_dept_name);
    $('#current_dept_id').val(data.current_dept_id);
   

   
    
    // Format dates if they exist
    if (data.start_date) {
        const startDate = new Date(data.start_date);
        const formattedStartDate = startDate.toISOString().split('T')[0]; // YYYY-MM-DD format
        $('#start_date').val(formattedStartDate);
    }
    
    if (data.end_date) {
        const endDate = new Date(data.end_date);
        const formattedEndDate = endDate.toISOString().split('T')[0]; // YYYY-MM-DD format
        $('#end_date').val(formattedEndDate);
    }
    
    // Location fields
    // First set the circle and trigger change to load divisions
    if (data.circle_id) {
        $('#id_circle').val(data.circle_id).trigger('change');
        
        // After circle is set and divisions are loaded, set division
        setTimeout(function() {
            if (data.division_id) {
                $('#id_division').val(data.division_id).trigger('change');
                
                // After division is set and subdivisions are loaded, set subdivision
                setTimeout(function() {
                    if (data.sub_id) {
                        $('#id_sub').val(data.sub_id).trigger('change');
                        
                        // After subdivision is set and talukas are loaded, set taluka
                        setTimeout(function() {
                            if (data.taluka_id) {
                                $('#id_taluka').val(data.taluka_id).trigger('change');
                            }
                        }, 500);
                    }
                }, 500);
            }
        }, 500);
    }
    
    // Also display the text values in case the IDs don't match correctly
    console.log('Circle: ' + data.circle_name + ', Division: ' + data.division_name + 
               ', Sub: ' + data.sub_name + ', Taluka: ' + data.taluka_name);
    
    // Status
    $('#status').val(data.status);
    
    // Set priority if it exists, otherwise default to 'Regular'
    if (data.priority) {
        $('#priority').val(data.priority);
    } else {
        $('#priority').val('Regular');
    }
    
    // Set department values after they are loaded
    loadDepartments(function() {
        setDepartmentValues(data);
    });
    
    // Display assigned employees if any
    displayAssignedEmployees(data.assigned_employees || []);
}

// Function to load departments
function loadDepartments(callback) {
    $.ajax({
        url: '<?php echo API_URL; ?>department',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
            console.log('Departments:', response);
            if (response.is_successful === "1" && response.data) {
                var options = '<option value="">Select Department</option>';
                $.each(response.data, function(index, dept) {
                    options += '<option value="' + dept.dept_id + '">' + dept.dept_name + '</option>';
                });
                
                // Populate all department dropdowns with the same options
                const $deptSelects = $('select[id$="_id"][id^="dept"]');
                $deptSelects.html(options);
                
                // Initialize Select2 for department dropdowns if not already initialized
                if (!$.fn.select2) {
                    console.warn('Select2 not loaded yet, will initialize later');
                } else {
                    $deptSelects.select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Department'
                    });
                }
                
                // Call the callback if provided
                if (typeof callback === 'function') {
                    callback();
                }
            } else {
                console.error('Failed to load departments:', response.errors || 'Unknown error');
            }
        },
        error: function(xhr, status, error) {
            console.error('API Error:', error);
            console.error('Response:', xhr.responseText);
            // Still call the callback to prevent UI from hanging
            if (typeof callback === 'function') {
                callback();
            }
        }
    });
}

// Direct API calls outside document.ready to ensure they run immediately
console.log('Starting API calls');
// Ensure jQuery is fully loaded before making API calls
$(function() {
    console.log('jQuery loaded, making API calls');
    loadProjectTypes();
    loadProposedWorks();
    loadCircles();
    loadDepartments(); // Load departments for dropdowns
    loadProjectData(); // Load project data for editing
});

// Function to format date for API
function formatDateForAPI(dateString) {
    if (!dateString) return '';
    
    // Check if the date is already in YYYY-MM-DD format
    if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
        return dateString;
    }
    
    // Parse the date and format it as YYYY-MM-DD
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        // Try parsing DD-MM-YYYY format
        const parts = dateString.split('-');
        if (parts.length === 3) {
            // Assuming DD-MM-YYYY format
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        console.error('Invalid date:', dateString);
        return '';
    }
    
    return date.toISOString().split('T')[0];
}

// Function to update project
function updateProject() {
    // Show loading state in button
    $('#saveIcon').hide();
    $('#savingLoader').show();
    $('#saveProject').prop('disabled', true);
    
    // Clear any previous error messages
    $('.error-feedback').text('');
    $('#formResult').removeClass('alert-danger alert-success').hide();
    
    // Get project ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const projectId = urlParams.get('id');
    
    if (!projectId) {
        $('#formResult').removeClass('alert-success').addClass('alert-danger')
            .html('Invalid project ID. Please go back to the projects list.').show();
        return;
    }
    
    // Format dates
    const startDate = formatDateForAPI($('#start_date').val());
    const endDate = formatDateForAPI($('#end_date').val());
    const dept1DueDate = formatDateForAPI($('#dept1_due_date').val());
    const dept2DueDate = formatDateForAPI($('#dept2_due_date').val());
    const dept3DueDate = formatDateForAPI($('#dept3_due_date').val());
    const dept4DueDate = formatDateForAPI($('#dept4_due_date').val());
    const dept5DueDate = formatDateForAPI($('#dept5_due_date').val());
    
    // Prepare data for API
    const requestData = {
        access_token: "<?php echo $_SESSION['access_token']; ?>",
        // Decode the base64 project_id before sending to API
        project_id: atob(projectId),
        // Convert project_type_id to integer
        project_type_id: parseInt($('#project_type_id').val()) || 0,
        // Add proposed work ID if selected
        proposed_work_id: $('#proposed_work_id').val() || null,
        project_name: $('#project_name').val(),
        job_no: $('#job_no').val(),
        job_no_reference_date: $('#job_no_reference_date').val(),
        client_name: $('#client_name').val(),
        // Ensure estimated_amount is always a number and defaults to 0
        estimated_amount: parseFloat($('#estimated_amount').val().trim() || 0) || 0,
        budget_head: $('#budget_head').val() || '',
        // Ensure length is always a number and defaults to 0
        length: parseFloat($('#length').val().trim() || 0) || 0,
        assigned_days: $('#assigned_days').val(),
        start_date: startDate,
        end_date: endDate,
        circle_id: parseInt($('#id_circle').val()) || 0,
        division_id: parseInt($('#id_division').val()) || 0,
        sub_id: parseInt($('#id_sub').val()) || 0,
        taluka_id: parseInt($('#id_taluka').val()) || 0,
        status: $('#status').val(),
        priority: $('#priority').val()
        
        // current_dept_id: $('#current_dept_id').val(),
        
    };
    
    // Add department 1 data if provided
    if ($('#dept1_id').val()) {
        requestData.dept1_id = parseInt($('#dept1_id').val()) || 0;
        requestData.dept1_assigned_days = parseInt($('#dept1_assigned_days').val()) || 0;
        if (dept1DueDate) {
            requestData.dept1_due_date = dept1DueDate;
        }
    }
    
    // Add department 2 data if provided
    if ($('#dept2_id').val()) {
        requestData.dept2_id = parseInt($('#dept2_id').val()) || 0;
        requestData.dept2_assigned_days = parseInt($('#dept2_assigned_days').val()) || 0;
        if (dept2DueDate) {
            requestData.dept2_due_date = dept2DueDate;
        }
    }
    
    // Add department 3 data if provided
    if ($('#dept3_id').val()) {
        requestData.dept3_id = parseInt($('#dept3_id').val()) || 0;
        requestData.dept3_assigned_days = parseInt($('#dept3_assigned_days').val()) || 0;
        if (dept3DueDate) {
            requestData.dept3_due_date = dept3DueDate;
        }
    }
    
    // Add department 4 data if provided
    if ($('#dept4_id').val()) {
        requestData.dept4_id = parseInt($('#dept4_id').val()) || 0;
        requestData.dept4_assigned_days = parseInt($('#dept4_assigned_days').val()) || 0;
        if (dept4DueDate) {
            requestData.dept4_due_date = dept4DueDate;
        }
    }
    
    // Add department 5 data if provided
    if ($('#dept5_id').val()) {
        requestData.dept5_id = $('#dept5_id').val();
        requestData.dept5_assigned_days = $('#dept5_assigned_days').val();
        if (dept5DueDate) {
            requestData.dept5_due_date = dept5DueDate;
        }
    }
    
    console.log('Sending update request:', requestData);
    
    // Make API request to update project
    $.ajax({
        url: '<?php echo API_URL; ?>project-update',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(requestData),
        success: function(response) {
            console.log('Update response:', response);
            
            if (response.is_successful === "1") {
                // Reset button state
                $('#savingLoader').hide();
                $('#saveIcon').show();
                $('#saveProject').prop('disabled', false);
                
                // Show success message using toast and redirect
                const successMessage = response.success_message || 'Project updated successfully!';
                console.log(successMessage);
                showToast(successMessage, true);
                // Redirect after a short delay to show the toast
                setTimeout(function() {
                    window.location.href = 'projects.php';
                }, 1000);
            } else {
                // Reset button state on error
                $('#savingLoader').hide();
                $('#saveIcon').show();
                $('#saveProject').prop('disabled', false);
                
                // Show error message
                const errorMessage = response.errors || 'Failed to update project';
                Swal.fire({
                    title: 'Error!',
                    text: typeof errorMessage === 'string' ? errorMessage : JSON.stringify(errorMessage),
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
                
                // Display field-specific errors if available
                if (response.errors && typeof response.errors === 'object') {
                    for (const field in response.errors) {
                        $('#' + field + '_error').text(Array.isArray(response.errors[field]) ? 
                            response.errors[field].join(', ') : response.errors[field]);
                    }
                }
            }
        },
        error: function(xhr, status, error) {
            // Reset button state on error
            $('#savingLoader').hide();
            $('#saveIcon').show();
            $('#saveProject').prop('disabled', false);
            
            // Show error message
            let errorMessage = 'Error updating project: ' + error;
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    errorMessage = typeof response.errors === 'string' ? 
                        response.errors : JSON.stringify(response.errors);
                }
            } catch (e) {
                console.error('Error parsing error response:', e);
            }
            
            console.error('API Error:', error);
            console.error('Response:', xhr.responseText);
        }
    });
}

$(document).ready(function() {
    // Initialize Select2 for all select2 elements except department dropdowns
    // We'll handle department dropdowns separately
    $('.select2:not([id^="dept"])').select2({
        theme: 'bootstrap4'
    });
    
    // Initialize date picker with dd/mm/yyyy format
    $('.datetimepicker-input').each(function() {
        $(this).datetimepicker({
            format: 'DD/MM/YYYY',
            useCurrent: false,
            locale: 'en-GB' // This sets the locale to UK which uses dd/mm/yyyy format
        });
    });
    
    // Also update the date display format for existing values
    function formatDateToDMY(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        
        return `${day}/${month}/${year}`;
    }
    
    // Format existing date values on page load
    $('.datetimepicker-input').each(function() {
        const $input = $(this);
        const currentValue = $input.val();
        if (currentValue) {
            $input.val(formatDateToDMY(currentValue));
        }
    });

    // // Link the two date pickers
    // $("#start_date_picker").on("change.datetimepicker", function (e) {
    //     $('#end_date_picker').datetimepicker('minDate', e.date);
    // });
    
    // $("#end_date_picker").on("change.datetimepicker", function (e) {
    //     $('#start_date_picker').datetimepicker('maxDate', e.date);
    });
    
    // Handle form submission
    $('#editProjectForm').submit(function(e) {
        e.preventDefault();
        updateProject();
    });
    
    // Direct click handler for the save button
    $('#saveProject').on('click', function(e) {
        e.preventDefault();
        updateProject();
    });
    
    // Handle circle selection change
    $('#id_circle').on('change', function() {
        var selectedCircleId = $(this).val();
        if (selectedCircleId) {
            loadDivisions(selectedCircleId);
        } else {
            $('#id_division').empty().append('<option value="">Select Division</option>');
        }
        // Reset dependent dropdowns
        $('#id_sub').empty().append('<option value="">Select Subdivision</option>');
        $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
    });

    // Handle division selection change
    $('#id_division').on('change', function() {
        var selectedDivisionId = $(this).val();
        if (selectedDivisionId) {
            loadSubDivisions(selectedDivisionId);
        } else {
            $('#id_sub').empty().append('<option value="">Select Subdivision</option>');
        }
        // Reset dependent dropdown
        $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
    });

    // Direct click handler for the save button
    $('#saveProject').on('click', function(e) {
        e.preventDefault();
        // submitProjectForm();
    });
    
    // Function to submit the project form



    // Add active class to navigation
    // $('#projects-menu').addClass('active');
    
    // API calls are already made when page loads

    // Handle subdivision selection change
    $('#id_sub').on('change', function() {
        var selectedSubId = $(this).val();
        if (selectedSubId) {
            loadTalukas(selectedSubId);
        } else {
            $('#id_taluka').empty().append('<option value="">Select Taluka</option>');
        }
    });

// API functions are defined at the top of the script
</script>

<?php include 'common/footer.php'; ?>