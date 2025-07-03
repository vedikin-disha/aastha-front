<?php 
include 'common/header.php';
?>


<div class="">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Employee Wise Report</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filters Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filters</h3>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row">
                            <!-- Employee Dropdown -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Employee</label>
                                    <select class="form-control select2" id="employeeId" style="width: 100%;">
                                        <option value="">Select Employee</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Time Range Dropdown -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Time Range</label>
                                    <select class="form-control" id="timeRange">
                                        <option value="" selected>Select Time</option>
                                        <option value="today">Today</option>
                                        <option value="last_week">Last Week (Mon-Sun)</option>
                                        <option value="current_week">Current Week</option>
                                        <option value="current_month">Current Month</option>
                                        <option value="custom">Custom Date Range</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Custom Date Range (initially hidden) -->
                            <div class="col-md-4" id="customDateRange" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" class="form-control" id="fromDate">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" class="form-control" id="toDate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn px-5" style=" background-color: #30b8b9 !important; color:white">Apply</button>
                                <!-- <button type="reset" class="btn btn-default">Reset</button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        
            
            <!-- Charts Row -->
            <style>
                .chart-container {
                    position: relative;
                    width: 100%;
                    padding-bottom: 0;
                    margin-bottom: 20px;
                }
                .chart-wrapper {
                    position: relative;
                    height: 300px;
                    min-height: 300px;
                    width: 100%;
                }
                .chart-card {
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }
                .chart-card .card-body {
                    flex: 1;
                    padding: 15px;
                    display: flex;
                    flex-direction: column;
                    min-height: 0;
                }
                .chart-card .card-header {
                    padding: 0.75rem 1.25rem;
                }
                .no-data-message {
                    text-align: center;
                    padding: 20px;
                    color: #6c757d;
                    font-style: italic;
                }
            </style>
            
            <!-- Charts Container -->
            <div id="chartsContainer" class="row mt-4 d-none">
                <!-- Date Frame Chart -->
                <div class="col-md-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Date Frame Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-wrapper">
                                <canvas id="dateFrameChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Today's Chart -->
                <div class="col-md-6 mb-4">
                    <div class="card chart-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Today's Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-wrapper">
                                <canvas id="todayChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- No Data Message - Initially hidden -->
            <div id="noDataMessage" class="no-data-message d-none">
                <i class="fas fa-chart-pie fa-3x mb-3"></i>
                <h4>No data available</h4>
                <p>Select filters and click Apply to view charts</p>
            </div>
            
            <!-- Projects Table -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Projects</h3>
                </div>
                <div>
                <div class="card-body p-0" style="margin: 26px;">
                    <div class="table-responsive">
                        <table id="projectsTable" class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>Project Name</th>
                                    <th>Department</th>
                                    <th>Project Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Tasks Modal -->
            <div class="modal fade" id="tasksModal" tabindex="-1" role="dialog" aria-labelledby="tasksModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tasksModalLabel">Project Tasks</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="table-responsive">
                                <table id="tasksTable" class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Task Name</th>
                                            <th>Assigned To</th>
                                            <th>Assigned Duration</th>
                                            <th>Completed Duration</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'common/footer.php'; ?>

<!-- Required CSS Libraries -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"/>

