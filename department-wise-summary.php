<?php include 'common/header.php'; ?>

<!-- DataTables & Select2 CSS -->
<link rel="stylesheet" href="css/select2.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/select2-bootstrap4.min.css">


<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"></script>


<style>

  .form-control {

    height: 38px;

  }
  

  .alert-danger {

    margin-top: 10px;

    padding: 10px;

    border-radius: 4px;

  }

  .dt-buttons {
    margin-bottom: 15px;
  }
  
  .dt-button {
    margin-right: 5px;
    margin-bottom: 5px;
  }
  
  .dt-button-collection .dropdown-menu {
    margin-top: 5px;
  }
  
  /* Ensure buttons are visible */
  .dataTables_wrapper .dt-buttons {
    display: inline-block;
    float: left;
    /* margin-right: 10px; */
  }
  
  /* Fix for button dropdowns */
  .dt-button-collection {
    position: absolute;
    z-index: 2001;
  }

  .dt-buttons-wrapper {

    padding: 10px 0;

  }

  .dt-buttons .btn {

    margin-right: -3px;

  }

  .dt-button-collection {

    padding: 8px;

  }

  .dt-button-collection .btn {

    display: block;

    margin: 5px 0;

    width: 100%;

    text-align: left;

  }
  
  /* Style for Select2 dropdown options */
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #ececec !important;
    color: #212529 !important;
  }
  
  .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #30b8b9 !important;
    color: white !important;
  }
  
  .select2-container--default .select2-selection--single {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    height: 38px;
    padding: 0.375rem 0.75rem;
  }

</style>



<div class="container-fluid mt-4">

    <!-- Filters --><div class="card card-primary card-outline">

    <div class="card-header">

        <h3 class="card-title">Department Wise Summary</h3>

    </div>

    <div class="card-body">

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

            </form>

        </div>

    



    <div class="row mt-4 p-3">

        <!-- Pending Projects Table -->

        <div class="col-md-6">

            <div class="card card-primary card-outline">

                <div class="card-header">

                    <h3 class="card-title">Department Wise Pending Projects</h3>

                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="pending-projects-table" class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>Department Name</th>

                                    <th>Pending Projects</th>

                                </tr>

                            </thead>

                            <tbody>

                                <!-- Table data will be dynamically loaded -->

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>



        <!-- Delayed Projects Table -->

        <div class="col-md-6">

            <div class="card card-primary card-outline">

                <div class="card-header">

                    <h3 class="card-title">Department Wise Delayed Projects</h3>

                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="delayed-projects-table" class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>Department Name</th>

                                    <th>Delayed Projects</th>

                                </tr>

                            </thead>

                            <tbody>

                                            <!-- data table data -->

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

</div>



<?php include 'common/footer.php'; ?>

<style>
    .dt-buttons {
    margin-bottom: 15px;
}

.dt-button {
    margin-right: 5px;
    margin-bottom: 5px;
}

</style>

<!-- DataTables & Select2 JS -->

<script src="js/jquery.min.js"></script>

<script src="js/select2.full.min.js"></script>

<script src="js/jquery.dataTables.min.js"></script>

<script src="js/dataTables.bootstrap4.min.js"></script>

<script src="js/dataTables.responsive.min.js"></script>

<script src="js/responsive.bootstrap4.min.js"></script>

<!-- jQuery first, then DataTables, then Buttons, then other extensions -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<!-- Local scripts -->
<script src="js/common.js"></script>


<script>

