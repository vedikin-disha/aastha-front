<?php include 'common/header.php'; ?>

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">



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
    <div class="new-pms-ap">

      <table id="divisionTable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Division</th>
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
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "data": response,
            "columns": [
              { "data": "division_name" },
              { "data": "circle_name" },
              { "data": "division_id" },
            ],
            "createdRow": function (row, data, dataIndex) {
              // Encode the division_id using base64 for the URL
              const encodedId = btoa(data.division_id);
              $(row).find('td:eq(2)').html(
                '<a href="<?php echo BASE_URL; ?>division-edit?id=' + encodedId + '" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;"><i class="fas fa-edit"></i> Edit</a>'
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