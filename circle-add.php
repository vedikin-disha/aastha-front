<?php include 'common/header.php'; ?>

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Add Circle</h3>
  </div>
  <div class="card-body">
  <form method="post" id="circleForm">
      
      <div class="form-group mb-3">
        <label for="circle_name" class="form-label">Circle</label>
        <div class="input-group">
          <input type="text" name="circle_name" id="circle_name" class="form-control" required>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border: 1px solid #30b8b9;">Save</button>
        <a href="circle-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

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

<script>
  // add nav-link active class to id = circle
  $('#circle a').addClass('active');
  $('#circle a').addClass('nav-link');
</script>

<script>
// upon form submit, call /add-circle API and add circle
$(document).ready(function() {
  $('#circleForm').submit(function(e) {
    e.preventDefault();
    var circle_name = $('#circle_name').val();
    
    if (!circle_name) {
      showToast('Please enter circle name', false);
      return;
    }
    
    $.ajax({
      url: '<?php echo API_URL; ?>add-circle',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({circle_name: circle_name, access_token: "<?php echo $_SESSION['access_token']; ?>"}),
      success: function(response) {
        // console.log('Add circle response:', response);
        
        if (response.is_successful === "1") {
          showToast(response.success_message || 'Circle added successfully');
          window.location.href = '<?php echo BASE_URL; ?>circle-list';
        } else {
          // Default error message
          let errorMsg = 'Failed to add circle';
          
          // For duplicate circle error
          if (response.errors && response.errors.circle_name) {
            errorMsg = 'Circle with this name already exists';
          }
          
          showToast(errorMsg, false);
        }
      },
      error: function(response) {
        // console.error('Add circle error:', response);
        let errorMsg = 'Circle with this name already exists';
        
        // For duplicate circle error
        if (response.responseJSON && response.responseJSON.errors && response.responseJSON.errors.circle_name) {
          errorMsg = 'Circle with this name already exists';
        }
        
        showToast(errorMsg, false);
      }
    });
  });
});
</script>

<?php include 'common/footer.php'; ?>