<?php include 'common/header.php'; ?>

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">


<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Division List</h3>
    <div class="card-tools">
      <a href="division-add" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Add Division
      </a>
    </div>
  </div>

  <div class="card-body">
    <div id="divisionTable_wrapper" class="dataTables_wrapper dt-bootstrap4"> </div>
    <table id="divisionTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Division Name</th>
          <th>Circle</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- data table data -->
      </tbody>
    </table>
  </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<!-- DataTable Initialization -->
<script>
  $(function () {
    // /division api
    // request: {"access_token": "jwt.token.here"}
    // Response: {"status": 200, "data": [{"division_id": 1, "division_name": "Division 1", "circle_id": 1, "circle_name": "Circle 1"}, ...], "message": "Division data retrieved successfully"}

    $.ajax({
      url: '<?php echo API_URL; ?>division',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),
      success: function(response) {
        // if (response.status == 200) {
          // update datatable
          $('#divisionTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "autoWidth": false,
            "responsive": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "dom": 'Bfrtip',
            "data": response,
            "columns": [
              { "data": "division_name" },
              { "data": "circle_name" },
              { "data": "division_id" },
            ],
            "createdRow": function (row, data, dataIndex) {
              $(row).find('td:eq(2)').html(
                '<a href="<?php echo BASE_URL; ?>division-edit?id=' + data.division_id + '" class="btn btn-primary btn-sm">Edit</a>'
              );
            }
          });
        // } else {
        //   showToast(response.message, false);
        // }
      },
      error: function(response) {
        showToast(response.message, false);
      }
    });

    // add nav-link active class to id = division
    $('#division a').addClass('active');
    $('#division a').addClass('nav-link');
  });
</script>

<?php include 'common/footer.php'; ?>