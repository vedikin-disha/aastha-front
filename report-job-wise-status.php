<?php include 'common/header.php'; ?>

<style>
    @media (max-width: 767px) {
        
        #new-report-job-wise{
         margin-top: 10px;
        }
    }
    .new-pms-ap {

        width: 100% !important;
        /* overflow-x: auto !important;
        overflow-y: hidden !important; */
    }
    .select2-container--bootstrap4 .select2-results__option--highlighted {
        background-color: #30b8b9 !important;
        color: #212529 !important;
    }

    .select2-results__option .select2-results__option--highlighted {
        background-color: #ececec !important;
        color: #212529 !important;
    }
    .dropdown-item.active {
        background-color: #30b8b9 !important;
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
                <div class="col-md-2">
                    <label for="id_job_no" class="form-label">Job No.</label>
                    <input type="text" name="job_no" id="id_job_no" class="form-control" placeholder="Enter Job No" value="">
                </div>
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="projectName" class="form-label">Project Name</label>
                    <select name="project_name" id="projectName" class="form-control select2" style="width: 100%;" data-placeholder="Select Project">
                        <option value="">All Projects</option>
                    </select>
                </div>
                <div id="new-report-job-wise" class="col-md-2 d-flex align-items-end">
                    <div class="d-flex" style="gap:10px;">
                        <!-- <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border:none;">Search</button> -->

                        <button type="button" id="resetButton" class="btn btn-outline-secondary" onclick="resetForm()">Reset</button>
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
                        
                        <th>Administrative Approval</th>
                        <th>Technical Section</th>
                        <th>DTP Section</th>
                     
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

// Global reset form function
function resetForm() {
    // Reset all form fields
    $('form')[0].reset();
    
    // Reset all Select2 dropdowns
    $('.select2').val(null).trigger('change');
    
    // Clear the job number input
    $('#id_job_no').val('');
    
    // Reset DataTable search and filters
    if (jobTable) {
        jobTable.search('').columns().search('').draw();
    }
    
    // Clear URL parameters without reloading
    if (window.history.replaceState) {
        const cleanURL = window.location.pathname;
        window.history.replaceState({}, document.title, cleanURL);
    }
    
    // Clear any global search in header
    if (window.parent && window.parent.$) {
        try {
            window.parent.$('#globalSearchInput').val('');
        } catch (e) {
            console.log('Could not clear global search');
        }
    }
    
    // Load fresh data without any filters
    if (typeof loadJobData === 'function') {
        loadJobData();
    } else {
        // If loadJobData isn't available yet, reload the page
        window.location.href = window.location.pathname;
    }
}

$(document).ready(function() {

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Reset form functionality is now in the global scope

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

            // make data for API dynamically. if project_name is present then only add it in data
            var urlParams = new URLSearchParams(window.location.search);
            var project_name = urlParams.get('project_name');
            var data = {
                access_token: "<?php echo $_SESSION['access_token']; ?>"
            };
            if(project_name){
                data.search = project_name;
            }

            // After circles are loaded, load initial job data
            $.ajax({
                url: '<?php echo API_URL; ?>jobwise',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(data),
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





    // Initialize DataTable with empty data
    var urlParams = new URLSearchParams(window.location.search);
    var initialProjectParam = urlParams.get('project_name');
    var jobTable = $('#jobStatusTable').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "serverSide": false,
        "processing": true,
        "data": [],
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        "columns": [
            { "data": "job_no" },
            { 
                "data": "project_name", 
                "render": function(data, type, row) {
                    if (type === 'display' && data) {
                        var encoded = btoa(row.project_id || '');
                        return `<a href="view-project.php?id=${encoded}" target="_blank">${data}</a>`;
                    }
                    return data || '-';
                }
            },
            { 
title: "Project Status",
                "data": "project_status" },
            { 
title: "Current Department",
                "data": "current_department" },
            { 
            title: "Administrative Approval",
                "data": "administrative_approval", 
                "render": function(data) {
                    if (!data) return '-';
                    return fileIcon(data);
                } 
            },

            { 
title: "Technical Section",
                "data": "technical_section", 
                "render": function(data) {
                    if (!data) return '-';
                    return fileIcon(data);
                } 
            },
           
            { 
title: "DTP Section",
                "data": "dtp_section", 
                "render": function(data) {
                    if (!data) return '-';
                    return fileIcon(data);
                } 
            }
        ],
        "language": {
            "emptyTable": "No data available in table",
            "zeroRecords": "No matching records found"
        },
        "drawCallback": function() {
            // Handle empty table
            var api = this.api();
            if (api.rows({page:'current'}).data().length === 0) {
                api.$('td').attr('colspan', api.columns().count())
                    .text('No data available');
            }
        }
    });
    
    // Move buttons to the correct container
    jobTable.buttons().container()
        .appendTo('#jobStatusTable_wrapper .col-md-6:eq(0)');
    
    // Initial data load
    loadJobData();

    // Function to load job data
    function loadJobData() {
        // Show loading state
        $('#jobStatusTable tbody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        // Get selected values from filters
        var circleId = $('#id_circle').val() || '';
        var divisionId = $('#id_division').val() || '';
        var subDivisionId = $('#id_sub_division').val() || '';
        var talukaId = $('#id_taluka').val() || '';
        var jobNo = $('#id_job_no').val().trim();
        var projectName = $('#projectName').val();
        var fromDate = $('#from_date').val();
        var toDate = $('#to_date').val();
        
        // Get search term from URL if present
        const urlParams = new URLSearchParams(window.location.search);
        const urlProjectName = urlParams.get('project_name');
        const urlJobNo = urlParams.get('job_no');
        
        // If URL has project_name parameter, use that instead of form field
        if (urlProjectName && !projectName) {
            projectName = urlProjectName;
            $('#projectName').val(projectName).trigger('change');
        }
        
        // If URL has job_no parameter, use that instead of form field
        if (urlJobNo && !jobNo) {
            jobNo = urlJobNo;
            $('#id_job_no').val(jobNo);
        }
        
        // Prepare request data
        var requestData = {
            access_token: "<?php echo $_SESSION['access_token']; ?>"
        };

        // Only add parameters if they have values
        if (circleId) requestData.circle_id = parseInt(circleId);
        if (divisionId) requestData.division_id = parseInt(divisionId);
        if (subDivisionId) requestData.sub_division_id = parseInt(subDivisionId);
        if (talukaId) requestData.taluka_id = parseInt(talukaId);
        if (jobNo) requestData.job_no = jobNo;
        if (projectName) requestData.search = projectName;
        if (fromDate) requestData.from_date = fromDate;
        if (toDate) requestData.to_date = toDate;
        
        console.log('Sending request:', JSON.stringify(requestData, null, 2));
        
        // Make the API call
        $.ajax({
            url: '<?php echo API_URL; ?>jobwise',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(requestData),
            success: function(response) {
                console.log('API Response:', response);
                
                // Clear existing data
                jobTable.clear().draw();
                
                if (response && response.is_successful === '1' && response.data) {
                    console.log('API Data:', response.data);
                    
                    // Check if data is an array and has items
                    if (Array.isArray(response.data) && response.data.length > 0) {
                        // Prepare data for DataTable
                        var tableData = response.data.map(function(item) {
                            return {
                                job_no: item.job_no || '-',
                                project_name: item.project_name || '-',
                                project_status: item.project_status ? 
                                    item.project_status.charAt(0).toUpperCase() + item.project_status.slice(1) : '-',
                                current_department: item.current_department || '-',
                                dtp_section: item.dtp_section || '',
                                technical_section: item.technical_section || '',
                                administrative_approval: item.administrative_approval || '',
                                project_id: item.project_id || ''
                            };
                        });
                        
                        // Clear the table and add new data
                        jobTable.clear();
                        jobTable.rows.add(tableData).draw();
                    } else {
                        // No data found
                        $('#jobStatusTable tbody')
                            .html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                    }
                } else {
                    // API returned an error or no data
                    console.error('API Error:', response?.errors || 'No data found');
                    jobTable.clear().draw();
                    $('#jobStatusTable tbody')
                        .html('<tr><td colspan="7" class="text-center">No data available</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching job data:', error);
                jobTable.draw(); // Ensure table is drawn even on error
            }
        });
    }

    // Handle date changes
    $('#from_date, #to_date').on('change', function() {
        // Only proceed if both dates are selected
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();
        
        if (fromDate && toDate) {
            loadJobData();
        }
    });

    // Handle form submission
    $('form').on('submit', function(e) {
        e.preventDefault();
        // Update URL with search parameter
        const searchTerm = $('#projectName').val();
        const url = new URL(window.location.href);
        if (searchTerm) {
            url.searchParams.set('project_name', searchTerm);
        } else {
            url.searchParams.delete('project_name');
        }
        window.history.pushState({}, '', url);
        
        // Reset to first page when searching
        if (jobTable) {
            jobTable.page('first').draw('page');
        }
        loadJobData();
    });
    
    // Handle job number input changes with debounce
    var jobNoTimeout;
    $('#id_job_no').on('keyup', function(e) {
        clearTimeout(jobNoTimeout);
        var $this = $(this);
        jobNoTimeout = setTimeout(function() {
            const jobNo = $this.val().trim();
            if (jobNo === '' || jobNo.length >= 1) {
                // Update URL with job number
                const url = new URL(window.location.href);
                if (jobNo) {
                    url.searchParams.set('job_no', jobNo);
                } else {
                    url.searchParams.delete('job_no');
                }
                url.searchParams.delete('project_name'); // Clear project name when searching by job no
                window.history.pushState({}, '', url);
                
                loadJobData();
            }
        }, 500); // 500ms delay
        
        // Also trigger on Enter key
        if (e.key === 'Enter' || e.keyCode === 13) {
            clearTimeout(jobNoTimeout);
            const jobNo = $this.val().trim();
            const url = new URL(window.location.href);
            if (jobNo) {
                url.searchParams.set('job_no', jobNo);
            } else {
                url.searchParams.delete('job_no');
            }
            url.searchParams.delete('project_name'); // Clear project name when searching by job no
            window.history.pushState({}, '', url);
            loadJobData();
        }
    });
    
    // Handle project name changes
    $('#projectName').on('change', function() {
        const searchTerm = $(this).val();
        const url = new URL(window.location.href);
        if (searchTerm) {
            url.searchParams.set('project_name', searchTerm);
        } else {
            url.searchParams.delete('project_name');
        }
        window.history.pushState({}, '', url);
        loadJobData();
    });
    
    // Handle filter changes with debounce
    var filterTimeout;
    $('#id_circle, #id_division, #id_sub_division, #id_taluka').on('change', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            loadJobData();
        }, 300);
    });
    
    // Handle reset button click
    $('#resetButton').on('click', function(e) {
        // redirect to the report-job-wise-status page
        window.location.href = 'report-job-wise-status.php';
        e.preventDefault();
        
        // Reset all form fields
        $('form')[0].reset();
        
        // Clear select2 dropdowns
        $('.select2').val(null).trigger('change');
        
        // Clear the job number input
        $('#id_job_no').val('');
        
        // Reset the table
        jobTable.search('').columns().search('').draw();
        
        // Reload data with empty filters
        loadJobData();
    });

    // Handle reset button
    $('.btn-outline-secondary, #resetButton').on('click', function(e) {
        // redirect to the report-job-wise-status page
      
        e.preventDefault();
        window.location.href = 'report-job-wise-status.php';
        
        function resetForm() {
            $('form')[0].reset();
            $('.select2').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            loadJobData();
        }
        
        // Reset all form fields
        resetForm();
        
        // Reset all Select2 dropdowns
        $('.select2').val(null).trigger('change');
        
        // Clear the job number input
        $('#id_job_no').val('');
        
        // Reset DataTable search and filters
        if (jobTable) {
            jobTable.search('').columns().search('').draw();
        }
        
        // Clear URL parameters without reloading
        if (window.history.replaceState) {
            const cleanURL = window.location.pathname;
            window.history.replaceState({}, document.title, cleanURL);
        }
        
        // Clear any global search in header
        if (window.parent && window.parent.$) {
            try {
                window.parent.$('#globalSearchInput').val('');
            } catch (e) {
                console.log('Could not clear global search');
            }
        }
        
        // Load fresh data without any filters
        loadJobData();
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



<?php include 'common/footer.php'; ?>
