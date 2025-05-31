<?php include 'common/header.php'; ?>
<style>
  .input-group {
    margin-bottom: 1rem !important;
  }
  .form-control {
    height: 38px;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
</style>

<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Edit User</h3>
  </div>
  <div class="card-body">
  <form method="post" id="editUserForm">
    <input type="hidden" id="emp_id" name="emp_id">
 

      <!-- Email -->
      <div class="form-group">
        <label for="emp_email_id">Email ID <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="emp_email_id" id="emp_email_id" class="form-control" readonly>
        </div>
        <div class="text-danger"></div>
      </div>

      <!-- Name -->
      <div class="form-group">
        <label for="emp_name">Full Name <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="full_name" id="full_name" class="form-control" required>
        </div>
        
        <div class="text-danger"></div>
      </div>

      <!-- Phone Number -->
      <div class="form-group">
        <label for="emp_phone_number">Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" name="phone" id="phone" class="form-control" pattern="[0-9]{10}" title="Please enter 10 digit phone number" required>
        </div>
        
        <div class="text-danger"></div>
      </div>

      <!-- WhatsApp Number -->
      <div class="form-group">
        <label for="w_phone">WhatsApp Number</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
          <input type="text" name="w_phone" id="w_phone" class="form-control" pattern="[0-9]{10}" title="Please enter 10 digit WhatsApp number">
        </div>
        <div class="text-danger"></div>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" minlength="8">
        </div>
        <small class="form-text text-muted">Leave empty if you don't want to change the password</small>
        <div class="text-danger"></div>
      </div>

      <!-- Role -->
      <div class="form-group mb-3">
        <label for="emp_role_id" class="form-label">Role <span class="text-danger">*</span></label>
        <select name="role" id="role" class="form-control" required>
          <option value="">-- Select Role --</option>
          <option value="1">Admin</option>
          <option value="2">Manager</option>
          <option value="3">Employee</option>
        </select>
        <div class="text-danger"></div>
      </div>

      <!-- Department -->
      <div class="form-group mb-3">
        <label for="dept_id" class="form-label">Department <span class="text-danger">*</span></label>
        <select name="department" id="department" class="form-control" required>
          <option value="">-- Select Department --</option>
          <option value="1">IT</option>
          <option value="2">HR</option>
          <option value="3">Finance</option>
          <option value="4">Marketing</option>
        </select>
        <div class="text-danger"></div>
      </div>

      <!-- Status -->
      <div class="form-group mb-3">
        <label for="emp_status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-control" required>
          <option value="">-- Select Status --</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
        <div class="text-danger"></div>
      </div>

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="user-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="css/all.min.css">

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap 5 Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="js/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
  $(document).ready(function() {
    // Initialize Select2 for Role, Department, and Status
    $('#role, #department, #status').select2({
      theme: 'bootstrap-5',
      width: '100%',
      dropdownParent: $('#editUserForm')
    });

    // Add active class to navigation
    $('#user a').addClass('active nav-link');

    // Get user ID from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');
    if (!userId) {
      window.location.href = 'user-list.php';
      return;
    }

    // Populate form with user data
    $.ajax({
      url: '<?php echo API_URL; ?>user',
      method: 'POST',
      headers: {
        'Authorization': 'Bearer <?php echo $_SESSION["access_token"]; ?>'
      },
      data: JSON.stringify({
        access_token: '<?php echo $_SESSION["access_token"]; ?>',
        emp_id: userId
      }),
      contentType: 'application/json',
      success: function(response) {
        if (response.is_successful === '1' && response.data) {
          const user = response.data;
          $('#emp_id').val(user.emp_id);
          $('#emp_email_id').val(user.emp_email_id);
          $('#full_name').val(user.emp_name);
          $('#phone').val(user.emp_phone_number);
          $('#w_phone').val(user.emp_whatsapp_number);
          $('#role').val(user.emp_role_id).trigger('change');
          $('#department').val(user.dept_id).trigger('change');
          $('#status').val(user.emp_status).trigger('change');
        } else {
          window.location.href = 'user-list.php';
        }
      },
      error: function(xhr) {
        window.location.href = 'user-list.php';
      }
    });


    // Handle form submission
    $('#editUserForm').on('submit', function(e) {
      e.preventDefault();
      
      const formData = {
        access_token: "<?php echo $_SESSION['access_token']; ?>",
        emp_id: $('#emp_id').val(),
        full_name: $('#full_name').val(),
        phone: $('#phone').val(),
        w_phone: $('#w_phone').val() || undefined,
        role: parseInt($('#role').val()),
        department: parseInt($('#department').val()),
        status: parseInt($('#status').val())
      };

      // Add password only if it's provided
      const password = $('#password').val();
      if (password) {
        formData.password = password;
      }

      $.ajax({
        url: '<?php echo API_URL; ?>update-user',
        method: 'POST',
        headers: {
          'Authorization': 'Bearer <?php echo $_SESSION["access_token"]; ?>'
        },
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function(response) {
          if (response.is_successful === '1') {
            // Show success message
            const successDiv = $('<div>')
              .addClass('alert alert-success')
              .text('User updated successfully!')
              .insertBefore('#editUserForm');
            
            // Redirect after showing message
            setTimeout(function() {
              window.location.href = 'user-list.php';
            }, 1500);
          }
        },
        error: function(xhr) {
          alert('Error updating user. Please try again.');
        }
      });
    });
  });
</script>
<?php include 'common/footer.php'; ?>
