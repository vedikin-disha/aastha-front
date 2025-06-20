<?php include 'common/header.php'; ?>

<style>
    @media (max-width: 767px) {
        
        #new-report-job-wise{
         margin-top: 10px;
        }
    }
    .new-pms-ap {

        width: 100% !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
    }

    .select2-results__option .select2-results__option--highlighted {
        background-color: #ececec !important;
        color: #212529 !important;
    }
    
</style>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Job Wise Status</h3>
    </div>
    <div class="card-body">

        <!-- Enhanced Filter Box -->
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                <label for="id_circle" class="form-label">Select Circle</label>
                <select name="circle_id" id="id_circle" class="form-control select2">
            <option value="">Select Circle</option>
            <!-- Circle options will be populated via API -->
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
                <div class="col-md-4">
                    <label for="id_job_no" class="form-label">Job No.</label>
                    <input type="text" name="job_no" id="id_job_no" class="form-control" placeholder="Enter Job No" value="">
                </div>
                <div class="col-md-4">
                    <label for="projectName" class="form-label">Project Name</label>
                    <select name="project_name" id="projectName" class="form-control select2" style="width: 100%;" data-placeholder="Select Project">
                        <option value="">All Projects</option>
                    </select>
                </div>
                <div id="new-report-job-wise" class="col-md-4 d-flex align-items-end">
                    <div class="d-flex" style="gap:10px;">
                        <!-- <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border:none;">Search</button> -->
                        <a href="report-job-wise-status" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Job Table -->
         <div class="new-pms-ap">
            <table id="jobStatusTable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Job No.</th>
                        <th>Project Name</th>
                        <th>Project Status</th>
                        <th>Current Department</th>
                        <th>DTP Section</th>
                        <th>Technical Section</th>
                        <th>Administrative Approval</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- data table data -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CSS Dependencies -->
<link rel="stylesheet" href="css/all.min.css">
<link rel="stylesheet" href="css/select2.min.css">
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="css/select2-bootstrap4.min.css">
<!-- JS Dependencies -->
<!-- <script src="{% static 'js/jquery.min.js' %}"></script>
<script src="{% static 'js/select2.full.min.js' %}"></script>
<script src="{% static 'js/jquery.dataTables.min.js' %}"></script>
<script src="{% static 'js/dataTables.bootstrap4.min.js' %}"></script>
<script src="{% static 'js/dataTables.responsive.min.js' %}"></script>
<script src="{% static 'js/responsive.bootstrap4.min.js' %}"></script>
<script src="{% static 'js/dataTables.buttons.min.js' %}"></script>
<script src="{% static 'js/buttons.bootstrap4.min.js' %}"></script>
<script src="{% static 'js/jszip.min.js' %}"></script>
<script src="{% static 'js/pdfmake.min.js' %}"></script>
<script src="{% static 'js/vfs_fonts.js' %}"></script>
<script src="{% static 'js/buttons.html5.min.js' %}"></script>
<script src="{% static 'js/buttons.print.min.js' %}"></script>
<script src="{% static 'js/buttons.colVis.min.js' %}"></script> -->

<!-- Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Define jobTable variable in global scope so it's accessible everywhere
var jobTable;

