<?php include 'common/header.php'; ?>

<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
<!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
 

<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">User List</h3>
    <div class="card-tools">
      <a href="user-add" class="btn btn-success btn-sm">
        <i class="fas fa-user-plus"></i> Add User
      </a>
    </div>
  </div>

  <div class="card-body">
    
    <div class="new-pms-ap">
      <table id="userTable" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>WhatsApp Number</th>
            <th>Role</th>
            <th>Department</th>
            <th>Status</th>
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

<!-- datatable initialization -->
<script>
  $(function () {
    $('#userTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "order": [[1, "asc"]],
      "autoWidth": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      "ajax": {
        "url": "<?php echo rtrim(API_URL, '/'); ?>/user",
        "type": "POST",
        "headers": {
          "Content-Type": "application/json",
          "Authorization": "Bearer <?php echo $_SESSION['access_token']; ?>"
        },
        "data": function(d) {
          return JSON.stringify({
            "access_token": "<?php echo $_SESSION['access_token']; ?>"
          });
        },
        "processData": false,
        "dataSrc": function(json) {
          if (json.is_successful === "1" && json.data) {
            return json.data;
          }
          console.error('API Error:', json);
          return [];
        },
        "error": function(xhr, error, thrown) {
          console.error('Ajax error:', error);
          console.error('Server response:', xhr.responseText);
          alert('Error loading user data. Please try refreshing the page.');
        }
      },
      "columns": [
        { "data": "emp_id" },
        { "data": "emp_name" },
        { "data": "emp_email_id" },
        { "data": "emp_phone_number" },
        { "data": "emp_whatsapp_number" },
        { "data": "emp_role_name" },
        { "data": "dept_name" },
        { 
          "data": "emp_status",
          "render": function(data) {
            return data == 1 ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
          }
        },
        {
          "data": null,
          "render": function(data) {
            // Encode the emp_id using base64 for the URL
            const encodedId = btoa(data.emp_id);
            return `<a href="user-edit?id=${encodedId}" class="btn btn-primary btn-sm" title="Edit User" style="background-color: #30b8b9;border:none;">
                      <i class="fas fa-edit"></i> Edit
                    </a>`;
          }
        }
      ]
    });
  });

  $('#user a').addClass('active');
  $('#user a').addClass('nav-link');
</script>

<?php include 'common/footer.php'; ?>