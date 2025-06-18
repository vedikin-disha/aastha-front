<?php 
include 'common/header.php';
?>

<div class="p-3">
  <!-- Filters Card -->
  <div class="card mb-4">
    <div class="card-body">
      <form id="filterForm">
        <div class="row align-items-end">
          <!-- Department -->
          <div class="col-md-4 mb-3">
            <label for="id_dept" class="form-label fw-semibold">Department</label>
            <select name="department" id="id_dept" class="form-control select2" data-placeholder="Select Department">
              <option value="">All Departments</option>
              <!-- Dynamic Options -->
            </select>
          </div>

          <!-- Time Range -->
          <div class="col-md-4 mb-3">
            <label for="timeRange" class="form-label fw-semibold">Time Range</label>
            <select id="timeRange" class="form-control">
              <option value="today">Today</option>
              <option value="last_week">Last Week</option>
              <option value="current_week">Current Week</option>
              <option value="current_month" selected>Current Month</option>
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
            <button type="submit" class="btn btn-primary px-5">Apply</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Chart Card -->
  <div class="card">
    <div class="card-body">
      <canvas id="donutChart" style="min-height:250px;"></canvas>
    </div>
  </div>

  <!-- Department Report Table -->
  <div class="card mt-4">
    <div class="card-header">
      <h5 class="card-title mb-0">Department Report Details</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="deptTable" class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Project Name</th>
              <th>Department Start Date</th>
              <th>Department End Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Dependencies -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap4.min.js"></script>
<!-- Chart.js (skip if already loaded by AdminLTE) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.getElementById("timeRange").addEventListener("change", function () {
  const customRange = document.getElementById("customRange");
  if (this.value === "custom") {
    customRange.classList.remove("d-none");
  } else {
    customRange.classList.add("d-none");
  }
});

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
}

  // show/hide custom range inputs
  $('#timeRange').on('change', function(){
    $('#customRange').toggleClass('d-none', this.value!=='custom');
  });

  // Form submit
  $('#filterForm').on('submit', function(e){ e.preventDefault(); loadReport(); });

  var deptTable = null;

  var donutChart;

  function loadReport(){
    // hide chart until data arrives
    $('#chartCard').addClass('d-none');
    
    const dept_id = parseInt($('#id_dept').val()) || 0;
    let from_date, to_date;
    const today = moment();

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
        break;
    }

    const params = {access_token:'<?php echo $_SESSION['access_token']; ?>', dept_id, from_date, to_date};

    $.ajax({
      url:'<?php echo API_URL; ?>dept-pie-chart',
      type:'POST', contentType:'application/json', data:JSON.stringify(params), success:function(res){
        if(res.is_successful==='1' && res.data){
          renderDonut(res.data.date_frame);
        }
      }
    });

    $.ajax({
      url:'<?php echo API_URL; ?>dept-report',
      type:'POST', contentType:'application/json', data:JSON.stringify(params), success:function(res){
        if(res.is_successful==='1'){
          populateSummary(res.data);
          if(!deptTable){
              deptTable = $('#deptTable').DataTable({
                columns: [
                  {
                    data: 'project_name',
                    render: function(data, type, row) {
                      var link = 'view-project.php?id=' + btoa(row.project_id);
                      var priority = row.priority === 'High' ? ' <i class="fas fa-exclamation text-danger"></i>' : '';
                      return `${priority}   <a href="${link}" target="_blank">${data}</a>`;
                    }
                  },
                  {
                    data: 'selected_department_start_date',
                    render: function(data) {
                      return data ? formatDate(data) : '-';
                    }
                  },
                  {
                    data: 'selected_department_end_date',
                    render: function(data) {
                      return data ? formatDate(data) : '-';
                    }
                  },
                  {
                    data: 'date_frame_status',
                    render: function(data) {
                      if (data === 'uncategorized') {
                        return '<span class="badge bg-warning">Uncategorized</span>';
                      }
                      return data;
                    }
                  }
                ]
              });
          }
          deptTable.clear().rows.add(res.data).draw();
        }
      }
    });
  }

  function renderDonut(chartData){
    if(!chartData) return;
    const ctx=document.getElementById('donutChart').getContext('2d');
    if(donutChart){ donutChart.destroy(); }
    donutChart=new Chart(ctx,{type:'doughnut',data:{labels:chartData.labels,datasets:chartData.datasets},options:{maintainAspectRatio:false,responsive:true,legend:{position:'bottom'}}});
    // show chart now that it has data
    $('#chartCard').removeClass('d-none');
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
});

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