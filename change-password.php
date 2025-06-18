<?php 
include 'common/header.php';
if (!defined('API_URL')) {
    include 'config/constant.php';
}

$access_token = '';
if (isset($_SESSION['access_token'])) {
    $access_token = $_SESSION['access_token'];
}

$emp_id = '';
if (isset($_SESSION['emp_id'])) {
    $emp_id = $_SESSION['emp_id'];
}
?>

<script>
  const API_URL = '<?php echo API_URL; ?>';
</script>

<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Change Password</h3>
  </div>
  <div class="card-body">
    <form id="changePasswordForm" novalidate>
      <div class="form-group row mb-3">
        <label class="col-sm-3 col-form-label">Current Password</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="currentPassword">
        </div>
      </div>
      <div class="form-group row mb-3">
        <label class="col-sm-3 col-form-label">New Password</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="newPassword">
        </div>
      </div>
      <div class="form-group row mb-3">
        <label class="col-sm-3 col-form-label">Confirm Password</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="confirmPassword">
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border:none;">Update Password</button>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Handle form submission
    $('#changePasswordForm').on('submit', function(e) {
      e.preventDefault();
      
      const currentPassword = $('#currentPassword').val().trim();
      const newPassword = $('#newPassword').val().trim();
      const confirmPassword = $('#confirmPassword').val().trim();

      // Validate fields
      if (!currentPassword) {
        showToast('error', 'Error', 'Please enter current password');
        return;
      }
      
      if (!newPassword) {
        showToast('error', 'Error', 'Please enter new password');
        return;
      }
      
      if (!confirmPassword) {
        showToast('error', 'Error', 'Please confirm your new password');
        return;
      }

      if (newPassword !== confirmPassword) {
        showToast('error', 'Error', 'New password and confirm password do not match');
        return;
      }

      $.ajax({
        url: API_URL + 'reset-password',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          access_token: '<?php echo $access_token; ?>',
          emp_id: <?php echo $emp_id; ?>,
          current_password: currentPassword,
          new_password: newPassword,
          confirm_new_password: confirmPassword
        }),
        success: function(response) {
          if (response.is_successful === '1') {
            showToast('success', 'Success', response.success_message || 'Password updated successfully');
            $('#changePasswordForm')[0].reset();
          } else {
            // Check if the error is due to incorrect current password
            const errorMessage = (response.errors && response.errors.current_password) ? 
              'Current password is incorrect' : 
              (response.errors || 'Failed to update password');
            showToast('error', 'Error', errorMessage);
          }
        },
        error: function(xhr, status, error) {
          showToast('error', 'Error', 'Failed to update password. Please try again.');
        }
      });
    });

    function showToast(type, title, message) {
      $(document).Toasts('create', {
        class: type === 'error' ? 'bg-danger' : 'bg-success',
        title: title,
        position: 'bottomRight',
        body: message,
        autohide: true,
        delay: 3000
      });
    }
  });
</script>
<?php if (isset($messages)): ?>
<script>
  $(document).ready(function() {
    // {% for message in messages %}
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Success',
        position: 'bottomRight',
        body: '<?php echo htmlspecialchars($message); ?>',
        autohide: true,
        delay: 3000
      });
    // {% endfor %}
  });
</script>
<?php endif; ?>

<?php include 'common/footer.php'; ?> 