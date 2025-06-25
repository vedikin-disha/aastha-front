<?php include 'common/header.php'; ?>


<!-- DataTables Bootstrap4 CSS -->
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<!-- <link rel="stylesheet" href="css/jquery.dataTables.min.css"> -->

<link rel="stylesheet" href="css/buttons.bootstrap4.min.css">


<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Taluka List</h3>
    <div class="card-tools">
      <a href="taluka-add" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Add Taluka
      </a>
    </div>
  </div>

  <div class="card-body">
    <div id="talukaTable_wrapper" class="dataTables_wrapper dt-bootstrap4"> </div>
    <div class="new-pms-ap">

      <table id="talukaTable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Taluka</th>
            <th>Subdivision</th>
            <th>Division</th>
            <th>Circle</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- datatable data -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap4 JS -->
<script src="js/dataTables.bootstrap4.min.js"></script>

<!-- DataTable Initialization -->
<script>
  $(function () {
    // Call /taluka API to get all talukas
    $.ajax({
      url: '<?php echo API_URL; ?>taluka',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),
      success: function(response) {
        // Initialize DataTable with API data
        $('#talukaTable').DataTable({
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
            { "data": "taluka_name" },
            { "data": "subdivision_name" },
            { "data": "division_name" },
            { "data": "circle_name" },
            { "data": "taluka_id", "className": 'text-center' }
          ],
          "createdRow": function (row, data, dataIndex) {
            // Encode the taluka_id using base64 for the URL
            const encodedId = btoa(data.taluka_id);
            $(row).find('td:eq(4)').html(
              '<a href="<?php echo BASE_URL; ?>taluka-edit?id=' + encodedId + '" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;"><i class="fas fa-edit"></i> Edit</a>'
            );
          }
        })
      },
      error: function(response) {
        showToast('Failed to load talukas', false);
      }
    });
    // add nav-link active class to id = taluka
    $('#taluka a').addClass('active nav-link');
  });
  $('#taluka a').addClass('nav-link');
</script>


<?php include 'common/footer.php'; ?>