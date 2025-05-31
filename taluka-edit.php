<?php include 'common/header.php'; ?>
<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Edit Taluka</h3>
  </div>
  <div class="card-body">
    <form method="post" id="talukaForm">
      <input type="hidden" name="taluka_id" id="taluka_id">
      
      <!-- Circle Selection (Read-only) -->
      <div class="form-group mb-3">
        <label for="circle_name" class="form-label">Circle</label>
        <div class="input-group">
          <input type="text" name="circle_name" id="circle_name" class="form-control" readonly>
          <input type="hidden" name="circle_id" id="circle_id">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>
      
      <!-- Division Selection (Read-only) -->
      <div class="form-group mb-3">
        <label for="division_name" class="form-label">Division</label>
        <div class="input-group">
          <input type="text" name="division_name" id="division_name" class="form-control" readonly>
          <input type="hidden" name="division_id" id="division_id">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-building"></i></span>
          </div>
        </div>
      </div>

      <!-- Subdivision Selection (Read-only) -->
      <div class="form-group mb-3">
        <label for="subdivision_name" class="form-label">Subdivision</label>
        <div class="input-group">
          <input type="text" name="subdivision_name" id="subdivision_name" class="form-control" readonly>
          <input type="hidden" name="sub_id" id="sub_id">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
          </div>
        </div>
      </div>

      <!-- Taluka Name -->
      <div class="form-group mb-3">
        <label for="taluka_name" class="form-label">Taluka Name</label>
        <div class="input-group">
          <input type="text" name="taluka_name" id="taluka_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
          </div>
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="taluka-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  // Add active class to navigation
  $('#taluka a').addClass('active nav-link');
  
  // Get taluka ID from URL parameter
  var urlParams = new URLSearchParams(window.location.search);
  var talukaId = urlParams.get('id');
  
  if (!talukaId) {
    showToast('No taluka ID provided', false);
    window.location.href = '<?php echo BASE_URL; ?>taluka-list';
    return;
  }
  
  // Fetch taluka data
  $.ajax({
    url: '<?php echo API_URL; ?>taluka',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
      taluka_id: talukaId,
      access_token: "<?php echo $_SESSION['access_token']; ?>"
    }),
    success: function(response) {
    //   console.log('Taluka data response:', response);
      // Check if response is an array or a single object
      if (Array.isArray(response) && response.length > 0) {
        var taluka = response[0];
        setFormValues(taluka);
      } else if (response && typeof response === 'object' && response.taluka_id) {
        // If response is a single object (not in an array)
        setFormValues(response);
      } else {
        showToast('Taluka not found', false);
        // console.error('Unexpected response format:', response);
      }
    },
    error: function(response) {
    //   console.error('Error fetching taluka:', response);
      showToast('Failed to load taluka data', false);
      // Don't redirect, just show the error message
    }
  });
  
  // Helper function to set form values
  function setFormValues(taluka) {
    // console.log('Setting form values with:', taluka);
    
    // Set form values
    $('#taluka_id').val(taluka.taluka_id);
    $('#taluka_name').val(taluka.taluka_name);
    $('#circle_name').val(taluka.circle_name);
    $('#circle_id').val(taluka.circle_id);
    $('#division_name').val(taluka.division_name);
    $('#division_id').val(taluka.division_id);
    $('#subdivision_name').val(taluka.subdivision_name);
    $('#sub_id').val(taluka.sub_id);
    
    // Debug output to verify values are set
    // console.log('Form values after setting:',{
    //   taluka_id: $('#taluka_id').val(),
    //   taluka_name: $('#taluka_name').val(),
    //   circle_name: $('#circle_name').val(),
    //   circle_id: $('#circle_id').val(),
    //   division_name: $('#division_name').val(),
    //   division_id: $('#division_id').val(),
    //   subdivision_name: $('#subdivision_name').val(),
    //   sub_id: $('#sub_id').val()
    // });
  }
  
  // Handle form submission
  $('#talukaForm').submit(function(e) {
    e.preventDefault();
    
    var taluka_id = $('#taluka_id').val();
    var taluka_name = $('#taluka_name').val();
    var sub_id = $('#sub_id').val();
    var division_id = $('#division_id').val();
    var circle_id = $('#circle_id').val();
    
    if (!taluka_name) {
      showToast('Please enter taluka name', false);
      return;
    }
    
    // Call update-taluka API
    $.ajax({
      url: '<?php echo API_URL; ?>update-taluka',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        taluka_id: taluka_id,
        taluka_name: taluka_name,
        sub_id: sub_id,
        division_id: division_id,
        circle_id: circle_id,
        access_token: "<?php echo $_SESSION['access_token']; ?>"
      }),
      success: function(response) {
        // console.log('Update taluka response:', response);
        
        if (response.is_successful === "1") {
          showToast(response.success_message || 'Taluka updated successfully');
          window.location.href = "<?php echo BASE_URL; ?>taluka-list";
        } else {
          let errorMsg = 'Failed to update taluka';
          
          if (response.errors && Object.keys(response.errors).length > 0) {
            errorMsg = Object.values(response.errors).flat().join(', ');
          } else if (response.error) {
            errorMsg = response.error;
          }
          
          showToast(errorMsg, false);
        }
      },
      error: function(response) {
        // console.error('Update taluka error:', response);
        showToast('Taluka with this name already exists', false);
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
