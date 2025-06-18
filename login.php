<?php
// Start the session at the beginning before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'config/constant.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Aastha PMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <style>
    .login-error {
      color: #dc3545;
      background-color: #f8d7da;
      border-color: #f5c6cb;
      position: relative;
      padding: 0.75rem 1.25rem;
      margin-bottom: 1rem;
      border: 1px solid transparent;
      border-radius: 0.25rem;
      display: none;
    }
    
    /* Button loading state */
    .btn-loading {
      position: relative;
      pointer-events: none;
      color: transparent !important;
    }
    
    .btn-loading:after {
      content: '';
      position: absolute;
      width: 1.5rem;
      height: 1.5rem;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      border: 3px solid #fff;
      border-top-color: transparent;
      border-radius: 50%;
      animation: button-loading-spinner 0.75s linear infinite;
    }
    
    @keyframes button-loading-spinner {
      from { transform: rotate(0turn); }
      to { transform: rotate(1turn); }
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box text-center">
  <img src="assets/logo.png" alt="Logo" class="img-fluid" style="width: 30%;margin-bottom: 5%;">
  <div class="card card-outline card-primary" style="    border-top: 3px solid #30b8b9;">
    <div class="card-header text-center">
      
      <h2 href="" class="h1"><b>Aastha</b>PMS</h2>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <div class="login-error" id="login-error"></div>
      <form method="post">
        <div class="input-group mb-3">
          <input type="text" name="emp_email_id" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="emp_pwd" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" id="loginButton" class="btn btn-primary btn-block btn-lg" style="background-color: #30b8b9;border-color: #30b8b9;">
            <span class="btn-text">Sign In</span>
          </button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function () {
    $('form').on('submit', function (e) {
      e.preventDefault();
      
      // Disable button and show loading state
      const $loginBtn = $('#loginButton');
      $loginBtn.addClass('btn-loading').prop('disabled', true);

      const email = $('input[name="emp_email_id"]').val();
      const password = $('input[name="emp_pwd"]').val();

      $.ajax({
        url: "<?php echo API_URL; ?>login",
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          email: email,
          password: password
        }),
        success: function (response) {
          try {
            if (response.is_successful === "1") {
              const data = {
                  access_token: response.data.access_token,
                  emp_id: response.data.emp_id,
                  emp_name: response.data.emp_name,
                  emp_role_id: response.data.emp_role_id,
                  dept_id: response.data.dept_id,
                  whatsapp_number: response.data.whatsapp_number,
                  dept_name: response.data.dept_name,
                  emp_role_name: response.data.emp_role_name
              };

              // Send via fetch or AJAX to a PHP file
              fetch('store-session.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify(data)
              })
              .then(res => res.json())
              .then(response => {
                  console.log('Session stored:', response);
              })
              .catch(error => console.error('Error:', error));

              // Show success toast
              $(document).Toasts('create', {
                class: 'bg-success',
                position: 'bottomRight',
                title: 'Login Successful',
                body: `Welcome, ${response.data.emp_name}`,
                autohide: true,
                delay: 3000
              });

              // Reset button state before redirect
              $loginBtn.removeClass('btn-loading').prop('disabled', false).find('.btn-text').text('Sign In');
              
              // Redirect after short delay
              setTimeout(() => {
                const roleId = parseInt(response.data.emp_role_id);
                if (roleId === 1 || roleId === 2) {
                  window.location.href = 'dashboard-admin';
                } else if (roleId === 3 || roleId === 4) {
                  window.location.href = 'dashboard-user';
                }
              }, 500);
            } else {
              // Reset button state on failed login
              $loginBtn.removeClass('btn-loading').prop('disabled', false).find('.btn-text').text('Sign In');
              
              // Display error message in the form
              $('#login-error').text(response.message || 'Invalid email or password. Please try again.');
              $('#login-error').show();
            }
          } catch (error) {
            // Reset button state on error
            const $loginBtn = $('#loginButton');
            $loginBtn.removeClass('btn-loading').prop('disabled', false).find('.btn-text').text('Sign In');
            
            console.error('Error processing login:', error);
            $('#login-error').text('An error occurred. Please try again.');
            $('#login-error').show();
          }
        },
        error: function (xhr) {
          // Reset button state on AJAX error
          const $loginBtn = $('#loginButton');
          $loginBtn.removeClass('btn-loading').prop('disabled', false).find('.btn-text').text('Sign In');
          
          // Display error message in the form
          $('#login-error').text('Invalid email or password. Please try again.');
          $('#login-error').show();
          
          // Log the actual error to console for debugging
          console.error('Login error:', xhr.responseText);
        },
        complete: function() {
          // This will run after success or error callbacks
          // Ensures loader is hidden regardless of the request outcome
          $('#loginLoader').hide();
        }
      });
    });
  });
</script>



</body>

<!-- Toast Notifications
<script>
  $(document).ready(function() {
    // {% for message in messages %}
      $(document).Toasts('create', {
        class: 'bg-success',
        position: 'bottomRight',
        body: '{{ message }}',
        autohide: true,
        delay: 3000
      });
    // {% endfor %}
  });
</script> -->

</html>