$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const initialJob = urlParams.get('job_no') || '';
const initialProjectParam = (urlParams.get('project_name') || '').toLowerCase();
    const initialProject = urlParams.get('project_name') || '';

    if(initialJob){ $('#id_job_no').val(initialJob); }

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // ---------------- Load Projects -----------------
    function loadProjects(){
        $.ajax({
            url: '<?php echo API_URL; ?>project-listing',
            type: 'POST',
            headers: { 'Content-Type': 'application/json' },
            data: JSON.stringify({ access_token: "<?php echo $_SESSION['access_token']; ?>" }),
            success: function(response){
                var projectSelect = $('#projectName');
                projectSelect.empty().append('<option value="">All Projects</option>');
                if(response.is_successful==='1' && response.data && response.data.projects){
                    response.data.projects.forEach(function(pr){
                        projectSelect.append(new Option(pr.project_name, pr.project_name));
                    });
                }
                projectSelect.trigger('change');
            },
            error:function(){
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

    loadProjects();

    // --------- Client-side table filtering ---------
    function applyFilters(){
        var jobFilter = $('#id_job_no').val().toLowerCase();
        var projectFilter = $('#projectName').val().toLowerCase();
        jobTable.rows().every(function(){
            var d = this.data();
            var match = true;
            if(jobFilter && (!d.job_no || d.job_no.toLowerCase().indexOf(jobFilter)===-1)){
                match = false;
            }
            if(projectFilter && (!d.project_name || d.project_name.toLowerCase()!==projectFilter)){
                match = false;
            }
            $(this.node()).toggle(match);
        });
    }

    $('#id_job_no').on('keyup change', applyFilters);
    $('#projectName').on('change', applyFilters);

    // Helper to return download link with appropriate icon based on file type
    function fileIcon(url){
        const ext = url.split('.').pop().toLowerCase().split('?')[0];
        let icon = 'fas fa-file', color = 'text-secondary';
        if(['pdf'].includes(ext)){ icon='fas fa-file-pdf'; color='text-danger'; }
        else if(['doc','docx'].includes(ext)){ icon='fas fa-file-word'; color='text-primary'; }
        else if(['xls','xlsx','csv'].includes(ext)){ icon='fas fa-file-excel'; color='text-success'; }
        else if(['png','jpg','jpeg','gif','bmp','webp'].includes(ext)){ icon='fas fa-file-image'; color='text-info'; }
        return `<a href="${url}" target="_blank"style="font-size: 20px"><i class="${icon} ${color}" style='font-size:30px'></i></a>`;
    }
    
    // Load Circles
    $.ajax({
        url: '<?php echo API_URL; ?>circle',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),
        success: function(response) {
            // Populate circle dropdown
            var circleSelect = $('#id_circle');
            
            // Clear existing options except the first one
            circleSelect.find('option:not(:first)').remove();
            
            // Add new options from API response
            $.each(response, function(index, circle) {
                circleSelect.append(new Option(circle.circle_name, circle.circle_id));
            });

            // After circles are loaded, load initial job data
            $.ajax({
                url: '<?php echo API_URL; ?>jobwise',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    access_token: "<?php echo $_SESSION['access_token']; ?>"
                }),
                success: function(response) {
                    if (response && response.is_successful === '1' && response.data) {
                        // Clear and reload the table
                        jobTable.clear();
                        
                        // Add the new data
                        response.data.forEach(function(item) {
                            jobTable.row.add({
                                job_no: item.job_no || '-',
                                project_name: item.project_name || '-',
                                project_status: item.project_status ? item.project_status.charAt(0).toUpperCase() + item.project_status.slice(1) : '-',
                                current_department: item.current_department || '-',
                                dtp_section: item.dtp_section,
                                technical_section: item.technical_section,
                                administrative_approval: item.administrative_approval,
                                project_id: item.project_id || ''
                            });
                        });
                        
                        // Draw the table
                        jobTable.draw();

                        // Populate project dropdown
                        var projectSelect = $('#projectName');
                        projectSelect.empty().append('<option value="">All Projects</option>');
                        
                        // Create a Set to store unique project names
                        var uniqueProjects = new Set();
                        response.data.forEach(function(item) {
                            if (item.project_name) {
                                uniqueProjects.add(item.project_name);
                            }
                        });
                        
                        // Add unique projects to dropdown
                        uniqueProjects.forEach(function(projectName) {
                            projectSelect.append('<option value="' + projectName + '">' + projectName + '</option>');
                        });
                        
                        // Refresh Select2
                        projectSelect.trigger('change');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching job data:', error);
                }
            });
        },
        error: function(response) {
            console.error('Failed to load circles');
            // alert('Failed to load circles. Please refresh the page.');
        }
    });

    // Load Divisions based on Circle
    $('#id_circle').on('change', function() {
        var circleId = $(this).val();
        var divisionSelect = $('#id_division');
        
        
        // Clear existing options
        divisionSelect.empty().append('<option value="">All Divisions</option>');
        
        if (!circleId) {
            divisionSelect.trigger('change');
            return;
        }
        
        // Call division API
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
                if (Array.isArray(response)) {
                    response.forEach(function(div) {
                        divisionSelect.append(new Option(div.division_name, div.division_id));
                    });
                }
                divisionSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching divisions:', error);
                // alert('Failed to fetch divisions. Please try again.');
            }
        });
    });

    // Load Sub Divisions based on Division
    $('#id_division').on('change', function() {
        var divisionId = $(this).val();
        var subDivisionSelect = $('#id_sub_division');
        loadJobData();
        
        // Clear existing options
        subDivisionSelect.empty().append('<option value="">All Sub Divisions</option>');
        
        if (!divisionId) {
            subDivisionSelect.trigger('change');
            return;
        }
        
        // Call subdivision API
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
                if (Array.isArray(response)) {
                    response.forEach(function(subdivision) {
                        subDivisionSelect.append(new Option(subdivision.subdivision_name, subdivision.sub_id));
                    });
                }
                subDivisionSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching subdivisions:', error);
                // alert('Failed to fetch subdivisions. Please try again.');
            }
        });
    });



    // Load Talukas based on Sub Division
    $('#id_sub_division').on('change', function() {
        var subId = $(this).val();
        var talukaSelect = $('#id_taluka');
        loadJobData();
        
        // Clear existing options
        talukaSelect.empty().append('<option value="">All Talukas</option>');
        
        if (!subId) {
            talukaSelect.trigger('change');
            return;
        }
        
        // Call taluka API
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
                if (Array.isArray(response)) {
                    response.forEach(function(taluka) {
                        talukaSelect.append(new Option(taluka.taluka_name, taluka.taluka_id));
                    });
                }
                talukaSelect.trigger('change');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching talukas:', error);
                // alert('Failed to fetch talukas. Please try again.');
            }
        });
    });





    // Initialize DataTable
    var jobTable = $('#jobStatusTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        "columns": [
            { "data": "job_no" },
            { "data": "project_name", "render": function(data, type, row){
                    var encoded = btoa(row.project_id || '');
                    return `<a href=\"view-project.php?id=${encoded}\" target=\"_blank\">${data || '-'}</a>`;
            }},
            { "data": "project_status" },
            { "data": "current_department" },
            { "data": "dtp_section", "render": function(data){
                    if(!data) return '-';
                    return fileIcon(data);
            } },
            { "data": "technical_section", "render": function(data){
                    if(!data) return '-';
                    return fileIcon(data);
            } },
            { "data": "administrative_approval", "render": function(data){
                    if(!data) return '-';
                    return fileIcon(data);
            } }
        ],
        
    });
    jobTable.buttons().container()
      .appendTo('#jobStatusTable_wrapper .col-md-6:eq(0)');
    jobTable.clear();