$(document).ready(function() {

    // Initialize Select2

    $('#id_circle').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Select an option',
        allowClear: true
    });

    $('#id_division').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: 'Select an option',
        allowClear: true
    });

    $('#id_sub_division').select2({
        theme: 'bootstrap4',
        width: '100%',
        allowClear: true
    });

    $('#id_taluka').select2({
        theme: 'bootstrap4',
        width: '100%',
        allowClear: true
    });

    // DataTable initialization with export buttons
    $('#pending-projects-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
        // copy,csv,excel,pdf,[print],column visibility
        {
            extend: 'copy',
            text: '<i class="fas fa-copy"></i> Copy',
            className: 'btn btn-primary',
            title: 'Department Wise Summary',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'csv',
            text: '<i class="fas fa-file-csv"></i> CSV',
            className: 'btn btn-primary',
            title: 'Department Wise Summary',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'xlsx',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-primary',
            title: 'Department Wise Summary',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'pdf',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn btn-primary',
            title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
            
        ],
        language: {
            buttons: {
                copyTitle: 'Copied to clipboard',
                copySuccess: {
                    _: '%d rows copied',
                    1: '1 row copied'
                }
            }
        },
        responsive: true
    });

    $('#delayed-projects-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            // copy,csv,excel,pdf,[print],column visibility
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-primary',
                title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv"></i> CSV',
                className: 'btn btn-primary',
                title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'xlsx',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary',
                title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-primary',
                title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary',
                title: 'Department Wise Summary',
                exportOptions: {
                    columns: ':visible'
                }
            },
           
           
        ],
        language: {
            buttons: {
                copyTitle: 'Copied to clipboard',
                copySuccess: {
                    _: '%d rows copied',
                    1: '1 row copied'
                }
            }
        },
        responsive: true
    });

    // Initialize DataTables variables
    var pendingTable, delayedTable;
    function initializeDataTables() {
        // Destroy existing instances if they exist
        if ($.fn.DataTable.isDataTable('#pending-projects-table')) {
            $('#pending-projects-table').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#delayed-projects-table')) {
            $('#delayed-projects-table').DataTable().destroy();
        }

        // Common DataTable configuration
        var commonConfig = {
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            serverSide: false,
            processing: true,
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-sm btn-primary mr-1 mb-1'
                    }
                },
                buttons: [
                    {
                        extend: 'collection',
                        text: '<i class="fas fa-download"></i> Export',
                        className: 'btn-info',
                        buttons: [
                            { extend: 'copy', className: 'btn-sm' },
                            { extend: 'xlsx', className: 'btn-sm' },
                            { extend: 'csv', className: 'btn-sm' },
                            { extend: 'pdf', className: 'btn-sm' },
                            { extend: 'print', className: 'btn-sm' }
                        ]
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-eye"></i> Columns',
                        className: 'btn-secondary btn-sm'
                    }
                ]
            },
            initComplete: function() {
                // Ensure buttons container is visible
                this.api().buttons().container().appendTo($('#pending-projects-table_wrapper .col-md-6:eq(0)'));
            }
        };

        // Initialize Pending Projects Table
        var pendingTable = $('#pending-projects-table').DataTable($.extend(true, {}, commonConfig, {
            data: [],
            columns: [
                { data: "dept_name", title: "Department Name" },
                { data: "no_pending_projects", title: "Pending Projects" }
            ]
        }));

        // Initialize Delayed Projects Table
        var delayedTable = $('#delayed-projects-table').DataTable($.extend(true, {}, commonConfig, {
            data: [],
            columns: [
                { data: "dept_name", title: "Department Name" },
                { data: "no_delayed_projects", title: "Delayed Projects" }
            ]
        }));
        
        // Move buttons to their containers
        pendingTable.buttons().container().appendTo('#pending-projects-table_wrapper .col-md-6:eq(0)');
        delayedTable.buttons().container().appendTo('#delayed-projects-table_wrapper .col-md-6:eq(0)');
    }


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

                    if (response.length > 0) {

                        response.forEach(function(taluka) {

                            talukaSelect.append('<option value="' + taluka.taluka_id + '">' + taluka.taluka_name + '</option>');

                        });

                    } else {

                        showToast('No talukas found for the selected criteria', false);

                    }

                } else {

                    showToast('Invalid response format while loading talukas', false);

                }

            },

            error: function(xhr, status, error) {

                showToast('Error loading talukas. Please try again.', false);

            }

        });

    }



    // Function to load pending projects

    function loadPendingProjects() {
        var requestData = {
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        };

        // Add filter values if selected
        var circleId = $('#id_circle').val();
        var divisionId = $('#id_division').val();
        var subDivisionId = $('#id_sub_division').val();
        var talukaId = $('#id_taluka').val();

        if (circleId) requestData.circle_id = circleId;
        if (divisionId) requestData.division_id = divisionId;
        if (subDivisionId) requestData.sub_division_id = subDivisionId;
        if (talukaId) requestData.taluka_id = talukaId;

        // Show loading state
        if ($.fn.DataTable.isDataTable('#pending-projects-table')) {
            $('#pending-projects-table').DataTable().clear().draw();
        }

        $.ajax({
            url: '<?php echo API_URL; ?>department-pending',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(requestData),
            success: function(response) {
                if (response && response.is_successful === '1' && response.data) {
                    var table = $('#pending-projects-table').DataTable();
                    table.clear();
                    
                    response.data.forEach(function(item) {
                        table.row.add([
                            item.dept_name || '-',
                            item.no_pending_projects || '0'
                        ]);
                    });
                    
                    table.draw();
                } else {
                    console.error('API Error:', response.errors || 'Unknown error');
                    alert('Failed to fetch department pending data: ' + (response.errors || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching department-pending data:', error);
                alert('Failed to fetch department pending data. Please try again.');
            }
        });
    }



    // Function to load delayed projects

    function loadDelayedProjects() {
        var requestData = {
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        };

        // Add filter values if selected
        var circleId = $('#id_circle').val();
        var divisionId = $('#id_division').val();
        var subDivisionId = $('#id_sub_division').val();
        var talukaId = $('#id_taluka').val();

        if (circleId) requestData.circle_id = circleId;
        if (divisionId) requestData.division_id = divisionId;
        if (subDivisionId) requestData.sub_division_id = subDivisionId;
        if (talukaId) requestData.taluka_id = talukaId;

        // Show loading state
        if ($.fn.DataTable.isDataTable('#delayed-projects-table')) {
            $('#delayed-projects-table').DataTable().clear().draw();
        }

        $.ajax({
            url: '<?php echo API_URL; ?>department-delayed',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(requestData),
            success: function(response) {
                if (response && response.is_successful === '1' && response.data) {
                    var table = $('#delayed-projects-table').DataTable();
                    table.clear();
                    
                    response.data.forEach(function(item) {
                        table.row.add([
                            item.dept_name || '-',
                            item.no_delayed_projects || '0'
                        ]);
                    });
                    
                    table.draw();
                } else {
                    console.error('API Error:', response.errors || 'Unknown error');
                    alert('Failed to fetch department delayed data: ' + (response.errors || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching department-delayed data:', error);
                alert('Failed to fetch department delayed data. Please try again.');
            }
        });
    }



    // Load circles and tables when page loads

    loadCircles();

    loadPendingProjects();

    loadDelayedProjects();



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



    // Handle taluka selection change

    $('#id_taluka').on('change', function() {

        // Reload data with new filters

        loadPendingProjects();

        loadDelayedProjects();

    });



    // Add change handlers for circle and division to reload data

    $('#id_circle').on('change', function() {

        loadPendingProjects();

        loadDelayedProjects();

    });



    $('#id_division').on('change', function() {

        loadPendingProjects();

        loadDelayedProjects();

    });

});

</script>

