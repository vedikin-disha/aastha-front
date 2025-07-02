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
  div.dt-button-collection div.dropdown-menu {
    display: block;
    z-index: 2002;
    min-width: 100%;
    background-color: #30b8b9 !important; /* Add this line for red background */
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
  .dt-button-collection .dt-button {
    background-color: white !important;
    color: #212529 !important;
    /* border: 1px solid #dee2e6 !important; */
    /* margin: 2px 0; */
    width: 100%;
    text-align: left;
    padding: 0.5rem 1rem;
}

.dt-button-collection .dt-button:hover:not(.disabled) {
    background-color: none !important;
    color: #212529 !important;
}


  .btn-group {
      white-space: nowrap;
  }
  
  .dropdown-item.active {
        background-color: #30b8b9 !important;
    }

    .dropdown-item:active {
        background-color: #30b8b9 !important;
    }
    .select2-container--bootstrap4 .select2-results__option--highlighted, .select2-container--bootstrap4 .select2-results__option--highlighted.select2-results__option[aria-selected="true"] {
        background-color: #30b8b9 !important;
        color: #fff !important;
    }
    
</style>
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



<!-- DataTables CSS -->
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<!-- Buttons HTML5 -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<!-- Buttons ColVis -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>

<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Project List</h3>
                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                <div class="card-tools">
              
                    <a href="add-project" class="btn btn-success"><i class="fas fa-plus"></i> Add Project</a>
              
                </div>
                <?php endif; ?>
            </div>
        </div>
                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 || $_SESSION['emp_role_id'] == 3): ?>
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
                                <select name="taluka_id[]" id="id_assing" class="form-control select2 checkbox" style="width: 100%;" data-placeholder="Select Assign Employee">
                                    <option value="">All assignments</option>
                                </select>
                                <div class="invalid-feedback" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="input-group">
                                <button type="button" id="resetFilters" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
        <script>
        $(document).ready(function() {
            // Reset button click handler
            $('#resetFilters').click(function() {
                // Reset all select2 dropdowns to their default first option
                $('.select2').val('').trigger('change');
                // If you want to submit the form after resetting, uncomment the next line
                // $('form').submit();
            });
        });
        </script>
        <div class="card-body">
            <div class="new-pms-ap">
                <div class="table-responsive">
                    <table id="projectTable" class="table table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Job No.</th>
                                <th>Client Name</th>
                                <th>Project Duration</th>
                                <th>Probable Date of Completion</th>
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
        projectTable.ajax.reload();
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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        "language": {
            "lengthMenu": "Show _MENU_ entries"
        },
        "pageLength": 10,
        "dom": "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12 col-md-6'l>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "buttons": [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-primary',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-primary',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-primary',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                exportOptions: { columns: ':visible' }
            },
            {
               
                extend: 'colvis',
                text: '<i class="fas fa-columns"></i> Column Visibility',
                className: 'btn btn-primary',
                exportOptions: { columns: ':visible' }
            }
        ],
        "initComplete": function() {
            // Add margin to buttons container
            $('.dt-buttons').addClass('mb-3');
            
            // // Add Font Awesome icons to buttons
            // $('.buttons-copy').html('<i class="fas fa-copy"></i>');
            // $('.buttons-csv').html('<i class="fas fa-file-csv"></i>');
            // $('.buttons-excel').html('<i class="fas fa-file-excel"></i>');
            // $('.buttons-pdf').html('<i class="fas fa-file-pdf"></i>');
            // $('.buttons-print').html('<i class="fas fa-print"></i>');
            // $('.buttons-colvis').html('<i class="fas fa-eye"></i>'); 
        },
        "columns": [
            { 
                "data": "project_name", 
                "name": "project_name",
                "render": function(data, type, row) {
                    let priorityBadge = '';
                    if (row.priority && row.priority.toLowerCase() === 'high') {
                        priorityBadge = '<span class="badge bg-danger">!</span>';
                    }
                    return priorityBadge + '    ' + (data || 'Unnamed Project');
                }
            },
            { "data": "job_no", "name": "job_no" },
            { "data": "client_name", "name": "client_name" },
            { "data": "project_duration", "name": "project_duration", "orderable": false },
            { "data": "probable_date_of_completion", "name": "probable_date_of_completion", "orderable": false },
            { "data": "location", "name": "location", "orderable": false },
            { "data": "current_dept_name", "name": "current_dept_name" },
            {   
                "data": "assigned_emp_names",
                "orderable": false,
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
                    let view = '<a href="view-project?id=' + btoa(data) + '" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;" ><i class="fas fa-eye"></i> View</a>';
                    return view;
                }
            },
            <?php endif; ?>
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            {
                "data": "project_id",
                "orderable": false,
                "render": function(data, type, row) {
                    let actions = '<div class="btn-group">' +
                           '<a href="view-project?id=' + data + '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                    actions += ' <a href="edit-project?id=' + data + '" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>';
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
                
                // Only include sorting parameters if the column is orderable
                var orderColumn = d.order[0];
                var column = d.columns[orderColumn.column];
                
                var params = {
                    access_token: "<?php echo $_SESSION['access_token']; ?>",
                    limit: d.length,
                    page: pageNumber,
                    search: d.search.value
                };
                
                // Only add sorting parameters if the column is orderable
                if (column.orderable !== false) {
                    //first always proiprity high
                    if (column.data === 'priority') {
                        params.order_by = 'priority';
                        params.order_dir = 'desc';
                        return;
                    }
                    params.order_by = column.data || '';
                    params.order_dir = orderColumn.dir || 'asc';
                }

                var circle_id = $('#id_circle').val();
                var division_id = $('#id_division').val();
                var sub_id = $('#id_sub_division').val();
                var taluka_id = $('#id_taluka').val();
                var emp_id = $('#id_assing').val();
                var dept_id = $('#id_dept').val();

                if (circle_id) params.circle_id = parseInt(circle_id);
                if (division_id) params.division_id = parseInt(division_id);
                if (sub_id) params.sub_id = parseInt(sub_id);
                if (taluka_id) params.taluka_id = parseInt(taluka_id);
                if (emp_id) params.emp_id = parseInt(emp_id);
                if (dept_id) params.dept_id = parseInt(dept_id);
                
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
                    if (json.data && typeof json.data.total !== 'undefined') {
                        json.recordsTotal = parseInt(json.data.total) || 0;
                        json.recordsFiltered = parseInt(json.data.total) || 0;
                        
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
            { 
                "data": "project_name", 
                "name": "project_name",
                "render": function(data, type, row) {
                    let priorityBadge = '';
                    if (row.priority && row.priority.toLowerCase() === 'high') {
                        priorityBadge = '<span class="badge bg-danger">!</span>';
                    }
                    return priorityBadge + '    ' + (data || 'Unnamed Project');
                }
            },
            { "data": "job_no" },
            
            { "data": "client_name" },
            { 
                "data": "project_duration",
                "render": function(data, type, row) {
                    return data || '-';
                },
                "orderable": "false"
            },
            { "data": "probable_date_of_completion", "orderable": "false" },
            { "data": "location", "orderable": "false" },
            { 
                "data": "current_dept_name",
                "render": function(data) {
                    return data || '-';
                }
            },
            { 
                "data": "assigned_employees",
                "render": function(data, type, row) {
                    if (Array.isArray(data) && data.length > 0) {
                        var html = '<div class="d-flex flex-wrap gap-2">';
                        data.forEach(function(emp) {
                            var imgSrc = emp.profile_picture || 'assets/img/default-avatar.png';
                            html += `
                                <div class="d-flex flex-column align-items-center" style="width: 50px;">
                                    <img src="${imgSrc}" class="img-circle elevation-1" alt="User Image" style="width: 30px; height: 30px; object-fit: cover;">
                                    <small class="text-truncate" style="max-width: 100%;" title="${emp.name}">${emp.name.split(' ')[0]}</small>
                                </div>`;
                        });
                        html += '</div>';
                        return html;
                    }
                    return '<span class="text-muted">Not Assigned</span>';
                },
                "orderable": "false"
            },
            
           
            { 
                "data": "status",
                "render": function(data) {
                    var status = data ? data.toLowerCase() : '';
                    var statusClass = '';
                    var statusText = data || 'Pending';
                    
                    switch(status.toLowerCase()) {
                        case 'active':
                            statusClass = 'badge-active';
                            break;
                        case 'in progress':
                        case 'in-progress':
                            statusClass = 'badge-in-progress';
                            statusText = 'In Progress';
                            break;
                        case 'internal done':
                            statusClass = 'badge-info';
                            statusText = 'Internal Done';
                            break;
                        case 'ext - taluka':
                            statusClass = 'badge-warning';
                            statusText = 'Ext - Taluka';
                            break;
                        case 'ext - sub division':
                            statusClass = 'badge-warning';
                            statusText = 'Ext - Sub Division';
                            break;
                        case 'ext - division':
                            statusClass = 'badge-warning';
                            statusText = 'Ext - Division';
                            break;
                        case 'ext - circle':
                            statusClass = 'badge-warning';
                            statusText = 'Ext - Circle';
                            break;
                        case 'ext - govt.':
                            statusClass = 'badge-warning';
                            statusText = 'Ext - Govt.';
                            break;
                        case 'completed':
                            statusClass = 'badge-success';
                            statusText = 'Completed';
                            break;
                        case 'cancelled':
                            statusClass = 'badge-danger';
                            statusText = 'Cancelled';
                            break;
                        case 'planned':
                            statusClass = 'badge-planned';
                            break;
                        case 'pending':
                        default:
                            statusClass = 'badge-pending';
                            statusText = data || 'Pending';
                    }
                    
                    return '<span class="badge ' + statusClass + '">' + statusText + '</span>';
                }
            },
            <?php if ($_SESSION['emp_role_id'] != 1 && $_SESSION['emp_role_id'] != 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let view = '<a href="view-project?id=' + btoa(data) + '" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;"><i class="fas fa-eye"></i> View</a>';
                    return view;
                }
            },
            <?php endif; ?>
            <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
            {
                "data": "project_id",
                "render": function(data, type, row) {
                    let actions = '<div class="btn-group"style="gap:5px;">';
                    actions += '<a href="view-project?id=' + btoa(data) + '" class="btn btn-primary btn-sm rounded"><i class="fas fa-eye"></i> View</a>';
                    actions += ' <a href="edit-project?id=' + btoa(data) + '" class="btn btn-info btn-sm rounded"><i class="fas fa-edit"></i> Edit</a>';
                    actions += '</div>';
                    return actions;
                }
            }
            <?php endif; ?>
        ]
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
          theme: 'bootstrap4',
          placeholder: "Select employees",
          allowClear: true
        });
      },
      error: function (xhr, status, error) {
        console.error("Failed to load employees:", error);
      }
    });
  }


  $(document).ready(function () {
    loadAssign();
  });
  // Call the function on page load

</script>
<?php include 'common/footer.php'; ?>
