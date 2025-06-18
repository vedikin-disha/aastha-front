<?php include 'common/header.php'; ?>

<style>

  .input-group {

    margin-bottom: 1rem !important;

  }

  .form-control {

    height: 38px;

  }

  .alert-danger {

    margin-top: 10px;

    padding: 10px;

    border-radius: 4px;

  }

  

</style>



<div class="card card-primary">

  <div class="card-header">

    <h3 class="card-title">Edit User</h3>

  </div>

  <div class="card-body">

    <!-- data table data -->

  </div>

</div>



<!-- Font Awesome -->

<link rel="stylesheet" href="css/all.min.css">



<!-- Select2 CSS -->

<link href="css/select2.min.css" rel="stylesheet" />

<!-- Select2 Bootstrap 5 Theme -->

<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />



<!-- jQuery -->

<script src="js/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->

<script src="js/select2.full.min.js"></script>

<script src="js/vfs_fonts.js"></script>



<script>

  $(document).ready(function() {

    // Initialize Select2 for Role, Department, and Status

    $('#id_emp_role_id, #id_dept_id, #id_emp_status').select2({

      theme: 'bootstrap-5',

      width: '100%',

      dropdownParent: $('#editUserForm')

    });



    // Add active class to navigation

    $('#user a').addClass('active nav-link');

  });

</script>

<?php include 'common/footer.php'; ?>

