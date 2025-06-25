<?php 
include 'common/header.php';

// Get and decode the department ID from URL parameter
$encoded_id = isset($_GET['id']) ? $_GET['id'] : '';
$dept_id = '';

if ($encoded_id) {
    $dept_id = base64_decode($encoded_id);
    if (!$dept_id || !is_numeric($dept_id)) {
        echo "<script>showToast('Invalid department ID', false); setTimeout(() => { window.location.href = 'department-list.php'; }, 2000);</script>";
        exit();
    }
} else {
    echo "<script>showToast('No department ID provided', false); setTimeout(() => { window.location.href = 'department-list.php'; }, 2000);</script>";
    exit();
}
?>

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Edit Department</h3>
  </div>
  <div class="card-body">
    <form id="editDepartmentForm">
      <input type="hidden" id="dept_id" name="dept_id" value="<?php echo $dept_id; ?>">
      
      <div class="form-group mb-3">
        <label for="dept_name" class="form-label">Department Name</label>
        <div class="input-group">
          <input type="text" name="dept_name" id="dept_name" class="form-control" required>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-building"></i></span>
          </div>
        </div>
      </div>
      
      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9; border: 1px solid #30b8b9;">
          <i class="fas fa-save"></i> Update
        </button>
        <a href="department-list.php" class="btn btn-secondary">
          <i class="fas fa-times"></i> Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<?php include 'common/footer.php'; ?>

<script>
$(document).ready(function() {
  // Add active class to department menu
  $('#department a').addClass('active');
  
  // Function to load department data
  function loadDepartment() {
    const deptId = $('#dept_id').val();
    
    // Show loading state
    $('#dept_name').prop('disabled', true).attr('placeholder', 'Loading department details...');
    
    // Make API request to get department details
    $.ajax({
      url: '<?php echo API_URL; ?>department',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        dept_id: deptId,
        access_token: '<?php echo $_SESSION['access_token']; ?>'
      }),
      success: function(response) {
        console.log('Department API Response:', response);
        if (response.is_successful === "1" && response.data) {
          const dept = response.data;
          $('#dept_name').val(dept.dept_name).prop('disabled', false);
        } else {
          const errorMsg = response.message || response.errors || 'Failed to load department data';
          console.error('Load department failed:', errorMsg);
          showToast(errorMsg, false);
          setTimeout(() => window.location.href = 'department-list.php', 1500);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading department:', {
          status: xhr.status,
          statusText: xhr.statusText,
          responseText: xhr.responseText,
          error: error
        });
        showToast('An error occurred while loading department data', false);
        setTimeout(() => window.location.href = 'department-list.php', 1500);
      }
    });
  }
  
  // Handle form submission
  $('#editDepartmentForm').on('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
    
    // Prepare form data according to API requirements
    const formData = {
      access_token: '<?php echo $_SESSION['access_token']; ?>',
      dept_id: $('#dept_id').val(),
      dept_name: $('#dept_name').val().trim()
    };
    
    // Log the data being sent for debugging
    console.log('Sending update request:', formData);
    
    // Make API request to update department
    $.ajax({
      url: '<?php echo API_URL; ?>update-department',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        console.log('Update API Response:', response);
        if (response.is_successful === "1") {
          showToast('Department updated successfully!', true);
          setTimeout(() => window.location.href = 'department-list.php', 1500);
        } else {
          const errorMsg = response.message || response.errors || 'Failed to update department. Please try again.';
          console.error('Update failed:', errorMsg);
          showToast(errorMsg, false);
          submitBtn.prop('disabled', false).html(originalBtnText);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error updating department:', {
          status: xhr.status,
          statusText: xhr.statusText,
          responseText: xhr.responseText,
          error: error
        });
        showToast('An error occurred while updating the department. Please check console for details.', false);
        submitBtn.prop('disabled', false).html(originalBtnText);
      }
    });
  });
  
  // Load department data when page loads
  loadDepartment();
});
</script>
