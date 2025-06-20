<?php include 'common/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">License Management</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">License List</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">License List</h3>
        <div class="card-tools">
          <a href="license-add" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Add License
          </a>
        </div>
      </div>
      <div class="card-body">
        <table id="licenseTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>License ID</th>
              <th>User Name</th>
              <th>Product Name</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Status</th>
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
  var table = $('#licenseTable').DataTable({
    "processing": true,
    "serverSide": false,
    "ajax": {
      "url": "<?php echo API_URL; ?>license-list",
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
          showToast(json.errors || 'Failed to load license data', false);
          return [];
        }
      },
      "error": function(xhr, error, thrown) {
        console.error('Error loading license data:', error);
        showToast('Error loading license data', false);
      }
    },
    "columns": [
      { "data": "license_id", "title": "License ID" },
      { "data": "user_name", "title": "User Name" },
      { 
        "data": "product_id", 
        "title": "Product",
        "render": function(data) {
          return 'Product ' + data;
        }
      },
     
      { 
        "data": "start_date", 
        "title": "Start Date",
        "render": function(data) {
          return data ? new Date(data).toLocaleDateString() : '';
        }
      },
      { 
        "data": "end_date", 
        "title": "End Date",
        "render": function(data) {
          return data ? new Date(data).toLocaleDateString() : '';
        }
      },
      { 
        "data": "status",
        "title": "Status",
        "render": function(data) {
          return data == 1 
            ? '<span class="badge badge-success">Active</span>'
            : '<span class="badge badge-danger">Inactive</span>';
        }
      },
      {
        "data": "license_id",
        "title": "Actions",
        "orderable": false,
        "render": function(data) {
          // Encode the license ID in base64 for the URL
          const encodedId = btoa(data);
          return `
            <div class="btn-group">
              <a href="license-edit?id=${encodedId}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit
              </a>
            </div>
          `;
        }
      }
    ],
    "responsive": true,
    "autoWidth": false,
    "order": [[0, 'desc']]
  });

  // Handle delete button click
  $(document).on('click', '.delete-license', function() {
    var licenseId = $(this).data('id');
    if (confirm('Are you sure you want to delete this license?')) {
      // Add delete functionality here
      console.log('Delete license:', licenseId);
      // You can implement the delete API call here
    }
  });
});
</script></script>

