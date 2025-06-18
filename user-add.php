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

  <div class="card-header" style="background-color:transparent !important; border-bottom: 1px solid rgba(0, 0, 0, .125); border-top: 3px solid #30b8b9 !important; color: #212529 !important;">

    <h3 class="card-title">Add User</h3>

  </div>

  <div class="card-body">

  <form method="post" id="userForm" onsubmit="return handleSubmit(event)" novalidate>

    

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

          <input type="text" name="emp_name" id="id_emp_name" class="form-control" value="">

        </div>

      </div>



      <!-- Email -->

      <div class="form-group">

        <label for="id_emp_email_id">Email <span class="text-danger">*</span></label>

        <div class="input-group">

          <div class="input-group-prepend">

            <span class="input-group-text"><i class="fas fa-envelope"></i></span>

          </div>

          <input type="email" name="emp_email_id" id="id_emp_email_id" class="form-control" value="">

        </div>

      </div>



      <!-- Password -->

      <div class="form-group">

        <label for="id_emp_password">Password <span class="text-danger">*</span></label>

        <div class="input-group">

          <div class="input-group-prepend">

            <span class="input-group-text"><i class="fas fa-lock"></i></span>

          </div>

          <input type="password" name="emp_password" id="id_emp_password" class="form-control">

        </div>

      </div>



      <!-- Phone Number -->

      <div class="form-group">

        <label for="id_emp_phone_number">Phone Number <span class="text-danger">*</span></label>

        <div class="input-group">

          <div class="input-group-prepend">

            <span class="input-group-text"><i class="fas fa-phone"></i></span>

          </div>

          <input type="text" name="emp_phone_number" id="id_emp_phone_number" class="form-control" value="">

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

        <select name="emp_role_id" id="id_emp_role_id" class="form-control">

          <option value="">-- Select Role --</option>

        

            <option value=""></option>

        

        </select>

      </div>



      <!-- Department -->

      <div class="form-group mb-3">

        <label for="id_dept_id" class="form-label">Department <span class="text-danger">*</span></label>

        <select name="dept_id" id="id_dept_id" class="form-control">

          <option value="">-- Select Department --</option>

        

            <option value=""></option>

        

        </select>

      </div>



      <!-- Status -->

      <div class="form-group mb-3">

        <label for="id_emp_status" class="form-label">Status <span class="text-danger">*</span></label>

        <select name="emp_status" id="id_emp_status" class="form-control">

          <option value="">-- Select Status --</option>

          <option value="1">Active</option>

          <option value="0">Inactive</option>

        </select>

      </div>







      <!-- Submit Buttons -->

      <div class="card-footer p-0" style="background-color: #fff !important;">

        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9 !important;border: 1px solid #30b8b9;">Save</button>

        <a href="user-list" class="btn btn-secondary">Cancel</a>

      </div>

    </form>

  </div>

</div>



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

        showToast('Failed to fetch roles', false);

      }

    } catch (error) {

      showToast('An error occurred while fetching roles', false);

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

        showToast('Failed to fetch departments', false);

      }

    } catch (error) {

      showToast('An error occurred while fetching departments', false);

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

    // Get form values

    const fullName = document.getElementById('id_emp_name').value;

    const email = document.getElementById('id_emp_email_id').value;

    const password = document.getElementById('id_emp_password').value;

    const phone = document.getElementById('id_emp_phone_number').value;

    const whatsapp = document.getElementById('id_emp_whatsapp_number').value;

    const role = document.getElementById('id_emp_role_id').value;

    const department = document.getElementById('id_dept_id').value;

    const status = document.getElementById('id_emp_status').value;

    

    // Validate required fields

    if (!fullName) {

      showToast('Please enter Full Name', false);

      return false;

    }

    

    if (!email) {

      showToast('Please enter Email', false);

      return false;

    }

    

    // Validate email format

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {

      showToast('Please enter a valid Email address', false);

      return false;

    }

    

    if (!password) {

      showToast('Please enter Password', false);

      return false;

    }

    

    if (!phone) {

      showToast('Please enter Phone Number', false);

      return false;

    }

    

    // Phone number validation removed as requested

    // WhatsApp number validation removed as requested

    

    if (!role) {

      showToast('Please select a Role', false);

      return false;

    }

    

    if (!department) {

      showToast('Please select a Department', false);

      return false;

    }

    

    if (!status) {

      showToast('Please select a Status', false);

      return false;

    }

    

    const formData = {

      access_token: '<?php echo $_SESSION["access_token"]; ?>',

      full_name: fullName,

      email: email,

      password: password,

      phone: phone,

      w_phone: whatsapp || phone,

      role: parseInt(role),

      department: parseInt(department),

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

        showToast(data.success_message || 'User added successfully!');

        setTimeout(() => {

          window.location.href = 'user-list';

        }, 2000);

      } else {

        showToast(data.errors || 'Failed to add user', false);

      }

    } catch (error) {

      showToast('An error occurred while adding the user. Please try again.', false);

    }



    return false;

  }

</script>



<?php include 'common/footer.php'; ?>

