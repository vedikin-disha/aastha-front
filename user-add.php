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
    <h3 class="card-title">Add User</h3>
  </div>
  <div class="card-body">
  <form method="post" id="userForm" onsubmit="return handleSubmit(event)">
    
      <div id="alertMessage" class="alert" style="display: none;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <span id="alertText"></span>
      </div>

      <!-- Full Name -->
      <div class="form-group">
        <label for="id_emp_name">Full Name <span class="text-danger">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
          </div>
          <input type="text" name="emp_name" id="id_emp_name" class="form-control"
                 value="" required>
        </div>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label for="id_emp_email_id">Email <span class="text-danger">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          </div>
          <input type="email" name="emp_email_id" id="id_emp_email_id" class="form-control" value="" required>
        </div>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="id_emp_password">Password <span class="text-danger">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
          </div>
          <input type="password" name="emp_password" id="id_emp_password" class="form-control" required>
        </div>
      </div>

      <!-- Phone Number -->
      <div class="form-group">
        <label for="id_emp_phone_number">Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-phone"></i></span>
          </div>
          <input type="text" name="emp_phone_number" id="id_emp_phone_number" class="form-control" value="" required>
        </div>
      </div>

      <!-- WhatsApp Number -->
      <div class="form-group">
        <label for="id_emp_whatsapp_number">WhatsApp Number</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
          </div>
          <input type="text" name="emp_whatsapp_number" id="id_emp_whatsapp_number" class="form-control" value="">
        </div>
      </div>

      
      <!-- Role -->
      <div class="form-group mb-3">
        <label for="id_emp_role_id" class="form-label">Role <span class="text-danger">*</span></label>
        <select name="emp_role_id" id="id_emp_role_id" class="form-control" required>
          <option value="">-- Select Role --</option>
        
            <option value=""></option>
        
        </select>
      </div>

      <!-- Department -->
      <div class="form-group mb-3">
        <label for="id_dept_id" class="form-label">Department <span class="text-danger">*</span></label>
        <select name="dept_id" id="id_dept_id" class="form-control" required>
          <option value="">-- Select Department --</option>
        
            <option value=""></option>
        
        </select>
      </div>

      <!-- Status -->
      <div class="form-group mb-3">
        <label for="id_emp_status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="emp_status" id="id_emp_status" class="form-control" required>
          <option value="">-- Select Status --</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>



      <!-- Submit Buttons -->
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="user-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

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
    $('#id_emp_role_id, #id_dept_id, #id_emp_status').select2({
      theme: 'bootstrap-5',
      width: '100%',
      dropdownParent: $('#userForm')
    });

    // Add active class to navigation
    $('#user a').addClass('active nav-link');

    // Fetch roles and departments
    fetchRoles();
    fetchDepartments();
  });

  async function fetchRoles() {
    try {
      const response = await fetch('<?php echo API_URL; ?>role', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          access_token: '<?php echo $_SESSION["access_token"]; ?>'
        })
      });

      const data = await response.json();
      
      if (response.ok) {
        const roleSelect = document.getElementById('id_emp_role_id');
        roleSelect.innerHTML = '<option value="">-- Select Role --</option>';
        
        data.data.forEach(role => {
          roleSelect.innerHTML += `<option value="${role.emp_role_id}">${role.emp_role_name}</option>`;
        });
      } else {
        showAlert('Failed to fetch roles', 'danger');
      }
    } catch (error) {
      showAlert('An error occurred while fetching roles', 'danger');
    }
  }

  async function fetchDepartments() {
    try {
      const response = await fetch('<?php echo API_URL; ?>department', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          access_token: '<?php echo $_SESSION["access_token"]; ?>'
        })
      });

      const data = await response.json();
      
      if (data.is_successful === "1") {
        const deptSelect = document.getElementById('id_dept_id');
        deptSelect.innerHTML = '<option value="">-- Select Department --</option>';
        
        data.data.forEach(dept => {
          deptSelect.innerHTML += `<option value="${dept.dept_id}">${dept.dept_name}</option>`;
        });
      } else {
        showAlert('Failed to fetch departments', 'danger');
      }
    } catch (error) {
      showAlert('An error occurred while fetching departments', 'danger');
    }
  }

  function showAlert(message, type) {
    const alertDiv = document.getElementById('alertMessage');
    const alertText = document.getElementById('alertText');
    alertDiv.className = `alert alert-${type}`;
    alertText.textContent = message;
    alertDiv.style.display = 'block';
  }

  async function handleSubmit(event) {
    event.preventDefault();
    
    const formData = {
      access_token: '<?php echo $_SESSION["access_token"]; ?>',
      full_name: document.getElementById('id_emp_name').value,
      email: document.getElementById('id_emp_email_id').value,
      password: document.getElementById('id_emp_password').value,
      phone: document.getElementById('id_emp_phone_number').value,
      w_phone: document.getElementById('id_emp_whatsapp_number').value || document.getElementById('id_emp_phone_number').value,
      role: parseInt(document.getElementById('id_emp_role_id').value),
      department: parseInt(document.getElementById('id_dept_id').value)
    };

    try {
      const response = await fetch('<?php echo API_URL; ?>add-user', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
      });

      const data = await response.json();
      
      if (data.is_successful === "1") {
        showAlert(data.success_message, 'success');
        setTimeout(() => {
          window.location.href = 'user-list';
        }, 2000);
      } else {
        showAlert(data.errors || 'Failed to add user', 'danger');
      }
    } catch (error) {
      showAlert('An error occurred while adding the user. Please try again.', 'danger');
    }

    return false;
  }
</script>

<?php include 'common/footer.php'; ?>
