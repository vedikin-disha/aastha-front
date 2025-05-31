<?php include 'common/header.php'; ?>



<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add Division</h3>
  </div>
  <div class="card-body">
    
  <form method="post" id="divisionForm">
      
      <!-- Circle Selection -->
      <div class="form-group mb-3">
        <label for="id_circle" class="form-label">Circle</label>
        <div class="input-group">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
          <select name="circle" id="id_circle" class="form-control">
            <option value="">Select Circle</option>
            <?php foreach ($circles as $circle): ?>
              <option value="<?php echo $circle['id']; ?>"><?php echo $circle['circle_name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Division Name -->
      <div class="form-group mb-3">
        <label for="id_division_name" class="form-label">Division Name</label>
        <div class="input-group">
        <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
          <input type="text" name="division_name" id="id_division_name" class="form-control">
          
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="division_list.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>

  </div>

</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script>
$(document).ready(function() {
  // Initialize Select2
  $('#id_circle').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#divisionForm')
  });
  
  // Add active class to navigation
  $('#division a').addClass('active nav-link');
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