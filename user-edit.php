<?php include 'common/header.php'; ?>
<style>
  .input-group {
    /* margin-bottom: 1rem !important; */
  }
  .form-control {
    height: 38px;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }

  /* Custom Select2 highlight and selected color */
.select2-container--bootstrap-5 .select2-results__option--highlighted {
  background-color:rgb(236, 236, 236) !important;
}
.select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable,
.select2-container--bootstrap-5 .select2-results__option--selected {
  background-color: #30b8b9 !important;
  color: #fff !important;
}

/* Style for selected item in the dropdown */
.select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
  background-color: #30b8b9 !important;
  color: #fff !important;
}
</style>

<div class="card card-primary">
  <div class="card-header" style="background-color:transparent !important;border-bottom: 1px solid rgba(0, 0, 0, .125); border-top: 3px solid #30b8b9 !important; color: #212529 !important;">
    <h3 class="card-title">Edit User</h3>
  </div>
  <div class="card-body">
  <form method="post" id="editUserForm" novalidate>
    <input type="hidden" id="emp_id" name="emp_id">
 

      <!-- Email -->
      <div class="form-group">
        <label for="emp_email_id">Email ID <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="emp_email_id" id="emp_email_id" class="form-control" readonly novalidate>
        </div>
        <div class="text-danger"></div>
      </div>

      <!-- Name -->
      <div class="form-group">
        <label for="emp_name">Full Name <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="full_name" id="full_name" class="form-control">
        </div>
        
        <div class="text-danger"></div>
      </div>

      <!-- Phone Number -->
      <div class="form-group">
        <label for="emp_phone_number">Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" name="phone" id="phone" class="form-control">
        </div>
        
        <div class="text-danger"></div>
      </div>

      <!-- WhatsApp Number -->
      <div class="form-group">
        <label for="w_phone">WhatsApp Number</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
          <input type="text" name="w_phone" id="w_phone" class="form-control">
        </div>
        <div class="text-danger"></div>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control">
        </div>
        <small class="form-text text-muted">Leave empty if you don't want to change the password</small>
        <div class="text-danger"></div>
      </div>

      <!-- Role -->
      <div class="form-group mb-3">
        <label for="emp_role_id" class="form-label">Role <span class="text-danger">*</span></label>
        <select name="role" id="emp_role_id" class="form-control">
          <option value="">-- Select Role --</option>  
        </select>
        <div class="text-danger"></div>
      </div>

      <!-- Department -->
      <div class="form-group mb-3">
        <label for="id_department" class="form-label">Department <span class="text-danger">*</span></label>
        <div class="input-group">
          <select name="department" id="id_department" class="form-control" required>
            <option value="">-- Select Department --</option>
          </select>
        </div>
        <div class="invalid-feedback" style="display: none;"></div>
      </div>

      <!-- Status -->
      <div class="form-group mb-3">
        <label for="emp_status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-control">
          <option value="">-- Select Status --</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
        <div class="text-danger"></div>
      </div>

      <div class="card-footer p-0" style="background-color: #fff !important;">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9 !important;border: 1px solid #30b8b9;">Update</button>
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
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- jQuery -->
<!-- <script src="js/jquery-3.6.0.min.js"></script> -->
<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<!-- Common JS with showToast function -->
<script src="js/common.js"></script>

