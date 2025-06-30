<?php include 'common/header.php'; ?>



<div class="container-fluid">
    <div class="card">
        <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">License List</h3>

                <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
                    <div class="card-tools">
                    <a href="license-add" class="btn btn-success" ><i class="fas fa-plus"></i> Add New License</a>
                    </div>
                        <?php endif; ?>
                
            </div>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <form method="get" class="mb-4">
               
            </form>
            <div class="new-pms-ap">
                <table id="licenseTable" class="table table-bordered table-hover dataTable">
                    <thead>
                        <tr>
                            <th style="width: 5%;"></th>
                            <th class="sorting">License ID</th>
                            <th>User Name</th>
                            <!-- <th>Product Name</th> -->
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>    
                <tbody>

                </tbody>
            </table>
        </div>
        
        <!-- DataTables handles pagination now -->
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
        "data": "start_date", 
        "title": "Start Date",
        
      },
      { 
        "data": "end_date", 
        "title": "End Date",
        
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
              <a href="license-edit?id=${encodedId}" class="btn btn-sm btn-primary" style="background-color: #30b8b9;border:none;">
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
</script>

