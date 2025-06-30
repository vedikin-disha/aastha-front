<?php 
include 'common/header.php';
?>

<div class="p-3">
<div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Department Wise Report</h1>
                </div>
            </div>
        </div>
    </div>

  <!-- Filters Card -->
  <div class="card mb-4">
    <div class="card-header">
      <h3 class="card-title">Filters</h3>
    </div>
    <div class="card-body">
      <form id="filterForm">
        <div class="row align-items-end">
          <!-- Department -->
          <div class="col-md-4 mb-3">
            <label for="id_dept" class="form-label fw-semibold">Department</label>
            <select name="department" id="id_dept" class="form-control select2" data-placeholder="Select Department">
              <option value="">Select Department</option>
              <!-- Dynamic Options -->
            </select>
          </div>

          <!-- Time Range -->
          <div class="col-md-4 mb-3">
            <label for="timeRange" class="form-label fw-semibold">Time Range</label>
            <select id="timeRange" class="form-control">
              <option value="" selected>Select Time</option>
              <option value="today">Today</option>
              <option value="last_week">Last Week</option>
              <option value="current_week">Current Week</option>
              <option value="current_month">Current Month</option>
              <option value="custom">Custom</option>
            </select>
          </div>

          <!-- Custom Date Range -->
          <div class="col-md-4 mb-3 d-none" id="customRange">
            <label class="form-label fw-semibold">Custom Date</label>
            <div class="d-flex gap-2">
              <input type="date" id="fromDate" class="form-control" placeholder="From">
              <input type="date" id="toDate" class="form-control" placeholder="To">
            </div>
          </div>

          <!-- Apply Button -->
          <div class="col-12 text-end mt-2">
            <button type="submit" class="btn px-5" style=" background-color: #30b8b9 !important; color:white">Apply</button>
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
      display: none; /* Hide by default */
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
  
  <!-- Charts Container - Initially hidden -->
  <div id="chartsContainer" class="row d-none">
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

  <!-- Department Report Table -->
  <div class="card mt-4">
    <div class="card-header">
      <h5 id="reportTitle" class="card-title mb-0">Department Report Details</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="deptTable" class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Project Name</th>
              <th>Department Start Date</th>
              <th>Department End Date</th>
              <th>
                <div class="d-flex justify-content-between align-items-center">
                  <span>Status</span>
                  <select id="statusFilter" class="form-control form-control-sm ms-2" style="width: 120px">
                    <option value="">All Status</option>
                  </select>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Required CSS Libraries -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css"/>

<!-- Required JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Moment.js for date handling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- Chart.js (skip if already loaded by AdminLTE) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Global variables
  let deptTable = null;
  let allProjectsData = [];
  
  document.getElementById("timeRange").addEventListener("change", function () {
    const customRange = document.getElementById("customRange");
    if (this.value === "custom") {
      customRange.classList.remove("d-none");
    } else {
      customRange.classList.add("d-none");
    }
  })

