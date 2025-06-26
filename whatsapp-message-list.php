<?php include 'common/header.php'; ?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
      <div class="d-flex justify-content-between align-items-center">
        <h3 class="card-title">Whatsapp Message List</h3>
        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
          <div class="card-tools">
            <a href="whatsapp-message-add" class="btn btn-success">
              <i class="fas fa-plus"></i> Add New Message
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card-body">
      <!-- Table -->
      <div class="new-pms-ap">
        <table id="whatsappMessageTable" class="table table-bordered table-hover dataTable">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Message</th>
              <th>Status</th>
              <th>Scheduled Time</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include 'common/footer.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
  var table = $('#whatsappMessageTable').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "<?php echo API_URL; ?>schedule-message-list",
      "type": "POST",
      "contentType": "application/json",
      "data": function (d) {
        return JSON.stringify({
          access_token: '<?php echo $_SESSION['access_token']; ?>',
          sort_column: d.columns[d.order[0].column].data,
          sort_order: d.order[0].dir,
          limit: d.length,
          page: Math.floor(d.start / d.length) + 1,
          search: d.search.value
        });
      },
      "dataSrc": function (json) {
        if (json.is_successful === "1") {
          json.recordsTotal = json.data.meta.total;
          json.recordsFiltered = json.data.meta.total;
          return json.data.records || [];
        } else {
          showToast(json.errors || 'Failed to load scheduled messages', false);
          return [];
        }
      },
      "error": function (xhr, error, thrown) {
        console.error('Error loading scheduled messages:', error);
        showToast('Error loading scheduled messages', false);
      }
    },
    "columns": [
      {
        "data": "emp_name",
        "render": function (data, type, row) {
          let name = row.emp_name || 'N/A';
          let phone = row.phone_number || '';
          return `${name}<br><small class="text-muted">${phone}</small>`;
        }
      },
      { "data": "message" },
      {
        "data": "is_sent",
        "render": function (data) {
          return data == 1
            ? '<span class="badge badge-success">Sent</span>'
            : '<span class="badge badge-warning">Pending</span>';
        }
      },
      {
        "data": "schedule_time",
        "render": function (data) {
          return data ? new Date(data).toLocaleString() : 'N/A';
        }
      },
      {
        "data": "shed_id",
        "orderable": false,
        "render": function (data) {
          return `
            <div class="btn-group">
              <a href="whatsapp-message-edit?id=${btoa(data)}" class="btn btn-sm btn-primary" style="background-color: #30b8b9;border:none;">
                <i class="fas fa-edit"></i> Edit
              </a>
            </div>
          `;
        }
      }
    ],
    "responsive": true,
    "autoWidth": false,
    "order": [[0, 'desc']],
    "pageLength": 10,
    "language": {
      "emptyTable": "No scheduled messages found",
      "loadingRecords": "Loading...",
      "processing": "Processing..."
    }
  });
});
</script>
