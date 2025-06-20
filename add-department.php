<?php include 'common/header.php'; ?>

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Add Department</h3>
  </div>
  <div class="card-body">
  <form method="post" id="departmentForm">  
      
      <div class="form-group mb-3">
        <label for="dept_name" class="form-label">Department Name</label>
        <div class="input-group">
          <input type="text" name="dept_name" id="dept_name" class="form-control" required>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border: 1px solid #30b8b9;">Save</button>
        <a href="department-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include 'common/footer.php'; ?>

<script>
$(document).ready(function() {
  // Add active class to department menu
  $('#department a').addClass('active');
  
  // Handle form submission
  $('#departmentForm').on('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    // Prepare form data
    const formData = {
      access_token: '<?php echo $_SESSION['access_token']; ?>',
      dept_name: $('#dept_name').val().trim()
    };
    
    // Make API request
    $.ajax({
      url: '<?php echo API_URL; ?>add-department',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.is_successful === "1") {
          showToast('Department added successfully!', true);
          // Redirect to department list after a short delay
          setTimeout(function() {
            window.location.href = 'department-list.php';
          }, 1500);
        } else {
          const errorMsg = response.errors || 'Failed to add department. Please try again.';
          showToast(errorMsg, false);
          submitBtn.prop('disabled', false).html(originalBtnText);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error adding department:', error);
        showToast('An error occurred while adding the department. Please try again.', false);
        submitBtn.prop('disabled', false).html(originalBtnText);
      }
    });
  });
});
</script>