$(function () {
  loadDepartments();
  // Init Select2 if available
  if($.fn.select2){ $('#id_dept').select2({placeholder:'Select Department'}); }

  // Populate department dropdown
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
}})

  // show/hide custom range inputs
  $('#timeRange').on('change', function(){
    $('#customRange').toggleClass('d-none', this.value!=='custom');
  });

  // Form submit
  $('#filterForm').on('submit', function(e){ e.preventDefault(); loadReport(); });

  var dateFrameChart, todayChart;

  function loadReport(){
    const dept_id = parseInt($('#id_dept').val()) || 0;
    let from_date, to_date;
    const today = moment();

    // Hide charts until data loads
    $('.chart-container').addClass('d-none');
    
    // Set date range based on selection
    switch($('#timeRange').val()){
      case 'today':
        from_date = today.format('YYYY-MM-DD');
        to_date = from_date;
        break;
      case 'last_week':
        from_date = moment().subtract(1,'weeks').startOf('week').format('YYYY-MM-DD');
        to_date = moment().subtract(1,'weeks').endOf('week').format('YYYY-MM-DD');
        break;
      case 'current_week':
        from_date = moment().startOf('week').format('YYYY-MM-DD');
        to_date = moment().endOf('week').format('YYYY-MM-DD');
        break;
      case 'current_month':
        from_date = moment().startOf('month').format('YYYY-MM-DD');
        to_date = moment().endOf('month').format('YYYY-MM-DD');
        break;
      case 'custom':
        from_date = $('#fromDate').val();
        to_date = $('#toDate').val();
        if (!from_date || !to_date) {
          alert('Please select both start and end dates for custom range');
          return;
        }
        break;
    }

    const params = {access_token:'<?php echo $_SESSION['access_token']; ?>', dept_id, from_date, to_date};

    // Show loading state
    $('#noDataMessage').addClass('d-none');
    $('#chartsContainer').addClass('d-none');
    
    // Load chart data
    $.ajax({
      url: '<?php echo API_URL; ?>dept-pie-chart',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(params),
      success: function(res) {
        if (res.is_successful === '1' && res.data) {
          // Check if we have data to display
          const hasDateFrameData = res.data.date_frame && 
                                 res.data.date_frame.datasets && 
                                 res.data.date_frame.datasets[0].data.some(v => v > 0);
          
          const hasTodayData = res.data.today && 
                             res.data.today.datasets && 
                             res.data.today.datasets[0].data.some(v => v > 0);
          
          if (hasDateFrameData || hasTodayData) {
            // Show charts container and hide no data message
            $('#chartsContainer').removeClass('d-none');
            $('#noDataMessage').addClass('d-none');
            
            // Render charts if they have data
            if (hasDateFrameData) {
              renderChart('dateFrameChart', res.data.date_frame);
            } else {
              $('#dateFrameChart').closest('.col-md-6').html('<div class="alert alert-info m-3">No data available for this time frame</div>');
            }
            
            if (hasTodayData) {
              renderChart('todayChart', res.data.today);
            } else {
              $('#todayChart').closest('.col-md-6').html('<div class="alert alert-info m-3">No data available for today</div>');
            }
          } else {
            // No data in either chart, show message
            $('#chartsContainer').addClass('d-none');
            $('#noDataMessage').removeClass('d-none');
          }
        } else {
          console.error('Error loading chart data:', res.errors);
          $('#noDataMessage').removeClass('d-none').find('h4').text('Error loading data');
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error loading chart data:', error);
        $('#noDataMessage').removeClass('d-none').find('h4').text('Error loading data');
      }
    });

    $.ajax({
      url:'<?php echo API_URL; ?>dept-report',
      type:'POST', 
      contentType:'application/json', 
      data: JSON.stringify(params), 
      success: function(res) {
        if (res.is_successful === '1') {
          // Store all projects data for filtering
          allProjectsData = res.data || [];
          
          // Initialize or update the DataTable
          if (!deptTable) {
            initializeDataTable(allProjectsData);
          } else {
            deptTable.clear().rows.add(allProjectsData).draw();
          }
          
          // Clear any active filters
          filterProjectsByStatus(null, null);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading department report:', error);
      }
    });
  }

  // Store the current filter state
  let currentFilter = {
    status: null,
    dateType: null // 'date_frame' or 'today'
  };

  // Function to filter projects table by status
  function filterProjectsByStatus(status, dateType) {
    currentFilter.status = status;
    currentFilter.dateType = dateType;
    
    // Update the table title based on date type and status
    const statusText = status ? ` (${status.charAt(0).toUpperCase() + status.slice(1)})` : '';
    const dateTypeText = dateType === 'today' ? "Today's " : '';
    $('#reportTitle').text(`${dateTypeText}Department Report Details${statusText}`);
    
    // Clear all filters first
    deptTable.search('').columns().search('').draw();
    
    // If no status or 'assigned' is clicked, show all projects
    if (!status || status.toLowerCase() === 'assigned') {
      deptTable.draw();
      return;
    }
    
    // For other statuses (overdue, completed), filter specifically
    deptTable.column(3).search('^' + status + '$', true, false, true, true, true).draw();
  }

  function renderChart(chartId, chartData) {
    if (!chartData || !chartData.labels || !chartData.datasets) return;
    
    const canvas = document.getElementById(chartId);
    const parent = canvas.parentElement;
    const ctx = canvas.getContext('2d');
    const isDateFrame = chartId === 'dateFrameChart';
    const dateType = isDateFrame ? 'date_frame' : 'today';
    
    // Set canvas dimensions to match parent
    canvas.width = parent.offsetWidth;
    canvas.height = parent.offsetHeight;
    
    // Destroy existing chart if it exists
    if ((isDateFrame && dateFrameChart) || (!isDateFrame && todayChart)) {
      if (isDateFrame) {
        dateFrameChart.destroy();
      } else {
        todayChart.destroy();
      }
    }
    
    // Store the labels for click handling
    const labels = [...chartData.labels];
    
    const chart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: labels,
        datasets: [{
          data: chartData.datasets[0].data,
          backgroundColor: chartData.datasets[0].backgroundColor,
          hoverBackgroundColor: chartData.datasets[0].backgroundColor.map(color => 
            Chart.helpers.color(color).darken(0.2).rgbString()
          ),
          borderWidth: 1,
          hoverBorderColor: "#fff"
        }]
      },
      options: {
        onClick: (event, elements) => {
          if (elements.length > 0) {
            const clickedIndex = elements[0].index;
            const status = labels[clickedIndex].toLowerCase();
            // Toggle filter - click again to clear filter
            if (currentFilter.status === status && currentFilter.dateType === dateType) {
              filterProjectsByStatus(null, null);
            } else {
              filterProjectsByStatus(status, dateType);
            }
          }
        },
        onHover: (event, elements) => {
          // Change cursor to pointer when hovering over chart segments
          canvas.style.cursor = elements.length > 0 ? 'pointer' : 'default';
        },
        responsive: true,
        maintainAspectRatio: false,
        layout: {
          padding: 10
        },
        plugins: {
          legend: {
            position: 'right',
            align: 'center',
            onClick: (e, legendItem, legend) => {
              // Disable default legend click behavior
              return false;
            },
            labels: {
              usePointStyle: true,
              padding: 10,
              boxWidth: 10,
              font: {
                size: 12
              },
              // Make legend items clickable
              generateLabels: function(chart) {
                const data = chart.data;
                if (data.labels.length && data.datasets.length) {
                  return data.labels.map((label, i) => {
                    const meta = chart.getDatasetMeta(0);
                    const style = meta.controller.getStyle(i);
                    
                    return {
                      text: `${label}: ${data.datasets[0].data[i]}`,
                      fillStyle: style.backgroundColor,
                      strokeStyle: style.borderColor,
                      lineWidth: style.borderWidth,
                      hidden: isNaN(data.datasets[0].data[i]) || meta.data[i].hidden,
                      // Extra data used for toggling the correct item
                      index: i
                    };
                  });
                }
                return [];
              }
            },
            // Handle legend item clicks
            onClick: (e, legendItem, legend) => {
              const index = legendItem.datasetIndex;
              const status = legend.chart.data.labels[index].toLowerCase();
              const dateType = legend.chart.canvas.id === 'dateFrameChart' ? 'date_frame' : 'today';
              
              // Toggle filter - click again to clear filter
              if (currentFilter.status === status && currentFilter.dateType === dateType) {
                filterProjectsByStatus(null, null);
              } else {
                filterProjectsByStatus(status, dateType);
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: ${value} (${percentage}%)`;
              },
              afterLabel: function() {
                return 'Click to filter projects';
              }
            }
          }
        },
        cutout: '60%',
        animation: {
          animateScale: true,
          animateRotate: true
        }
      }
    });
    
    // Store chart instance
    if (isDateFrame) {
      dateFrameChart = chart;
    } else {
      todayChart = chart;
    }
  }

  // Initialize the DataTable
  function initializeDataTable() {
    if (deptTable) {
      deptTable.destroy();
      $('.dt-buttons').remove();
    }
    
    deptTable = $('#deptTable').DataTable({
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
        {
          extend: 'copy',
          text: '<i class="fas fa-copy"></i> Copy',
          className: 'btn btn-primary',
          exportOptions: { columns: ':visible' }
        },
        {
          text: '<i class="fas fa-file-csv"></i>CSV',
          className: 'btn btn-primary',
          action: function() {
            downloadDeptReport('csv');
          }
        },
        {
          text: '<i class="fas fa-file-excel"></i> Excel',
          className: 'btn btn-primary',
          action: function() {
            downloadDeptReport('excel');
          }
        },
        {
          text: '<i class="fas fa-file-pdf"></i> PDF',
          className: 'btn btn-primary',
          action: function() {
            downloadDeptReport('pdf');
          }
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i> Print',
          className: 'btn btn-primary',
          exportOptions: { columns: ':visible' }
        }
      ],
      data: allProjectsData,
      responsive: true,
      language: {
        buttons: {
          copyTitle: 'Copied to clipboard',
          copySuccess: {
            _: '%d rows copied',
            1: '1 row copied'
          }
        },
        search: "_INPUT_",
        searchPlaceholder: "Search projects...",
        lengthMenu: "Show _MENU_ projects",
        info: "Showing _START_ to _END_ of _TOTAL_ projects",
        infoEmpty: "No projects found",
        infoFiltered: "(filtered from _MAX_ total projects)",
        paginate: {
          previous: "&laquo;",
          next: "&raquo;"
        }
      },
      initComplete: function() {
        // Move the buttons to our custom container
        $('.dt-buttons').appendTo(buttonContainer);
      },
      columns: [
        { 
          data: 'project_name',
          render: function(data, type, row) {
            const link = 'view-project.php?id=' + btoa(row.project_id);
            const priority = row.priority === 'High' ? ' <i class="fas fa-exclamation text-danger"></i>' : '';
            return `${priority} <a href="${link}" target="_blank">${data}</a>`;
          }
        },
        { 
          data: 'selected_department_start_date',
          render: function(data) {
            return data ? moment(data).format('DD MMM YYYY') : '-';
          }
        },
        { 
          data: 'selected_department_end_date',
          render: function(data) {
            return data ? moment(data).format('DD MMM YYYY') : '-';
          }
        },
        { 
          data: 'date_frame_status',
          render: function(data, type, row) {
            if (!data) return '-';
            
            const statusMap = {
              'assigned': { class: 'secondary', text: 'Assigned' },
              'completed': { class: 'success', text: 'Completed' },
              'ongoing': { class: 'primary', text: 'Ongoing' },
              'overdue': { class: 'danger', text: 'Overdue' },
              'uncategorized': { class: 'warning', text: 'Uncategorized' }
            };
            
            const status = statusMap[data.toLowerCase()] || { class: 'secondary', text: data };
            return `<span class="badge bg-${status.class}">${status.text}</span>`;
          }
        }
      ],
      pageLength: 10,
      lengthMenu: [10, 25, 50, 100],
      order: [[1, 'desc']],
      drawCallback: function() {
        // Update the status filter dropdown after table is drawn
        const statusSet = new Set();
        
        // Get unique status values
        this.api().cells(null, 3).every(function() {
          const data = this.data();
          if (data) statusSet.add(data);
        });
        
        // Update the status filter dropdown
        const $statusFilter = $('#statusFilter');
        const currentFilter = $statusFilter.val();
        $statusFilter.empty().append('<option value="">All Status</option>');
        
        Array.from(statusSet).sort().forEach(status => {
          $statusFilter.append(`<option value="${status}">${status}</option>`);
        });
        
        // Restore the filter selection
        if (currentFilter) {
          $statusFilter.val(currentFilter);
        }
      }
    });
    
    // Add status filter handler
    $('#statusFilter').off('change').on('change', function() {
      const status = $(this).val();
      deptTable.column(3).search(status, true, false).draw();
    });
  }

  function populateSummary(data){
    const total=data.length;
    let completed=0, ongoing=0, overdue=0;
    data.forEach(function(item){
      if(item.date_frame_status==='completed') completed++;
      else if(item.date_frame_status==='ongoing') ongoing++;
      else if(item.date_frame_status==='overdue') overdue++;
    });
    $('#countAssigned').text(total);
    $('#countCompleted').text(completed);
    $('#countOngoing').text(ongoing);
    $('#countOverdue').text(overdue);
  }

  function formatDate(data){
    if(!data) return '-';
    return moment(new Date(data)).format('DD MMM YYYY');
  }

  function downloadDeptReport(format) {
        const deptId = $('#id_dept').val();
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
        
        if (!deptId) {
            showError('Please select a department');
            return;
        }
        
        // Show loading indicator
        const $btn = $(`button:contains('Download ${format.toUpperCase()}')`);
        const originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Downloading...').prop('disabled', true);
        
        // Prepare request data
        const requestData = {
            access_token: '<?php echo $_SESSION["access_token"]; ?>',
            dept_id: deptId,
            from_date: fromDate,
            to_date: toDate,
            download_report_in: format
        };
        
        // Make API request with JSON
        fetch('<?php echo API_URL; ?>dept-report', {
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

  function drawDonut(chartData) {
    const ctx = document.getElementById("donutChart").getContext("2d");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: chartData.labels,
        datasets: chartData.datasets
      },
      options: {
        maintainAspectRatio: false,
        responsive: true,
        legend: { position: "bottom" }
      }
    });
  }
</script>

<?php include 'common/footer.php'; ?>