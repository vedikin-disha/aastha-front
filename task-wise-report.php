<?php include 'common/header.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables & Extensions -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<style>
    td.details-control {
        text-align: center;
        cursor: pointer;
    }
    /* .flex-wrap {
      bottom: -30px;
    } */
    td.details-control::before {
        content: '+';
        color: white;
        background-color: #28a745; /* Green */
        font-weight: bold;
        display: inline-block;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        border-radius: 4px;
    }
    tr.shown td.details-control::before {
        content: '-';
        background-color: #dc3545; /* Red */
    }
    .btn-group>.btn:not(:last-child):not(.dropdown-toggle){
       font-size: 17px;
    }
    .btn-group>.btn:not(:first-child) {
        font-size: 17px;
    }

    .project-row {
        cursor: pointer;
        background-color: #f8f9fa;
    }
    .project-row:hover {
        background-color: #e9ecef;
    }
    .department-row {
        cursor: pointer;
        background-color: #e9ecef;
    }
    .department-row:hover {
        background-color: #dee2e6;
    }
    .task-row {
        background-color: #ffffff;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.85em;
        font-weight: 500;
    }
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-ongoing {
        background-color: #cce5ff;
        color: #004085;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
            <h3 class="card-title">Task Wise Report</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <!-- Buttons will be placed here -->

                </div>
            </div>
            <div class="table-responsive">
                <table id="taskReportTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>    </th> <!-- For controls -->
                        <th>Project Name</th>
                        <th>Total Tasks</th>
                        <th>Completed</th>
                        <th>Pending</th>
                        <th>Ongoing</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody">
                    <!-- Data will be loaded by JavaScript -->
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<!-- Department Tasks Modal -->
<div class="modal fade" id="departmentTasksModal" tabindex="-1" role="dialog" aria-labelledby="departmentTasksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentTasksModalLabel">Department Tasks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="departmentTasksTable">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Assigned To</th>
                            <th>Assigned Duration</th>
                            <th>Completed Duration</th>
                            <th>Status</th>
                            
                        </tr>
                    </thead>
                    <tbody id="departmentTasksBody">
                        <!-- Task rows will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let allProjectsData = []; // To store the full data from API
        
    // Generates DIV-based structure for departments, which is more stable than nested tables.
    function formatDepartments(projectData) {
        if (!projectData || !projectData.tasks || projectData.tasks.length === 0) {
            return '<div class="p-3 text-center">No tasks or departments found for this project.</div>';
        }

        const departments = projectData.tasks.reduce((acc, task) => {
            if (task && task.dept_id) {
                if (!acc[task.dept_id]) {
                    acc[task.dept_id] = {
                        id: task.dept_id,
                        name: task.dept_name,
                        tasks: [],
                        counts: { completed: 0, pending: 0, ongoing: 0 }
                    };
                }
                acc[task.dept_id].tasks.push(task);

                // Normalize status by converting to lowercase
                const status = String(task.task_status || '').toLowerCase().trim();
                if (status.includes('done') || status.includes('complete')) {
                    acc[task.dept_id].counts.completed++;
                } else if (status.includes('pending') || status.includes('to do')) {
                    acc[task.dept_id].counts.pending++;
                } else if (status.includes('in progress') || status.includes('on going')) {
                    acc[task.dept_id].counts.ongoing++;
                }
            }
            return acc;
        }, {});

        let deptHtml = '<div class="p-3 bg-light">';
        Object.values(departments).forEach(dept => {
            deptHtml += `<div class="department-container border-bottom mb-2 pb-2">
                            <div class="department-row p-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-plus-circle text-primary mr-2"></i>${dept.name}</span>
                                <div>
                                    <span class="badge badge-secondary mr-1">Total: ${dept.tasks.length}</span>
                                    <span class="badge badge-success mr-1">Completed: ${dept.counts.completed}</span>
                                    <span class="badge badge-warning mr-1">To Do: ${dept.counts.pending}</span>
                                    <span class="badge badge-info">In Progress: ${dept.counts.ongoing}</span>
                                </div>
                            </div>`;
            const tasksHtml = formatTasks(dept.tasks);
            deptHtml += `<div class="tasks-container" style="display: none; padding-left: 20px;">${tasksHtml}</div>
                         </div>`;
        });
        deptHtml += '</div>';
        return deptHtml;
    }

    function formatTasks(tasks) {
        if (!tasks || tasks.length === 0) {
            return '<div class="p-3 text-center">No tasks found for this department.</div>';
        }

        let tasksHtml = '<div class="p-3"><table class="table table-bordered table-sm">';
        tasksHtml += '<thead class="thead-light"><tr><th>Task</th><th>Assigned To</th><th>Task Duration</th><th>Completed Duration</th><th>Status</th></tr></thead><tbody>';
        tasks.forEach(task => {
            const statusClass = getStatusClass(task.task_status);
            tasksHtml += `<tr>
                            <td>${task.task_name || 'N/A'}</td>
                            <td>${task.assigned_emp_name || 'Unassigned'}</td>
                            <td>${task.task_duration}</td>
                            <td>${task.completed_duration}</td>
                            <td><span class="badge ${statusClass}">${task.task_status || 'N/A'}</span></td>
                           
                          </tr>`;
        });
        tasksHtml += '</tbody></table></div>';
        return tasksHtml;
    }    

        function getStatusClass(status) {
            if (!status) return 'badge-secondary';
            // Normalize status to handle variations like 'On Going', 'To Do', etc.
            const n = String(status).toLowerCase().trim();
            if (n.includes('done') || n.includes('complete')) return 'badge-success';
            if (n.includes('pending') || n.includes('to do')) return 'badge-warning';
            if (n.includes('in progress') || n.includes('on going')) return 'badge-info';
            if (n.includes('overdue')) return 'badge-danger';
            return 'badge-secondary';
        }

        // Custom action for export buttons to include nested data
        const exportAction = function(e, dt, button, config) {
            const projects = allProjectsData;
            const flatData = [];

            projects.forEach(project => {
                if (project.tasks && project.tasks.length > 0) {
                    project.tasks.forEach(task => {
                        flatData.push({
                            project: project.project_name,
                            department: task.dept_name,
                            task: task.task_name,
                            assigned: task.assigned_emp_name || 'Unassigned',
                            status: task.task_status || 'N/A'
                        });
                    });
                } else {
                    flatData.push({
                        project: project.project_name,
                        department: 'N/A',
                        task: 'N/A',
                        assigned: 'N/A',
                        status: 'N/A'
                    });
                }
            });

            const tempTable = $('<table class="d-none"></table>');
            $('body').append(tempTable);

            const tempDt = tempTable.DataTable({
                data: flatData,
                columns: [
                    { title: 'Project', data: 'project' },
                    { title: 'Department', data: 'department' },
                    { title: 'Task', data: 'task' },
                    { title: 'Assigned To', data: 'assigned' },
                    { title: 'Task Duration', data: 'task_duration' },
                    { title: 'Completed Duration', data: 'completed_duration' },
                    { title: 'Status', data: 'status' }
                ],
                dom: 'B',
                buttons: [{
                    extend: config.extend,
                    title: 'Task-Wise Report',
                    customize: function (win) {
                        // Custom styling for the print view
                        if (config.extend === 'print') {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                        }
                    }
                }]
            });

            tempDt.buttons(0).trigger();
            tempDt.destroy();
            tempTable.remove();
        };

        // Function to handle report downloads
        function downloadTaskReport(format) {
            // Show loading state
            const loading = $('<div class="loading-overlay">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="sr-only">Loading...</span></div></div>');
            $('body').append(loading);
            
            // Prepare request data
            const requestData = {
                access_token: '<?php echo $_SESSION["access_token"]; ?>',
                download_report_in: format
            };
            
            // Make the API request
            fetch('<?php echo API_URL; ?>project-task-listing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/octet-stream'
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
                // Create a URL for the blob
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                
                // Set the filename based on the format
                const timestamp = new Date().toISOString().slice(0, 10);
                let filename = 'task_report_' + timestamp;
                
                switch(format) {
                    case 'pdf':
                        filename += '.pdf';
                        break;
                    case 'excel':
                        filename += '.xlsx';
                        break;
                    case 'csv':
                        filename += '.csv';
                        break;
                    default:
                        filename += '.pdf';
                }
                
                // Trigger download
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                
                // Clean up
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            })
            .catch(error => {
                console.error('Error downloading report:', error);
                alert('Error downloading report. Please try again.');
            })
            .finally(() => {
                // Remove loading state
                loading.remove();
            });
        }

        // Initialize the DataTable
        const taskTable = $('#taskReportTable').DataTable({
            ajax: {
                url: '<?php echo API_URL; ?>project-task-listing',
                type: 'POST',
                cache: false, // Prevents browser from caching the AJAX request
                contentType: 'application/json',
                data: function(d) {
                    return JSON.stringify({ access_token: '<?php echo $_SESSION["access_token"]; ?>', "limit": 10 });
                },
                dataSrc: function(json) {
                    if (json && json.data && Array.isArray(json.data.projects)) {
                        allProjectsData = json.data.projects;
                    } else {
                        allProjectsData = [];
                        console.error("API response is not in the expected format:", json);
                    }
                    return allProjectsData;
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    $('.dataTables_empty').html('Failed to load data. Please try again.');
                }
            },
            columns: [
                { className: 'details-control', orderable: false, data: null, defaultContent: '' },
                { data: 'project_name' },
                { data: 'total_tasks' },
                { data: 'completed_task', render: function(data) { return `<span class="badge badge-success">${data || 0}</span>`; } },
                { data: 'pending_task', render: function(data) { return `<span class="badge badge-warning">${data || 0}</span>`; } },
                { data: 'ongoing_task', render: function(data) { return `<span class="badge badge-info">${data || 0}</span>`; } }
            ],
            order: [[1, 'asc']],
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                // {
                //     extend: 'copy',
                //     text: '<i class="fas fa-copy"></i> Copy',
                //     className: 'btn btn-sm btn-primary',
                //     exportOptions: { columns: ':visible' }
                // },
                {
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'btn btn-sm btn-primary',
                    action: function() {
                        downloadTaskReport('csv');
                    }
                },
                {
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-primary',
                    action: function() {
                        downloadTaskReport('excel');
                    }
                },
                {
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-primary',
                    action: function() {
                        downloadTaskReport('pdf');
                    },
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: ':visible' }
                }
                // ,
                // {
                //     extend: 'print',
                //     text: '<i class="fas fa-print"></i> Print',
                //     className:'btn btn-sm btn-primary',
                //     exportOptions: { columns: ':visible' }
                // }
            ],
            responsive: true
        });

        // Handles the main project row expansion
        $('#taskReportTable tbody').on('click', 'td.details-control', function () {
            const tr = $(this).closest('tr');
            const row = taskTable.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(formatDepartments(row.data())).show();
                tr.addClass('shown');
            }
        });

        // Handles clicks on department rows to toggle visibility of the tasks container.
        $('#taskReportTable tbody').on('click', '.department-row', function () {
            const $this = $(this);
            // Find the tasks container within the same parent and toggle it.
            $this.closest('.department-container').find('.tasks-container').toggle();
            // Toggle the icon for visual feedback.
            $this.find('i').toggleClass('fa-plus-circle fa-minus-circle text-primary text-danger');
        }); 
    });
    

    function getDepartmentTasks(projectId, deptId) {
        const project = reportData?.data?.projects.find(p => p.project_id == projectId);
        if (!project || !project.tasks) return [];
        return project.tasks.filter(task => task.dept_id == deptId);
    }

    function renderTasks($row, tasks) {
    // First, render the header row for tasks
    const headerRow = `
        <tr class="task-row task-header-row">
            <th>Task</th>
            <th>Assigned To</th>
            <th>Assigned Duration</th>
            <th>Completed Duration</th>
            <th>Status</th>
            
        </tr>
    `;
    $row.after(headerRow);

    // Now render each task row below the header
    tasks.forEach(task => {
        const statusClass = getStatusClass(task.task_status);
        const taskRow = `
            <tr class="task-row">
                <td>${task.task_name || 'N/A'}</td>
                <td> <img src="${task.assigned_emp_profile}" alt="${task.assigned_emp_name}" 
                                                class="img-fluid rounded-circle" 
                                                style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #dee2e6;"
                                                onerror="this.src='./assets/img/default-avatar.png'"
                                            >   ${task.assigned_emp_name || 'Unassigned'}</td>
                <td align="center">${task.task_duration}</td>
                <td align="center">${task.completed_duration}</td>
                <td><span class="badge ${statusClass}">${task.task_status || 'N/A'}</span></td>
               
            </tr>
        `;
        $($row).nextAll('.task-header-row').last().after(taskRow);
    });
}

    function formatDuration(startDate, endDate) {
        if (!startDate) return 'Not started';

        const parseDate = (str) => {
            const [day, month, year] = str.split('-');
            return new Date(`${year}-${month}-${day}`);
        };

        try {
            const start = parseDate(startDate);
            const end = endDate ? parseDate(endDate) : new Date();
            const startStr = start.toLocaleDateString('en-GB');
            const endStr = endDate ? end.toLocaleDateString('en-GB') : 'Present';
            return `${startStr} - ${endStr}`;
        } catch (e) {
            console.error('Date format error:', e);
            return '';
        }
    }

    function getStatusClass(status) {
    if (!status) return 'badge-secondary';
    const normalized = String(status).toLowerCase().trim();
    
    if (normalized.includes('done') || normalized.includes('complete')) {
        return 'badge-success';
    } else if (normalized.includes('pending') || normalized.includes('to do')) {
        return 'badge-warning';
    } else if (normalized.includes('in progress') || normalized.includes('ongoing') || normalized === 'inprogress') {
        return 'badge-info';
    } else if (normalized.includes('overdue')) {
        return 'badge-danger';
    }
    return 'badge-secondary';
}

    // Handle View Task button
    $(document).on('click', '.view-task', function () {
        const taskId = $(this).data('task-id');
        window.location.href = `view-project-task.php?id=${btoa(taskId)}`;
    });


    
</script>
<?php include 'common/footer.php'; ?>