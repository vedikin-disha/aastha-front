<?php include 'common/header.php'; ?>

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Subdivision</h3>
  </div>
  <div class="card-body">
    <form method="post" id="subdivisionForm">
      
      <!-- Circle Selection (Read-only) -->
      <div class="form-group mb-3">
        <label for="circle_name" class="form-label">Circle</label>
        <div class="input-group">
          <input type="text" name="circle_name" id="circle_name" class="form-control" readonly>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>
      
      <!-- Division Selection -->
      <div class="form-group mb-3">
        <label for="division_name" class="form-label">Division</label>
        <div class="input-group">
          <input type="text" name="division_name" id="division_name" class="form-control" readonly>
          <input type="hidden" name="division_id" id="division_id">
          <input type="hidden" name="circle_id" id="circle_id">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-building"></i></span>
          </div>
        </div>
      </div>

      <!-- Subdivision Name -->
      <div class="form-group mb-3">
        <label for="subdivision_name" class="form-label">Subdivision Name</label>
        <div class="input-group">
          <input type="text" name="subdivision_name" id="subdivision_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
          </div>
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="subdivision-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
  // add nav-link active class to id = subdivision
  $('#subdivision a').addClass('active nav-link');
</script>

<script>
  // Call /subdivision API to get individual subdivision by id
  $.ajax({
    url: '<?php echo API_URL; ?>subdivision',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      sub_id: <?php echo $_GET['id']; ?>,
      access_token: "<?php echo $_SESSION['access_token']; ?>"
    }),
    success: function(response) {
      console.log('Subdivision API response:', response);
      // Handle the actual response structure from the API
      if (response) {
        // The API returns the data directly in the response object
        $('#subdivision_name').val(response.subdivision_name);
        $('#division_name').val(response.division_name);
        $('#division_id').val(response.division_id);
        $('#circle_name').val(response.circle_name);
        // Store circle_id in a hidden variable
        $('#circle_id').val(response.circle_id);
        console.log('Set division_id to:', response.division_id);
        console.log('Set circle_id to:', response.circle_id);
      } else {
        showToast('Failed to load subdivision data', false);
        console.error('Invalid API response structure:', response);
      }
    },
    error: function(response) {
      showToast('Error loading subdivision data', false);
      console.error('API error:', response);
    }
  });
</script>

<script>
  $(document).ready(function() {
    $('#subdivisionForm').submit(function(e) {
      e.preventDefault();
      var subdivision_name = $('#subdivision_name').val();
      var division_id = $('#division_id').val();
      
      if (!subdivision_name) {
        showToast('Please enter subdivision name', false);
        return;
      }
      
      if (!division_id) {
        showToast('Division ID is required', false);
        return;
      }
      
      console.log('Submitting with division_id:', division_id);
      
      $.ajax({
        url: '<?php echo API_URL; ?>update-subdivision',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          sub_name: subdivision_name,
          division_id: division_id,
          circle_id: $('#circle_id').val(),
          access_token: "<?php echo $_SESSION['access_token']; ?>",
          sub_id: <?php echo $_GET['id']; ?>
        }),
        success: function(response) {
          console.log('Update response:', response);
          if (response.is_successful === "1") {
            showToast(response.success_message || 'Subdivision updated successfully');
            window.location.href = "<?php echo BASE_URL; ?>subdivision-list";
          } else {
            let errorMsg = 'Failed to update subdivision';
            if (response.errors) {
              errorMsg = Object.values(response.errors).flat().join(', ');
            }
            showToast(errorMsg, false);
          }
        },
        error: function(response) {
          console.error('Update error:', response);
          showToast('Error updating subdivision', false);
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