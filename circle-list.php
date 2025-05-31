<?php include 'common/header.php'; ?>

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">


<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Circle List</h3>
    <div class="card-tools">
      <a href="circle-add" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Add Circle
      </a>
    </div>
  </div>

  <div class="card-body">
    <div id="circleTable_wrapper" class="dataTables_wrapper dt-bootstrap4"></div>
    <table id="circleTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Circle Name</th>
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

<!-- DataTable Initialization -->
<script>
  $(function () {
  // /circle api
  // request: {"access_token": "jwt.token.here"}	
  // Response: {"status": 200, "data": [{"circle_id": 1, "circle_name": "Circle 1"}, ...], "message": "Circle data retrieved successfully"}
  // call

    $.ajax({
      url: '<?php echo API_URL; ?>circle',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),
      success: function(response) {
        // if (response.status == 200) {
          // update datatable
           // update datatable to call /circle-list/ api
            $('#circleTable').DataTable({
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
                { "data": "circle_name" },
                { "data": "circle_id" },
                // { "data": "action" }
              ],
              "createdRow": function (row, data, dataIndex) {
                // ignore first row
                if (dataIndex == 0) {
                  return;
                }
                $(row).find('td:eq(1)').html(
                  '<a href="<?php echo BASE_URL; ?>circle-edit?id=' + data.circle_id + '" class="btn btn-primary btn-sm">Edit</a>'
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

   
  });
  // add nav-link active class to id = circle
  $('#circle a').addClass('active');
  $('#circle a').addClass('nav-link');


</script>

<?php include 'common/footer.php'; ?>