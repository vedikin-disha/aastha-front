<?php include 'common/header.php'; ?>
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<div class="card card-primary card-outline">

  <div class="card-header">

    <h3 class="card-title">Subdivision List</h3>

    <div class="card-tools">

      <a href="subdivision-add" class="btn btn-success btn-sm">

        <i class="fas fa-plus"></i> Add Subdivision

      </a>

    </div>

  </div>



  <div class="card-body">

    <div id="subdivisionTable_wrapper" class="dataTables_wrapper dt-bootstrap4"> </div>

    <div class="new-pms-ap">
        
      <table id="subdivisionTable" class="table table-bordered table-hover">

      <thead>
        
        <tr>
          
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



<!-- <script src="js/jquery-3.5.1.js"></script> -->
<script src="
https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js
"></script>

<script src="js/jquery.dataTables.min.js"></script>

<script src="js/dataTables.bootstrap4.min.js"></script>




<!-- DataTable Initialization -->

<script>

  // Call /subdivision API to get all subdivisions

  $.ajax({

    url: '<?php echo API_URL; ?>subdivision',

    type: 'POST',

    contentType: 'application/json',

    data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),

    success: function(response) {

      // Initialize DataTable with API data

      $('#subdivisionTable').DataTable({

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

          { "data": "subdivision_name" },

          { "data": "division_name" },

          { "data": "circle_name" },

          { "data": "sub_id", "className": 'text-center' }

        ],

        "createdRow": function (row, data, dataIndex) {

          // Encode the sub_id using base64 for the URL
          const encodedId = btoa(data.sub_id);
          $(row).find('td:eq(3)').html(
            '<a href="<?php echo BASE_URL; ?>subdivision-edit?id=' + encodedId + '" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;"><i class="fas fa-edit"></i> Edit</a>'
          );

        }

      });

    },

    error: function(response) {

      showToast('Failed to load subdivisions', false);

    }

  });

  

  // add nav-link active class to id = subdivision

  $('#subdivision a').addClass('active');

  $('#subdivision a').addClass('nav-link');

</script>



<?php include 'common/footer.php'; ?>



