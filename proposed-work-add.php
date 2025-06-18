<?php include 'common/header.php'; ?>

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Add Proposed Work</h3>
  </div>
  <div class="card-body">
    <form method="post" id="proposedWorkForm">
      <div class="form-group mb-3">
        <label for="work_name" class="form-label">Proposed Work Name</label>
        <div class="input-group">
          <input type="text" name="work_name" id="work_name" class="form-control" required>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9; border: 1px solid #30b8b9;">Save</button>
        <a href="proposed-work-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<style>
  .form-control {
    height: 38px;
  }
  textarea.form-control {
    height: auto;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
</style>

<script>
  // Add active class to navigation
  $('#proposedWorkNav a').addClass('active');
  $('#proposedWorkNav a').addClass('nav-link');
  
  // Form submission handler
  $(document).ready(function() {
    $('#proposedWorkForm').submit(function(e) {
      e.preventDefault();
      var work_name = $('#work_name').val();
      
      if (!work_name) {
        showToast('Please enter proposed work name', false);
        return;
      }
      
      $.ajax({
        url: '<?php echo API_URL; ?>proposed-work-add',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          proposed_work_name: work_name, 
          access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
          if (response.is_successful === "1") {
            showToast(response.success_message || 'Proposed work added successfully');
            window.location.href = '<?php echo BASE_URL; ?>proposed-work-list';
          } else {
            // Default error message
            let errorMsg = 'Failed to add proposed work';
            
            // For duplicate work error
            if (response.errors && response.errors.proposed_work_name) {
              errorMsg = 'Proposed work with this name already exists';
            }
            
            showToast(errorMsg, false);
          }
        },
        error: function(xhr) {
          let errorMsg = 'Failed to add proposed work';
          
          // For duplicate work error
          if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.proposed_work_name) {
            errorMsg = 'Proposed work with this name already exists';
          }
          
          showToast(errorMsg, false);
        }
      });
    });
  });
</script>

<?php include 'common/footer.php'; ?>
