<?php include 'common/header.php'; ?>
<style>
  .input-group {
    margin-bottom: 1rem !important;
  }
  .form-control {
    height: 38px;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
  /* Custom styling for DataTables */
  .dataTables_filter {
      text-align: right;
      float: right;
      display: flex;
      align-items: center;
      justify-content: flex-end;
  }
  
  .dataTables_length {
      padding-top: 0.5rem;
      float: left;
  }
  
  .dataTables_filter input {
      margin-left: 0.5rem;
      display: inline-block;
      width: auto;
  }
  
  /* Clear floats */
  .dataTables_wrapper .row:after {
      content: "";
      display: table;
      clear: both;
  }
  .badge {
      font-size: 85%;
      padding: 0.4em 0.7em;
  }
  .badge-planned {
      background-color: #6c757d;
      color: white;
  }
  .badge-in-progress {
      background-color: #007bff;
      color: white;
  }
  .badge-active {
      background-color: #28a745;
      color: white;
  }
  .badge-pending {
      background-color: #ffc107;
      color: #212529;
  }
  .btn-group {
      white-space: nowrap;
  }
  
.select2-results__option {
  padding-left: 20px !important;
  position: relative;
}
.select2-results__option::before {
  content: "✔";
  position: absolute;
  left: 4px;
  top: 4px;
  display: none;
}
.select2-results__option[aria-selected=true]::before {
  display: block;
}

</style>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



<!-- DataTables CSS -->
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Project List</h3>
                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                <div class="card-tools">
                    <a href="add-project.php" class="btn btn-success">+ Add Project</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label for="id_circle" class="form-label">Circle</label>
                    <select name="circle_id" id="id_circle" class="form-control select2" style="width: 100%;" data-placeholder="Select Circle">
                        <option value="">All Circles</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="id_division" class="form-label">Division</label>
                    <select name="division_id" id="id_division" class="form-control select2" style="width: 100%;" data-placeholder="Select Division">
                        <option value="">All Divisions</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="id_sub_division" class="form-label">Sub Division</label>
                    <select name="subdivision_id" id="id_sub_division" class="form-control select2" style="width: 100%;" data-placeholder="Select Sub Division">
                        <option value="">All Sub Divisions</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="id_taluka" class="form-label">Taluka</label>
                    <select name="taluka_id" id="id_taluka" class="form-control select2" style="width: 100%;" data-placeholder="Select Taluka">
                        <option value="">All Talukas</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="id_dept" class="form-label">Department</label>
                    <div class="input-group">
                        <select name="department" id="id_dept" class="form-control select2" style="width: 100%;" data-placeholder="Select current department">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="id_assing" class="form-label">Assign</label>
                    <div class="input-group">
                        <select name="taluka_id[]" id="id_assing" class="form-control select2 checkbox" style="width: 100%;" data-placeholder="Select current department">
                            <option value="">All assignments</option>
                        </select>
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
            </form>
        </div>
            <table id="projectTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Job No</th>
                        <th>Client Name</th>
                        <th>Project Duration</th>
                        <th>Location</th>
                        <th>Department</th>
                        <th>Assigned Employees</th>
                        <th>Status</th>
                        <?php if ($_SESSION['emp_role_id'] != 1 && $_SESSION['emp_role_id'] != 2): ?>
                        <th>View</th>
                        <?php endif; ?>
                        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                        <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete project "<span id="projectNameToDelete"></span>"?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="css/all.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap 4 Theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%',
        allowClear: true
    });

    // Initialize filter dropdowns
    $('#id_circle, #id_division, #id_sub_division, #id_taluka, #id_dept, #id_assing').select2({
        theme: 'bootstrap4',
        width: '100%',
        allowClear: true,
        placeholder: 'Select an option'
    });

    // Initialize DataTable
    var projectTable = $('#projectTable').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "autoWidth": false,
        "responsive": true,
        "pagingType": "full_numbers",
        "dom": '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
        "displayStart": <?php echo (isset($_GET['page']) ? ((int)$_GET['page'] - 1) * (isset($_GET['limit']) ? (int)$_GET['limit'] : 10) : 0); ?>,
        "pageLength": <?php echo isset($_GET['limit']) ? (int)$_GET['limit'] : 10; ?>,
        "drawCallback": function(settings) {
            // Update URL with current page information without reloading
            var api = this.api();
            var pageInfo = api.page.info();
            var pageNum = pageInfo.page + 1;
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('page', pageNum);
            currentUrl.searchParams.set('limit', pageInfo.length);
            window.history.replaceState({}, '', currentUrl.toString());
            
            console.log('Draw callback - Page info:', pageInfo);
            console.log('Total records:', pageInfo.recordsTotal);
            console.log('Records per page:', pageInfo.length);
            console.log('Current page:', pageInfo.page);
        },
        "columns": [
            { "data": "project_name", "name": "project_name" },
            { "data": "job_no", "name": "job_no" },
            { "data": "client_name", "name": "client_name" },
            { "data": "project_duration", "name": "project_duration" },
            { "data": "location", "name": "location" },
            { "data": "current_dept_name", "name": "current_dept_name" },
            {   
                "data": "assigned_emp_names",
                "render": function(data, type, row) {
                    if (Array.isArray(data) && data.length > 0) {
                        return data.join(', ');
                    }
                    return 'Not Assigned';
                }
            },
            { 
                "data": "status",
                "render": function(data, type, row) {
                    return '<span class="badge badge-' + data + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                }
            },
            <?php if ($_SESSION['emp_role_id'] != 1 && $_SESSION['emp_role_id'] != 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let view = '<a href="view-project.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>';
                    return view;
                }
            },
            <?php endif; ?>
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let actions = '<div class="btn-group">' +
                           '<a href="view-project.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                    actions += ' <a href="edit-project.php?id=' + data + '" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>';
                    <?php endif; ?>
                    
                    actions += '</div>';
                    return actions;
                }
            }
            <?php endif; ?>
        ],
        "ajax": {
            url: '<?php echo API_URL; ?>project-listing',
            "type": "POST",
            "contentType": "application/json",
            "data": function(d) {
                // Calculate page number ensuring it's at least 1
                var calculatedPage = Math.floor(d.start / d.length) + 1;
                var pageNumber = Math.max(1, calculatedPage); // Ensure page is never less than 1
                
                var params = {
                    access_token: "<?php echo $_SESSION['access_token']; ?>",
                    limit: d.length,
                    page: pageNumber,
                    search: d.search.value
                };
                
                // Debug information
                console.log('Start:', d.start, 'Length:', d.length, 'Calculated page:', calculatedPage, 'Final page:', pageNumber);
                
                console.log('DataTables Request:', params);
                
                return JSON.stringify(params);
            },
            "dataSrc": function(json) {
                // Update total record count for pagination
                if (json.data && json.data.projects) {
                    return json.data.projects;
                }
                return [];
            },
            "dataFilter": function(data) {
                var json = JSON.parse(data);
                console.log('API Response:', json); // Log the API response for debugging
                
                // Check if the API call was successful
                if (json.is_successful === "1") {
                    // Make sure the response has the required DataTables properties
                    if (json.data && typeof json.data.total_count !== 'undefined') {
                        json.recordsTotal = parseInt(json.data.total_count) || 0;
                        json.recordsFiltered = parseInt(json.data.total_count) || 0;
                        
                        // Ensure we have at least as many records as we have items in the current page
                        if (json.data.projects && json.data.projects.length > 0 && json.recordsTotal < json.data.projects.length) {
                            json.recordsTotal = json.data.projects.length;
                            json.recordsFiltered = json.data.projects.length;
                        }
                    } else {
                        // Fallback values if the API doesn't return total_count
                        json.recordsTotal = (json.data && json.data.projects) ? json.data.projects.length * 3 : 0; // Multiply by estimated page count
                        json.recordsFiltered = json.recordsTotal;
                    }
                } else {
                    // Handle API error
                    console.error('API Error:', json.errors);
                    // Set empty data and show error message
                    json.data = { projects: [] };
                    json.recordsTotal = 0;
                    json.recordsFiltered = 0;
                    
                    // Show error toast
                    setTimeout(function() {
                        var errorMsg = '';
                        if (json.errors) {
                            for (var key in json.errors) {
                                errorMsg += key + ': ' + json.errors[key].join(', ') + '<br>';
                            }
                        } else {
                            errorMsg = 'Error loading data from server.';
                        }
                        
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Error',
                            body: errorMsg,
                            autohide: true,
                            delay: 5000
                        });
                    }, 500);
                }
                
                console.log('Modified Response:', json); // Log the modified response
                return JSON.stringify(json);
            }
        },
        "columns": [
            { "data": "project_name" },
            { "data": "job_no" },
            { "data": "client_name" },
            { 
                "data": "project_duration",
                "render": function(data, type, row) {
                    return data || '-';
                }
            },
            { "data": "location" },
            { 
                "data": "current_dept_name",
                "render": function(data) {
                    return data || '-';
                }
            },
            { 
                "data": "assigned_emp_names",
                "render": function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        return data.join(', ');
                    }
                    return 'Not Assigned';
                }
            },  
            { 
                "data": "status",
                "render": function(data) {
                    var status = data ? data.toLowerCase() : '';
                    var statusClass = '';
                    var statusText = data || 'Pending';
                    
                    switch(status) {
                        case 'active':
                            statusClass = 'badge-active';
                            break;
                        case 'in progress':
                        case 'in-progress':
                            statusClass = 'badge-in-progress';
                            statusText = 'In Progress';
                            break;
                        case 'planned':
                            statusClass = 'badge-planned';
                            break;
                        case 'pending':
                        default:
                            statusClass = 'badge-pending';
                            statusText = 'Pending';
                    }
                    
                    return '<span class="badge ' + statusClass + '">' + statusText + '</span>';
                }
            },
            <?php if ($_SESSION['emp_role_id'] != 1 && $_SESSION['emp_role_id'] != 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let view = '<a href="view-project.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>';
                    return view;
                }
            },
            <?php endif; ?>
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let actions = '<div class="btn-group">';
                    actions += '<a href="view-project.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    actions += ' <a href="edit-project.php?id=' + data + '" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>';
                    actions += '</div>';
                    return actions;
                }
            }
            <?php endif; ?>
        ]
    });

    // Make sure pagination is working correctly
    $(document).on('click', '.paginate_button', function(e) {
        console.log('Pagination button clicked');
    });
    
    // Initialize with the current page if it exists in the URL
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('page')) {
        var pageNum = parseInt(urlParams.get('page')) - 1; // DataTables uses 0-based index
        if (pageNum >= 0) {
            setTimeout(function() {
                table.page(pageNum).draw('page');
                console.log('Set page to:', pageNum);
            }, 100);
        }
    }
    
    // Listen for page changes
    table.on('page.dt', function() {
        var info = table.page.info();
        console.log('Page changed to:', info.page);
        
        // Force a redraw to ensure the server gets the correct page request
        setTimeout(function() {
            table.ajax.reload(null, false); // null callback, false to keep current page
        }, 0);
    });

    var projectIdToDelete = null;
    var projectNameToDelete = null;

    // Handle delete button click using event delegation
    $('#projectTable').on('click', '.delete-project', function() {
        projectIdToDelete = $(this).data('project-id');
        projectNameToDelete = $(this).data('project-name');
        $('#projectNameToDelete').text(projectNameToDelete);
        $('#deleteModal').modal('show');
    });

    // Handle confirm delete
    $('#confirmDelete').click(function() {
        if (!projectIdToDelete) return;
        
        $.ajax({
            url: '<?php echo API_URL; ?>delete-project',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: "<?php echo $_SESSION['access_token']; ?>",
                project_id: projectIdToDelete
            }),
            success: function(response) {
                if (response.is_successful === "1") {
                    // Reload the DataTable
                    table.ajax.reload();
                    
                    // Show success message
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        body: 'Project deleted successfully',
                        autohide: true,
                        delay: 3000
                    });
                } else {
                    // Show error message
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        body: response.errors ? Object.values(response.errors).join('<br>') : 'Error deleting project',
                        autohide: true,
                        delay: 3000
                    });
                }
            },
            error: function() {
                // Show error message
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Error deleting project. Please try again.',
                    autohide: true,
                    delay: 3000
                });
            },
            complete: function() {
                $('#deleteModal').modal('hide');
                projectIdToDelete = null;
                projectNameToDelete = null;
            }
        });
    });

    // Add active class to navigation
    $('#projects-menu').addClass('active');
});
</script>
<script>
      // Handle circle selection change
    $('#id_circle').on('change', function() {
        var selectedCircleId = $(this).val();
        if (selectedCircleId) {
            loadDivisions(selectedCircleId);
        } else {
            $('#id_division').empty().append('<option value="">All Divisions</option>');
        }
        // Reset dependent dropdowns
        $('#id_sub_division').empty().append('<option value="">All Sub Divisions</option>');
        $('#id_taluka').empty().append('<option value="">All Talukas</option>');
    });

    // Handle division selection change
    $('#id_division').on('change', function() {
        var selectedDivisionId = $(this).val();
        if (selectedDivisionId) {
            loadSubDivisions(selectedDivisionId);
        } else {
            $('#id_sub_division').empty().append('<option value="">All Sub Divisions</option>');
        }
        // Reset dependent dropdown
        $('#id_taluka').empty().append('<option value="">All Talukas</option>');
    });

    // Handle subdivision selection change
    $('#id_sub_division').on('change', function() {
        var selectedSubId = $(this).val();
        if (selectedSubId) {
            loadTalukas(selectedSubId);
        } else {
            $('#id_taluka').empty().append('<option value="">All Talukas</option>');
        }
        // Reload data with new filters
        loadPendingProjects();
        loadDelayedProjects();
    });

    // Handle filter changes
    $('#id_circle, #id_division, #id_sub_division, #id_taluka, #id_dept, #id_assing').on('change', function() {
        // Reload DataTable with new filters
        projectTable.ajax.reload();
    });

    // Initialize DataTable first for project-listing API
    var projectTable = $('#projectTable').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "columns": [
            { "data": "project_name", "name": "project_name" },
            { "data": "job_no", "name": "job_no" },
            { "data": "client_name", "name": "client_name" },
            { "data": "project_duration", "name": "project_duration" },
            { "data": "location", "name": "location" },
            { "data": "current_dept_name", "name": "current_dept_name" },
            {   
                "data": "assigned_emp_names",
                "render": function(data, type, row) {
                    if (Array.isArray(data) && data.length > 0) {
                        return data.join(', ');
                    }
                    return 'Not Assigned';
                }
            },
            { 
                "data": "status",
                "render": function(data, type, row) {
                    return '<span class="badge badge-' + data + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                }
            },
            <?php if ($_SESSION['emp_role_id'] != 1 && $_SESSION['emp_role_id'] != 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let view = '<a href="view-project.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>';
                    return view;
                }
            },
            <?php endif; ?>
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let actions = '<div class="btn-group">' +
                           '<a href="view-project.php?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                    actions += ' <a href="edit-project.php?id=' + data + '" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>';
                    <?php endif; ?>
                    
                    actions += '</div>';
                    return actions;
                }
            }
            <?php endif; ?>
        ],  
        "ajax": {
            url: '<?php echo API_URL; ?>project-listing',
                var deptId = $('#id_dept').val();

                if (circleId) params.circle_id = parseInt(circleId);
                if (divisionId) params.division_id = parseInt(divisionId);
                if (subId) params.sub_id = parseInt(subId);
                if (talukaId) params.taluka_id = parseInt(talukaId);
                if (empId) params.emp_id = parseInt(empId);
                if (deptId) params.dept_id = parseInt(deptId);

                return JSON.stringify(params);
            },
            "dataSrc": function(json) {
                if (json.is_successful === '1' && json.data && Array.isArray(json.data)) {
                    return json.data;
                }
                return [];
            }
        }
    });

    // After DataTable is initialized, load other data
    loadCircles(); // Load circles
    loadDepartments(); // Load departments
    loadAssign(); // Load assignments
        // Function to load circles
    function loadCircles() {
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
                if (response && Array.isArray(response)) {
                    var circleSelect = $('#id_circle');
                    circleSelect.empty();
                    circleSelect.append('<option value="">All Circles</option>');
                    
                    response.forEach(function(circle) {
                        circleSelect.append('<option value="' + circle.circle_id + '">' + circle.circle_name + '</option>');
                    });
                } else {
                    console.error('API Error:', 'Invalid response format');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching circles:', error);
            }
        });
    }

    // Function to load divisions based on selected circle
    function loadDivisions(circleId) {
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
                var divisionSelect = $('#id_division');
                divisionSelect.empty();
                divisionSelect.append('<option value="">All Divisions</option>');
                
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

    // Function to load subdivisions based on selected division
    function loadSubDivisions(divisionId) {
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
                var subDivisionSelect = $('#id_sub_division');
                subDivisionSelect.empty();
                subDivisionSelect.append('<option value="">All Sub Divisions</option>');
                
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

    // Function to load departments
function loadDepartments() {
   $.ajax({
      url: '<?php echo API_URL; ?>department',
      type: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      data: JSON.stringify({
        access_token: '<?php echo $_SESSION["access_token"]; ?>'
      }),
      success: function(response) {
        if (response.is_successful === '1' && response.data) {
          var deptSelect = $('#id_dept');
          response.data.forEach(function(dept) {
            deptSelect.append(new Option(dept.dept_name, dept.dept_id));
          });
          // Set the selected department if we have task data
          if (typeof taskData !== 'undefined' && taskData.dept_id) {
            deptSelect.val(taskData.dept_id);
          }
          deptSelect.trigger('change');
        } else {
          console.error('Error loading projects:', response.errors);
          $(document).Toasts('create', {
            class: 'bg-danger',
            title: 'Error',
            position: 'bottomRight',
            body: 'Failed to load projects. ' + response.errors,
            autohide: true,
            delay: 3000
          });
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading projects:', error);
        $(document).Toasts('create', {
          class: 'bg-danger',
          title: 'Error',
          position: 'bottomRight',
          body: 'Failed to load projects. Please try again.',
          autohide: true,
          delay: 3000
        });
      }
    });
}

    // Function to load talukas based on selected subdivision
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
    function loadAssign() {
    var apiUrl = '<?php echo API_URL; ?>user';
    var accessToken = "<?php echo $_SESSION['access_token']; ?>";

    $.ajax({
      url: apiUrl,
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ access_token: accessToken }),
      success: function (response) {
        const $select = $('#id_assing');
        $select.empty(); 
        $select.append('<option value="">All Employees</option>');

        if (response.is_successful == "1" && Array.isArray(response.data)) {
          response.data.forEach(function (emp) {
            if (emp.emp_status == 1) {
              $select.append(
                $('<option>', {
                  value: emp.emp_id,
                  text: emp.emp_name
                })
              );
            }
          });
        }

        $select.select2({
          closeOnSelect: false,
          placeholder: "Select employees",
          allowClear: true,
          templateResult: formatOptionWithCheckbox,
          templateSelection: formatSelection
        });
      },
      error: function (xhr, status, error) {
        console.error("Failed to load employees:", error);
      }
    });
  }

  function formatOptionWithCheckbox(option) {
    if (!option.id) return option.text;
    return $('<span><input type="checkbox" style="margin-right:10px;" />' + option.text + '</span>');
  }

  function formatSelection(selection) {
    return selection.text;
  }

  $(document).ready(function () {
    loadAssign();
  });
  // Call the function on page load

</script>
<?php include 'common/footer.php'; ?>