<script>
  $(document).ready(function() {
    // Clear password field on page load
    $('#password').val('');
    
    // Initialize Select2 for Role, Department, and Status
    $('#emp_role_id, #id_department, #status').select2({
      theme: 'bootstrap-5',
      width: '100%',
      dropdownParent: $('#editUserForm')
    });

    // Load departments from API
    function loadDepartments(selectedDeptId = '') {
      $.ajax({
        url: '<?php echo API_URL; ?>department',
        type: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer <?php echo $_SESSION["access_token"]; ?>'
        },
        data: JSON.stringify({
          access_token: '<?php echo $_SESSION["access_token"]; ?>'
        }),
        success: function(response) {
          if (response.is_successful === '1' && response.data) {
            const deptSelect = $('#id_department');
            deptSelect.empty().append('<option value="">-- Select Department --</option>');
            
            response.data.forEach(function(dept) {
              deptSelect.append(new Option(dept.dept_name, dept.dept_id));
            });
            
            // Set the selected department if provided
            if (selectedDeptId) {
              deptSelect.val(selectedDeptId).trigger('change');
            }
          } else {
            showToast('Failed to load departments. ' + (response.errors || ''), false);
          }
        },
        error: function(xhr, status, error) {
          showToast('Failed to load departments. Please try again.', false);
        }
      });
    }
    // Load roles when page loads
    loadRoles();

    function loadRoles(selectedRoleId = '') {
      $.ajax({
        url: '<?php echo API_URL; ?>role',
        type: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer <?php echo $_SESSION["access_token"]; ?>'
        },
        data: JSON.stringify({
          access_token: '<?php echo $_SESSION["access_token"]; ?>'
        }),
        success: function(response) {
          if (response.is_successful === '1' && response.data) {
            const roleSelect = $('#emp_role_id');
            roleSelect.empty().append('<option value="">-- Select Role --</option>');
            
            response.data.forEach(function(role) {
              roleSelect.append(new Option(role.emp_role_name, role.emp_role_id));
            });
            
            // Set the selected department if provided
            if (selectedRoleId) {
              roleSelect.val(selectedRoleId).trigger('change');
            }
          } else {
            showToast('Failed to load departments. ' + (response.errors || ''), false);
          }
        },
        error: function(xhr, status, error) {
          showToast('Failed to load departments. Please try again.', false);
        }
      });
    }

    // Add active class to navigation
    $('#user a').addClass('active nav-link');

    // Get and decode user ID from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const encodedId = urlParams.get('id');
    
    if (!encodedId) {
      showToast('Invalid user ID', false);
      setTimeout(() => { window.location.href = 'user-list'; }, 2000);
      return;
    }
    
    // Decode the base64 ID
    let userId;
    try {
      userId = atob(encodedId);
      if (!userId) throw new Error('Invalid ID');
    } catch (error) {
      showToast('Invalid user ID format', false);
      setTimeout(() => { window.location.href = 'user-list'; }, 2000);
      return;
    }

    // Load departments first
    loadDepartments();
    
    // Load roles first
    loadRoles();

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
          $('#emp_role_id').val(user.emp_role_id).trigger('change');
          
          // Set department after a short delay to ensure it's loaded
          setTimeout(() => {
            loadDepartments(user.dept_id);
            // Set department after loading
          }, 100);
          
          $('#status').val(user.emp_status).trigger('change');
        } else {
          window.location.href = 'user-list';
        }
      },
      error: function(xhr) {
        window.location.href = 'user-list';
      }
    });

    // Handle form submission
    $('#editUserForm').on('submit', function(e) {
      e.preventDefault();
      
      // Validate required fields
      const fullName = $('#full_name').val();
      const phone = $('#phone').val();
      const whatsapp = $('#w_phone').val();
      const role = $('#emp_role_id').val();
      const department = $('#id_department').val();
      const status = $('#status').val();
      
      // Check for empty required fields
      if (!fullName) {
        showToast('Please enter Full Name', false);
        return;
      }
      
      if (!phone) {
        showToast('Please enter Phone Number', false);
        return;
      }
      
      if (!role) {
        showToast('Please select a Role', false);
        return;
      }
      
      if (!department) {
        showToast('Please select a Department', false);
        return;
      }
      
      if (!status) {
        showToast('Please select a Status', false);
        return;
      }
      if (!whatsapp) {
        showToast('Please enter WhatsApp Number', false);
        return;
      }
      
      const formData = {
        access_token: "<?php echo $_SESSION['access_token']; ?>",
        emp_id: $('#emp_id').val(),
        full_name: fullName,
        phone: phone,
        w_phone: $('#w_phone').val() || undefined,
        role: parseInt(role),
        department: parseInt(department),
        status: parseInt(status)
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
            // Show success message using showToast
            showToast(response.success_message , true);
            
            // Redirect after showing message
            setTimeout(function() {
              window.location.href = 'user-list';
            }, 1500);
          } else {
            // Show error message if API returns unsuccessful
            const errorMessage = response.errors.error || 'Failed to update user';
            showToast(errorMessage, false);
            
            // If there's a specific field error, show it next to the field
            if (response.errors.field) {
              const fieldError = response.errors.error;
              $(`#${response.errors.field}_error`).text(fieldError).show();
            }
          }
        },
        error: function(xhr, status, error) {
          try {
            // Try to parse the error response
            const response = xhr.responseJSON || {};
            if (xhr.status === 400 && response.errors) {
              // For 400 errors with error details
              const errorMessage = response.errors.error || 'Validation error';
              showToast(errorMessage, false);
              
              // If there's a specific field error, show it next to the field
              if (response.errors.field) {
                $(`#${response.errors.field}_error`).text(errorMessage).show();
              }
            } else {
              // For other types of errors
              showToast('Error updating user. Please try again.', false);
            }
          } catch (e) {
            // If error parsing fails, show generic error
            showToast('Error updating user. Please try again.', false);
          }
        }
      });
    });
  });
</script>
<?php include 'common/footer.php'; ?>
