<?php include 'common/header.php'; ?>

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Proposed Work List</h3>
    <div class="card-tools">
      <a href="proposed-work-add" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Add Proposed Work
      </a>
    </div>
  </div>

  <div class="card-body">
    <div id="proposedWorkTable_wrapper" class="dataTables_wrapper dt-bootstrap4"></div>
    <table id="proposedWorkTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Proposed Work</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- Data Table Data -->
      </tbody>
    </table>
  </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>
  $(function () {
    // Call proposed-work-listing API
    $.ajax({
      url: '<?php echo API_URL; ?>proposed-work-listing',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),
      success: function(response) {
        // Initialize DataTable with the response data
        $('#proposedWorkTable').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "autoWidth": false,
          "responsive": true,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
          "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
          "data": response.data || [],
          "columns": [
            { "data": "proposed_work_name" },
            { 
              "data": "proposed_work_id",
              "render": function(data, type, row) {
                // Encode the ID using base64 for the URL
                const encodedId = btoa(data);
                return '<a href="<?php echo BASE_URL; ?>proposed-work-edit?id=' + encodedId + '" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;"><i class="fas fa-edit"></i> Edit</a>';
              }
            }
          ],
          "createdRow": function (row, data, dataIndex) {
            // Add any row-specific styling or functionality here
          }
        });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching proposed work data:', error);
        showToast('Failed to load proposed work data', false);
      }
    });
    
    // Add active class to navigation
    $('#proposedWorkNav a').addClass('active');
    $('#proposedWorkNav a').addClass('nav-link');
  });
</script>

<?php include 'common/footer.php'; ?>
