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
    <form method="post" id="divisionForm">
      
      
      <!-- Circle Selection -->
      <div class="form-group mb-3">
        <label for="id_circle" class="form-label">Circle</label>
        <div class="input-group">
          <input type="text" name="circle_name" id="circle_name" class="form-control" readonly>
          <input type="hidden" name="circle_id" id="circle_id">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>

      <!-- Division Name -->
      <div class="form-group mb-3">
        <label for="id_division_name" class="form-label">Division Name</label>
        <div class="input-group">
        <input type="text" name="division_name" id="division_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-check"></i></span>
          </div>
        </div>
        
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="division-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>



<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
  // add nav-link active class to id = division
  $('#division a').addClass('active');
  $('#division a').addClass('nav-link');
</script>

<script>
  // Call /division API to get individual division by id
  $.ajax({
    url: '<?php echo API_URL; ?>division',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      division_id: <?php echo $_GET['id']; ?>,
      access_token: "<?php echo $_SESSION['access_token']; ?>"
    }),
    success: function(response) {
      console.log('Division API response:', response);
      // Handle the actual response structure from the API
      if (response) {
        // The API returns the data directly in the response object
        $('#division_name').val(response.division_name);
        $('#circle_name').val(response.circle_name);
        $('#circle_id').val(response.circle_id);
        console.log('Set circle_id to:', response.circle_id);
      } else {
        showToast('Failed to load division data', false);
        console.error('Invalid API response structure:', response);
      }
    },
    error: function(response) {
      showToast('Error loading division data', false);
      console.error('API error:', response);
    }
  });
</script>

<script>
  $(document).ready(function() {
    // Add active class to navigation
    $('#division a').addClass('active nav-link');
    
    $('#divisionForm').submit(function(e) {
      e.preventDefault();
      var division_name = $('#division_name').val();
      var circle_id = $('#circle_id').val();
      
      if (!division_name) {
        showToast('Please enter division name', false);
        return;
      }
      
      if (!circle_id) {
        showToast('Circle ID is required', false);
        return;
      }
      
      console.log('Submitting with circle_id:', circle_id);
      
      $.ajax({
        url: '<?php echo API_URL; ?>update-division',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          division_name: division_name,
          circle_id: circle_id,
          access_token: "<?php echo $_SESSION['access_token']; ?>",
          division_id: <?php echo $_GET['id']; ?>
        }),
        success: function(response) {
          console.log('Update response:', response);
          if (response.is_successful === "1") {
            showToast(response.success_message || 'Division updated successfully');
            window.location.href = "<?php echo BASE_URL; ?>division-list";
          } else {
            let errorMsg = 'Failed to update division';
            if (response.errors) {
              errorMsg = Object.values(response.errors).flat().join(', ');
            }
            showToast(errorMsg, false);
          }
        },
        error: function(response) {
          console.error('Update error:', response);
          showToast('Error updating division', false);
        }
      });
    });
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