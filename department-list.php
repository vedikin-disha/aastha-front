<?php include 'common/header.php'; ?>



<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->





<div class="card card-primary card-outline">

  <div class="card-header">

    <h3 class="card-title">Department List</h3>

    <div class="card-tools">

      <a href="add-department" class="btn btn-success btn-sm">

        <i class="fas fa-plus"></i> Add Department

      </a>

    </div>

  </div>



  <div class="card-body">
    <div class="table-responsive">
      <table id="circleTable" class="table table-bordered table-striped table-hover">
        <thead class="thead-light">
          <tr>
            <th>Department Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data will be loaded by DataTables -->
        </tbody>
       
      </table>
    </div>
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

      url: '<?php echo API_URL; ?>department',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),

      success: function(response) {

        // if (response.status == 200) {

          // update datatable

           // update datatable to call /circle-list/ api

            $('#circleTable').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "autoWidth": false,
              "responsive": true,
              "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
              "dom": 'Bfrtip',
              "data": response.data, // Access the data array from the response
              "columns": [
                { 
                  "data": "dept_name",
                  "title": "Department Name"
                },
                // action
                {
                    title: "Actions",
                    "data": null,
                    "defaultContent": '',
                    "orderable": false,
                    "className": 'text-center',
                    "render": function(data, type, row) {
                        const encodedId = btoa(row.dept_id);
                        return `
                          <div class="btn-group">
                            <a href="edit-department?id=${encodedId}" class="btn btn-primary btn-sm" style="background-color: #30b8b9;border:none;">
                              <i class="fas fa-edit"></i> Edit
                            </a>
                          </div>
                        `;
                    }
                }
              ],
              "order": [[0, 'asc']], // Sort by department name by default
              "language": {
                "emptyTable": "No department data available",
                "search": "Search:",
                "paginate": {
                  "first": "First",
                  "last": "Last",
                  "next": "Next",
                  "previous": "Previous"
                }
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

  // Add active class to department menu
  $('#department a').addClass('active');
  $('#department a').addClass('nav-link');
  




</script>



<?php include 'common/footer.php'; ?>