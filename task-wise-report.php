<?php include 'common/header.php'; ?>

<!-- Add DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables core -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- Buttons core -->
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.min.js"></script>

<!-- Export dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

<style>
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
                            <th>Actions</th>
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
    let reportData = null;

    // Initialize DataTable
    const taskTable = $('#taskReportTable').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        pageLength: 100,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
    // Position the buttons in the container we created
    taskTable.buttons().container()
        .appendTo('#taskReportTable_wrapper .col-md-6:eq(0)');
    // Add some margin to the buttons
    $('.dt-buttons').addClass('mb-3');

    // Load report data
    loadReportData();

    function loadReportData() {
        $.ajax({
            url: '<?php echo API_URL; ?>project-task-listing',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION["access_token"]; ?>'
            }),
            success: function (response) {
                reportData = response; // Make response globally accessible
                if (response && response.data && response.data.projects) {
                    populateReportTable(response.data.projects);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error loading report data:', error);
                alert('Error loading report data. Please try again.');
            }
        });
    }

    function populateReportTable(projects) {
        const tbody = $('#reportTableBody');
        tbody.empty();

        projects.forEach(project => {
            const projectRow = `
                <tr class="project-row" data-project-id="${project.project_id}">
                    <td>
                        <i class="fas fa-chevron-right mr-2 expand-icon"></i>
                        ${project.project_name}
                    </td>
                    <td>${project.total_tasks || 0}</td>
                    <td><span class="badge badge-success">${project.completed_task || 0}</span></td>
                    <td><span class="badge badge-warning">${project.pending_task || 0}</span></td>
                    <td><span class="badge badge-info">${project.ongoing_task || 0}</span></td>
                </tr>
            `;
            tbody.append(projectRow);

            // Group tasks by department
            const departments = {};
            if (project.tasks) {
    project.tasks.forEach(task => {
        if (!departments[task.dept_id]) {
            departments[task.dept_id] = {
                name: task.dept_name,
                tasks: [],
                counts: {
                    total: 0,
                    completed: 0,
                    pending: 0,
                    ongoing: 0
                }
            };
        }
        departments[task.dept_id].tasks.push(task);
        departments[task.dept_id].counts.total++;
        
// Update the status check to be case-insensitive and handle all variations
const status = String(task.task_status || '').toLowerCase().trim();
if (status.includes('done') || status.includes('complete')) {
    departments[task.dept_id].counts.completed++;
} else if (status.includes('pending') || status.includes('to do')) {
    departments[task.dept_id].counts.pending++;
} else if (
    status.includes('in progress') || 
    status.includes('ongoing') || 
    status.replace(/\s/g, '') === 'inprogress'
) {
    departments[task.dept_id].counts.ongoing++;
} else {
    console.warn('Unrecognized status:', task.task_status, 'Normalized:', status);
}

const mapStatusToType = (status) => {
    if (!status) return 'other';
    const s = status.toLowerCase().trim().replace(/\s+/g, '');
    
    if (s.includes('done') || s.includes('complete')) return 'completed';
    
    if (s.includes('inprogress') || s.includes('ongoing') || s.includes('inprogress')) return 'ongoing';
    if (s.includes('overdue')) return 'overdue';
    
    console.warn('Unmapped status:', status);
    return 'other';
};

const type = mapStatusToType(task.task_status);
if (type && departments[task.dept_id].counts[type] !== undefined) {
    departments[task.dept_id].counts[type]++;
} else {
    console.warn('Unrecognized status:', task.task_status, 'Mapped as:', type);
}

// Add debug logging
console.log('Task:', {
    id: task.task_id,
    name: task.task_name,
    status: task.task_status,
    normalized: status,
    counts: departments[task.dept_id].counts
});
    });
}
            // Add department rows
            Object.entries(departments).forEach(([deptId, deptData]) => {
                const deptRow = `
                     <tr class="department-row" data-project-id="${project.project_id}" data-dept-id="${deptId}" style="display: none;">
        <td colspan="5" class="pl-4">
            <i class="fas fa-chevron-right mr-2 expand-icon"></i>
            ${deptData.name}
            <span class="badge badge-secondary ml-2">${deptData.counts.total} Tasks</span>
            <span class="badge badge-success ml-1">${deptData.counts.completed} Done</span>
            <span class="badge badge-warning ml-1">${deptData.counts.pending} Pending</span>
            <span class="badge badge-info ml-1">${deptData.counts.ongoing} Ongoing</span>
        </td>
    </tr>
                `;
                tbody.append(deptRow);
            });
        });

        setupRowHandlers();
    }

    function setupRowHandlers() {
        // Toggle departments under projects
        $('.project-row').off('click').on('click', function () {
            const projectId = $(this).data('project-id');
            const $icon = $(this).find('.expand-icon');
            $icon.toggleClass('fa-chevron-down fa-chevron-right');
            $(`.department-row[data-project-id="${projectId}"]`).toggle();
        });

        // Handle department row click
        $('.department-row').off('click').on('click', function (e) {
            e.stopPropagation();
            const $row = $(this);
            const projectId = $row.data('project-id');
            const deptId = $row.data('dept-id');
            const $icon = $row.find('.expand-icon');
            $icon.toggleClass('fa-chevron-down fa-chevron-right');

            // Check if tasks already loaded
            if ($row.next().hasClass('task-row')) {
    // Just toggle all task rows including header
    $row.nextUntil(':not(.task-row)').toggle();
} else {
    // Render header + tasks
    const loadingRow = $('<tr class="task-row"><td colspan="6" class="p-3 text-center"><i class="fas fa-spinner fa-spin"></i> Loading tasks...</td></tr>');
    $row.after(loadingRow);

    const tasks = getDepartmentTasks(projectId, deptId);
    if (tasks && tasks.length > 0) {
        loadingRow.remove();
        renderTasks($row, tasks);
    } else {
        loadingRow.html('<td colspan="6" class="text-center p-3">No tasks found for this department</td>');
    }
}
        });
    }

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
});

    
</script>
<?php include 'common/footer.php'; ?>