jobTable.rows.add(response.data); // if response.data is an array of objects
jobTable.draw();

    // Function to load job data
    function loadJobData() {
        // Get selected values from filters
        var circleId = $('#id_circle').val() || '';
        var divisionId = $('#id_division').val() || '';
        var subDivisionId = $('#id_sub_division').val() || '';
        var talukaId = $('#id_taluka').val() || '';
        var jobNo = $('#id_job_no').val().trim();
        var projectName = $('#projectName').val();
        // globaley serch then project name in going to request
        
        jobTable.column(0).search(jobNo);
        jobTable.column(1).search(projectName);
        jobTable.draw();

        // Prepare request data
        var requestData = {
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        };

        // Only add parameters if they are selected
        if (circleId) requestData.circle_id = circleId;
        if (divisionId) requestData.division_id = divisionId;
        if (subDivisionId) requestData.sub_division_id = subDivisionId;
        if (talukaId) requestData.taluka_id = talukaId;
        if (jobNo) requestData.job_no = jobNo;
        if (projectName) requestData.project_name = projectName;
        

        $.ajax({
            url: '<?php echo API_URL; ?>jobwise',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(requestData),
            success: function(response) {
                if (response && response.is_successful === '1' && response.data) {
                    // Clear and reload the table
                    jobTable.clear();
                    
                    // Add the new data
                    response.data.forEach(function(item) {
                        jobTable.row.add({
                            job_no: item.job_no || '-',
                            project_name: item.project_name || '-',
                            project_status: item.project_status ? item.project_status.charAt(0).toUpperCase() + item.project_status.slice(1) : '-',
                            current_department: item.current_department || '-'
                        });
                    });
                    
                    // Draw the table
                    jobTable.draw();

                    // Populate project dropdown
                    var projectSelect = $('#projectName');
                    projectSelect.empty().append('<option value="">All Projects</option>');
                    
                    // Create a Set to store unique project names
                    var uniqueProjects = new Set();
                    response.data.forEach(function(item) {
                        if (item.project_name) {
                            uniqueProjects.add(item.project_name);
                        }
                    });
                    
                    // Add unique projects to dropdown
                    uniqueProjects.forEach(function(projectName) {
                        projectSelect.append('<option value="' + projectName + '">' + projectName + '</option>');
                    });
                    
                    // Refresh Select2
                    projectSelect.trigger('change');
                } else {
                    console.error('API Error:', response.errors || 'Unknown error');
                    // alert('Failed to fetch job data: ' + (response.errors || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching job data:', error);
                // alert('Failed to fetch job data. Please try again.');
            }
        });
    }

    // Handle filter changes
    $('#id_circle, #id_division, #id_sub_division, #id_taluka').on('change', function() {
        loadJobData(); // Reload data when any filter changes
    });

    // Handle form submission
    $('form').on('submit', function(e) {
        e.preventDefault();
        var jobNo = $('#id_job_no').val().trim();
        var projectName = $('#projectName').val();
        loadJobData();

        // Apply filters to DataTable
        jobTable.column(0).search(jobNo);
        jobTable.column(1).search(projectName);
        jobTable.draw();
    });

    // Handle reset button
    $('.btn-outline-secondary').on('click', function(e) {
        e.preventDefault();
        // Clear all filters
        $('#id_circle, #id_division, #id_sub_division, #id_taluka, #id_job_no, #projectName').val('').trigger('change');
        jobTable.search('').columns().search('').draw();
        loadJobData(); // Reload with no filters
    });


});
</script>
<script src="js/jquery.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="js/dataTables.responsive.min.js"></script>
<script src="js/responsive.bootstrap4.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.bootstrap4.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script src="js/buttons.print.min.js"></script>
<script src="js/buttons.colVis.min.js"></script>

<!-- <script>
  $(function () {
    // Initialize DataTable only once
    jobTable = $("#jobStatusTable").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      paging: true,
      searching: true,
      ordering: true,
      info: true,
      buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    jobTable.buttons().container()
      .appendTo('#jobStatusTable_wrapper .col-md-6:eq(0)');

    $('#report a').addClass('active');
    $('#report a').addClass('active nav-link');
  });
</script> -->

<?php include 'common/footer.php'; ?>
