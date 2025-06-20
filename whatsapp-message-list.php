<?php include 'common/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Whatsapp Message Management</h1>
      </div>
      
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Whatsapp Message List</h3>
        <div class="card-tools">
          <a href="whatsapp-message-add" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Add Whatsapp Message
          </a>
        </div>
      </div>
      <div class="card-body">
        <table id="whatsappMessageTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Message</th>
              <th>Status</th>
              <th>Scheduled Time</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data will be loaded via AJAX -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#whatsappMessageTable').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": {
      "url": "<?php echo API_URL; ?>schedule-message-list",
      "type": "POST",
      "contentType": "application/json",
      "data": function() {
        return JSON.stringify({
          access_token: '<?php echo $_SESSION['access_token']; ?>'
        });
      },
      "dataSrc": function(json) {
        if (json.is_successful === "1") {
          return json.data || [];
        } else {
          showToast(json.errors || 'Failed to load scheduled messages', false);
          return [];
        }
      },
      "error": function(xhr, error, thrown) {
        console.error('Error loading scheduled messages:', error);
        showToast('Error loading scheduled messages', false);
      }
    },
    "columns": [
      { 
        "data": null,
        "render": function(data) {
          let name = data.emp_name || 'N/A';
          let phone = data.phone_number || '';
          return `${name}<br><small class="text-muted">${phone}</small>`;
        }
      },
      { "data": "message" },
      { 
        "data": "is_sent",
        "render": function(data) {
          return data == 1 
            ? '<span class="badge badge-success">Sent</span>'
            : '<span class="badge badge-warning">Pending</span>';
        }
      },
      { 
        "data": "schedule_time",
        "render": function(data) {
          return data ? new Date(data).toLocaleString() : 'N/A';
        }
      },
      {
        "data": "shed_id",
        "orderable": false,
        "render": function(data) {
          return `
            <div class="btn-group">
              <a href="whatsapp-message-edit?id=${btoa(data)}" class="btn btn-sm btn-primary">
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
