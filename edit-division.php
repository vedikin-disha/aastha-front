<?php include 'common/header.php'; ?>

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Division</h3>
  </div>
  <div class="card-body">
    <!-- data table data -->
  </div>
</div>



<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '80%',
      placeholder: 'Select a Circle',
      allowClear: false,
      minimumResultsForSearch: 5
    });
    
    // add nav-link active class to id = division
    $('#division a').addClass('active');
    $('#division a').addClass('nav-link');
  });
</script>

<style>
  .form-control {
    height: 38px;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
</style>



<?php include 'common/footer.php'; ?>                                              