<!-- Required JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for employee dropdown
    if ($.fn.select2) {
        $('#employeeId').select2({
            placeholder: 'Select Employee',
            allowClear: true
        });
        
        // Load employees
        loadEmployees();
    }
    
    // Toggle custom date range
    $('#timeRange').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#customDateRange').show();
        } else {
            $('#customDateRange').hide();
        }
    });
    
    // Form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadReport();
    });
    
    // Reset form
    $('button[type="reset"]').on('click', function() {
        $('#customDateRange').hide();
        $('#filterForm')[0].reset();
        $('#employeeId').val(null).trigger('change');
    });
    
    // Process data for charts and summary
    function processDataForCharts(projects) {
        const summary = {
            date_frame: {
                completed: 0,
                ongoing: 0,
                delayed: 0
            },
            today: {
                completed: 0,
                ongoing: 0,
                delayed: 0
            }
        };

        // Count tasks by status
        projects.forEach(project => {
            if (project.tasks && project.tasks.length > 0) {
                project.tasks.forEach(task => {
                    // Count for date frame status
                    if (task.date_frame_status === 'completed') summary.date_frame.completed++;
                    else if (task.date_frame_status === 'ongoing') summary.date_frame.ongoing++;
                    else if (task.date_frame_status === 'overdue') summary.date_frame.delayed++;
                    
                    // Count for today's status
                    if (task.today_status === 'completed') summary.today.completed++;
                    else if (task.today_status === 'ongoing') summary.today.ongoing++;
                    else if (task.today_status === 'overdue') summary.today.delayed++;
                });
            }
        });

        return summary;
    }

    // Format task details row
    function formatTaskDetails(tasks) {
        if (!tasks || tasks.length === 0) return '<div class="p-3">No tasks found</div>';
        
        let html = `
            <div class="task-details p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Task Name</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Assigned Duration</th>
                                <th class="text-center">Completed Duration</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Today's Status</th>
                            </tr>
                        </thead>
                        <tbody>`;
        
        tasks.forEach(task => {
            const priorityClass = task.task_priority?.toLowerCase() === 'high' ? 'badge bg-danger' : 'badge bg-secondary';
            const statusClass = {
                'completed': 'success',
                'ongoing': 'warning',
                'overdue': 'danger',
                'assigned': 'info'
            };
            
            const frameStatus = task.date_frame_status?.toLowerCase() || '';
            const todayStatus = task.today_status?.toLowerCase() || '';
            
            html += `
                            <tr>
                        
                                <td><strong>${task.task_name || '-'}</strong></td>

                                <td class="text-center"><span class="${priorityClass}">${task.task_priority.charAt(0).toUpperCase() + task.task_priority.slice(1) || 'Regular'}</span></td>
                                <td class="text-center">${task.assigned_duration || '-'}</td>
                                <td class="text-center">${task.completed_duration || '-'}</td>
                                <td class="text-center"><span class="badge bg-${statusClass[frameStatus] || 'secondary'}">${task.date_frame_status.charAt(0).toUpperCase() + task.date_frame_status.slice(1) || '-'}</span></td>
                                <td class="text-center"><span class="badge bg-${statusClass[todayStatus] || 'secondary'}">${task.today_status.charAt(0).toUpperCase() + task.today_status.slice(1) || '-'}</span></td>
                            </tr>`;
        });
        
        html += `
                        </tbody>
                    </table>
                </div>
            </div>`;
            
        return html;
    }

    // Initialize DataTable
    let projectsTable;
    
    function initializeProjectsTable() {
        if ($.fn.DataTable.isDataTable('#projectsTable')) {
            projectsTable.clear().destroy();
        }
    }
    
    // Setup details row handlers
    function setupDetailsHandlers() {
        $('#projectsTable tbody').off('click', 'td .details-control').on('click', 'td .details-control', function(e) {
            e.stopPropagation();
            const $icon = $(this);
            const tr = $icon.closest('tr');
            const row = projectsTable.row(tr);
            
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
            } else {
                // Open this row
                const rowData = row.data();
                const tasks = rowData.tasks || [];
                
                if (tasks.length > 0) {
                    // Show loading state
                    row.child('<div class="p-3 text-center"><i class="fas fa-spinner fa-spin"></i> Loading tasks...</div>').show();
                    
                    // Small delay to show loading state
                    setTimeout(() => {
                        // Format and show the tasks
                        const tasksHtml = formatTaskDetails(tasks);
                        row.child(tasksHtml).show();
                        
                        tr.addClass('shown');
                        $icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
                        
                        // Scroll to the expanded row if it's not fully visible
                        $('html, body').animate({
                            scrollTop: tr.offset().top - 50
                        }, 100);
                    }, 100);
                }
            }
        });
    }
    
    // Update projects table
    function updateProjectsTable(projects) {
        const tableBody = $('#projectsTable tbody');
        
        // Clear existing data
        if ($.fn.DataTable.isDataTable('#projectsTable')) {
            if (projectsTable) {
                projectsTable.clear().destroy();
            }
        } else {
            tableBody.empty();
        }
        
        // Initialize the DataTable
        projectsTable = $('#projectsTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            responsive: true,
            autoWidth: false,
            searching: true,
            ordering: true,
            paging: true,
            info: true,
            language: {
                lengthMenu: 'Show _MENU_ entries',
                search: 'Search:',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                },
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'No entries to show',
                infoFiltered: '(filtered from _MAX_ total entries)'
            },
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                // {
                //     extend: 'copy',
                //     text: '<i class="fas fa-copy"></i> Copy',
                //     className: 'btn btn-primary',
                //     exportOptions: { columns: ':visible' }
                // },
                {
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'btn btn-primary',
                    action: function() {
                        downloadReport('csv');
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    action: function() {
                        downloadReport('excel');
                    }
                },
                {
                    text: '<i class="fas fa-file-pdf"></i>PDF',
                    className: 'btn btn-primary',
                    action: function() {
                        downloadReport('pdf');
                    }
                },
                // {
                //     extend: 'print',
                //     text: '<i class="fas fa-print"></i> Print',
                //     className: 'btn btn-primary',
                //     exportOptions: { columns: ':visible' }
                // },
                // {
                //     extend: 'colvis',
                //     text: '<i class="fas fa-columns"></i> Columns',
                //     className: 'btn btn-primary',
                // }
            ],
            columnDefs: [
                { orderable: false, targets: 0 },
                { className: 'text-left', targets: [1, 2] },
                { className: 'text-center', targets: 3 },
                { width: '5%', targets: 0 } // Set width for expand/collapse column
            ],
            order: [[2, 'asc']], // Sort by project name by default
            drawCallback: function() {
                // Re-bind event handlers after table is drawn
                setupDetailsHandlers();
            }
        });
        
        if (!Array.isArray(projects)) {
            // Handle error case - show single column with error message
            tableBody.append('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
            return;
        }
        
        if (projects.length === 0) {
            tableBody.append('<tr><td colspan="4" class="text-center">No projects found</td></tr>');
            return;
        }
        
        // Add projects to the DataTable
        const rows = projects.map(project => {
            const hasTasks = project.tasks && project.tasks.length > 0;
            const priorityIcon = project.project_priority === 'High' ? ' <i class="fas fa-exclamation-circle text-danger"></i>' : '';
            const statusClass = {
                'completed': 'success',
                'ongoing': 'warning',
                'overdue': 'danger',
                'assigned': 'info'
            };
            
            const status = project.project_status?.toLowerCase() || '';
            const statusBadge = status ? 
                `<span class="badge bg-${statusClass[status] || 'secondary'}">${project.project_status}</span>` : 
                '-';
            
            // Create row data
            return {
                // This will be displayed in the table
                '0': hasTasks ? '<i class="fas fa-plus-circle text-primary details-control" style="cursor: pointer;"></i>' : '',
                '1': `${priorityIcon} ${project.project_name || '-'}`,
                '2': project.current_dept_name || '-',
                '3': statusBadge,
                // Store the tasks as a property that won't be displayed
                'tasks': project.tasks || []
            };
        });
        
        // Add all rows at once
        projectsTable.rows.add(rows).draw();
        
        // Set up event handlers after the table is drawn
        setupDetailsHandlers();
    }

    // Initialize DataTables
    const tasksTable = $('#tasksTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });
    loadEmployees();
    // Load employees function
    function loadEmployees() {
        $.ajax({
            url: '<?php echo API_URL; ?>user',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION["access_token"]; ?>'
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.is_successful === '1' && response.data) {
                    const select = $('#employeeId');
                    select.empty().append('<option value="">Select Employee</option>');
                    
                    response.data.forEach(function(employee) {
                        select.append(new Option(employee.emp_name, employee.emp_id));
                    });
                } else {
                    showError('Failed to load employees');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading employees:', error);
                showError('Failed to load employees. Please try again.');
            }
        });
    }
    
    // Function to download report in different formats
    function downloadReport(format) {
        const employeeId = $('#employeeId').val();
        const timeRange = $('#timeRange').val();
        let fromDate = '';
        let toDate = '';
        
        // Set date range based on selection
        const today = moment();
        switch(timeRange) {
            case 'today':
                fromDate = today.format('YYYY-MM-DD');
                toDate = today.format('YYYY-MM-DD');
                break;
            case 'last_week':
                fromDate = today.clone().subtract(1, 'weeks').startOf('week').format('YYYY-MM-DD');
                toDate = today.clone().subtract(1, 'weeks').endOf('week').format('YYYY-MM-DD');
                break;
            case 'current_week':
                fromDate = today.clone().startOf('week').format('YYYY-MM-DD');
                toDate = today.clone().endOf('week').format('YYYY-MM-DD');
                break;
            case 'current_month':
                fromDate = today.clone().startOf('month').format('YYYY-MM-DD');
                toDate = today.clone().endOf('month').format('YYYY-MM-DD');
                break;
            case 'custom':
                fromDate = $('#fromDate').val();
                toDate = $('#toDate').val();
                if (!fromDate || !toDate) {
                    showError('Please select both from and to dates');
                    return;
                }
                break;
        }
        
        if (!employeeId) {
            showError('Please select an employee');
            return;
        }
        
        // Show loading indicator
        const $btn = $(`button:contains('Download ${format.toUpperCase()}')`);
        const originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Downloading...').prop('disabled', true);
        
        // Prepare request data
        const requestData = {
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            emp_id: employeeId,
            from_date: fromDate,
            to_date: toDate,
            download_report_in: format
        };
        
        // Make API request with JSON
        fetch('<?php echo API_URL; ?>emp-report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/octet-stream' // Important for file download
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob();
        })
        .then(blob => {
            // Create a download link and trigger download
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            
            // Set the filename based on format
            const fileName = `employee_report_${fromDate}_to_${toDate}.${format}`;
            a.download = fileName;
            
            document.body.appendChild(a);
            a.click();
            
            // Clean up
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(error => {
            console.error('Error downloading report:', error);
            showError('Failed to download report. Please try again.');
        })
        .finally(() => {
            // Reset button state
            $btn.html(originalText).prop('disabled', false);
        });
    }
    
    // Load report function
    function loadReport() {
        const employeeId = $('#employeeId').val();
        const timeRange = $('#timeRange').val();
        let fromDate = '';
        let toDate = '';
        
        // Set date range based on selection
        const today = moment();
        switch(timeRange) {
            case 'today':
                fromDate = today.format('YYYY-MM-DD');
                toDate = today.format('YYYY-MM-DD');
                break;
            case 'last_week':
                fromDate = today.clone().subtract(1, 'weeks').startOf('week').format('YYYY-MM-DD');
                toDate = today.clone().subtract(1, 'weeks').endOf('week').format('YYYY-MM-DD');
                break;
            case 'current_week':
                fromDate = today.clone().startOf('week').format('YYYY-MM-DD');
                toDate = today.clone().endOf('week').format('YYYY-MM-DD');
                break;
            case 'current_month':
                fromDate = today.clone().startOf('month').format('YYYY-MM-DD');
                toDate = today.clone().endOf('month').format('YYYY-MM-DD');
                break;
            case 'custom':
                fromDate = $('#fromDate').val();
                toDate = $('#toDate').val();
                if (!fromDate || !toDate) {
                    showError('Please select both from and to dates');
                    return;
                }
                break;
        }
        
        // Load pie charts
        loadPieCharts(employeeId, fromDate, toDate);
        
        // Show loading state
        $('#summaryCards').addClass('opacity-50');
        
        // Call API to get report data
        // This is a placeholder - replace with your actual API endpoint
        const apiUrl = '<?php echo API_URL; ?>emp-report';
        const requestData = {
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            emp_id: employeeId,
            from_date: fromDate,
            to_date: toDate
        };
        
        $.ajax({
            url: apiUrl,
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(requestData),
            contentType: 'application/json',
            success: function(response) {
                if (response.is_successful === '1') {
                    // Check if there's an error in the response
                    if (response.is_successful !== '1') {
                        // Show error message in table
                        updateProjectsTable(null);
                        showError(response.errors || 'Failed to load report data');
                        return;
                    }
                    
                    // Process the data for summary and update projects table
                    if (response.data && Array.isArray(response.data)) {
                        const summary = processDataForCharts(response.data);
                        updateSummaryCards(summary);
                        updateProjectsTable(response.data);
                    } else {
                        updateProjectsTable([]);
                    }
                } else {
                    showError(response.message || 'Failed to load report data');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading report:', error);
                showError('Failed to load report. Please try again.');
            },
            complete: function() {
                // Hide loading state
                $('#summaryCards').removeClass('opacity-50');
            }
        });
    }
    
    // Update summary cards
    function updateSummaryCards(summary) {
        const totalAssigned = (summary.date_frame.completed || 0) + 
                            (summary.date_frame.ongoing || 0) + 
                            (summary.date_frame.delayed || 0);
                            
        $('#totalAssigned').text(totalAssigned);
        $('#totalCompleted').text(summary.date_frame.completed || 0);
        $('#totalOngoing').text(summary.date_frame.ongoing || 0);
        $('#totalDelayed').text(summary.date_frame.delayed || 0);
    }
    
    // Initialize charts
    let dateFrameChart = null;
    let todayChart = null;

    // Function to load pie chart data
    function loadPieCharts(employeeId, fromDate, toDate) {
        const apiUrl = '<?php echo API_URL; ?>emp-pie-chart';
        const requestData = {
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            emp_id: employeeId,
            from_date: fromDate,
            to_date: toDate
        };
        
        $.ajax({
            url: apiUrl,
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(requestData),
            contentType: 'application/json',
            success: function(response) {
                if (response.is_successful === '1' && response.data) {
                    renderCharts(response.data);
                } else {
                    console.error('Error loading pie charts:', response.errors || 'Failed to load chart data');
                    $('#chartsContainer').addClass('d-none');
                    $('#noDataMessage').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading pie charts:', error);
                $('#chartsContainer').addClass('d-none');
                $('#noDataMessage').removeClass('d-none');
            }
        });
    }

    function renderCharts(chartData) {
        // Show/hide containers based on data availability
        const hasData = chartData && (chartData.date_frame || chartData.today);
        
        if (hasData) {
            $('#chartsContainer').removeClass('d-none');
            $('#noDataMessage').addClass('d-none');
            
            // Render date frame chart if data exists
            if (chartData.date_frame && chartData.date_frame.datasets && chartData.date_frame.datasets.length > 0) {
                renderChart('dateFrameChart', {
                    labels: chartData.date_frame.labels || [],
                    datasets: [{
                        data: chartData.date_frame.datasets[0].data || [],
                        backgroundColor: chartData.date_frame.datasets[0].backgroundColor || [
                            '#28a745', // Green for completed
                            '#ffc107', // Yellow for ongoing
                            '#dc3545', // Red for delayed
                            '#A9A9A9'  // Gray for assigned
                        ]
                    }]
                });
            }
            
            // Render today's chart if data exists
            if (chartData.today && chartData.today.datasets && chartData.today.datasets.length > 0) {
                renderChart('todayChart', {
                    labels: chartData.today.labels || [],
                    datasets: [{
                        data: chartData.today.datasets[0].data || [],
                        backgroundColor: chartData.today.datasets[0].backgroundColor || [
                            '#28a745', // Green for completed
                            '#ffc107', // Yellow for ongoing
                            '#dc3545', // Red for delayed
                            '#A9A9A9'  // Gray for assigned
                        ]
                    }]
                });
            }
        } else {
            $('#chartsContainer').addClass('d-none');
            $('#noDataMessage').removeClass('d-none');
        }
    }
    
    function renderChart(chartId, chartData) {
        if (!chartData || !chartData.labels || !chartData.datasets) return;
        
        const canvas = document.getElementById(chartId);
        const parent = canvas.parentElement;
        const ctx = canvas.getContext('2d');
        
        // Calculate total for percentages
        const total = chartData.datasets[0].data.reduce((a, b) => a + b, 0);
        
        // Destroy existing chart if it exists
        if (chartId === 'dateFrameChart' && dateFrameChart) {
            dateFrameChart.destroy();
        } else if (chartId === 'todayChart' && todayChart) {
            todayChart.destroy();
        }
        
        // Create new chart
        const chart = new Chart(ctx, {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        
                                        return {
                                            text: `${label}: ${value} (${percentage}%)`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            lineCap: 'round',
                                            lineDash: [],
                                            lineDashOffset: 0,
                                            lineJoin: 'round',
                                            lineWidth: 1,
                                            strokeStyle: data.datasets[0].borderColor ? data.datasets[0].borderColor[i] : '#fff',
                                            pointStyle: 'circle',
                                            rotation: 0
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                cutout: '60%',
                radius: '90%'
            }
        });
        
        // Store chart instance
        if (chartId === 'dateFrameChart') {
            dateFrameChart = chart;
        } else if (chartId === 'todayChart') {
            todayChart = chart;
        }
    }
    
    // Load project tasks
    function loadProjectTasks(projectId) {
        // Show loading state
        const modal = $('#tasksModal');
        const modalBody = modal.find('.modal-body');
        modalBody.addClass('loading');
        
        // Call API to get project tasks
        // This is a placeholder - replace with your actual API endpoint
        const apiUrl = '<?php echo API_URL; ?>project-tasks';
        const requestData = {
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            project_id: projectId
        };
        
        $.ajax({
            url: apiUrl,
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(requestData),
            contentType: 'application/json',
            success: function(response) {
                if (response.is_successful === '1') {
                    updateTasksTable(response.tasks || []);
                    modal.modal('show');
                } else {
                    showError(response.message || 'Failed to load project tasks');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading project tasks:', error);
                showError('Failed to load project tasks. Please try again.');
            },
            complete: function() {
                modalBody.removeClass('loading');
            }
        });
    }
    
    // Update tasks table
    function updateTasksTable(tasks) {
        const table = $('#tasksTable').DataTable();
        table.clear();
        
        tasks.forEach(function(task) {
            const priorityIcon = task.is_high_priority ? ' <i class="fas fa-exclamation-circle text-danger" title="High Priority"></i>' : '';
            const row = [
                task.task_name + priorityIcon,
                task.assigned_to || '-',
                formatDate(task.start_date),
                formatDate(task.end_date),
                getStatusBadge(task.status)
            ];
            
            table.row.add(row);
        });
        
        table.draw();
    }
    
    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return '-';
        return moment(dateString).format('DD MMM YYYY');
    }
    
    // Helper function to get status badge
    function getStatusBadge(status) {
        if (!status) return '-';
        
        const statusMap = {
            'completed': { class: 'success', text: 'Completed' },
            'ongoing': { class: 'warning', text: 'Ongoing' },
            'delayed': { class: 'danger', text: 'Delayed' },
            'not_started': { class: 'secondary', text: 'Not Started' }
        };
        
        const statusInfo = statusMap[status.toLowerCase()] || { class: 'info', text: status };
        return `<span class="badge bg-${statusInfo.class}">${statusInfo.text}</span>`;
    }
    
    // Show error message
    function showError(message) {
        // Using Toastr for notifications (assuming it's included in your layout)
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }
});
</script>

<style>
    /* Expand/collapse icon styling */
    .details-control {
        cursor: pointer;
        text-align: center;
        width: 40px;
    }
    
    /* Make project name clickable */
    #projectsTable tbody td:nth-child(2) {
        cursor: pointer;
    }
    
    .details-control:hover {
        background-color: #f8f9fa;
    }

    /* Hide default DataTables row details control */
    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
        display: none !important;
    }
    
    .detail-row {
        background-color: #f8f9fa;
    }
    
    .task-details {
        background-color: #fff;
        border-left: 4px solid #007bff;
    }
    
    .task-details h6 {
        color: #495057;
        margin-bottom: 1rem;
    }
    
    .task-details .table {
        margin-bottom: 0;
    }
    
    .task-details .table th {
        white-space: nowrap;
    }

.loading {
    position: relative;
    min-height: 100px;
}
.loading:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7) url('data:image/svg+xml;utf8,<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>') center no-repeat;
    background-size: 40px;
}
</style>

