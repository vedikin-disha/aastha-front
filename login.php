<?php
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
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Aastha</b>PMS</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
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
            <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
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
          if (response.is_successful === "1") {
            
// add all fields individually
            // sessionStorage.setItem('access_token', response.data.access_token);
            // sessionStorage.setItem('emp_id', response.data.emp_id);
            // sessionStorage.setItem('emp_name', response.data.emp_name);
            // sessionStorage.setItem('emp_role_id', response.data.emp_role_id);
            // sessionStorage.setItem('dept_id', response.data.dept_id);
            // sessionStorage.setItem('whatsapp_number', response.data.whatsapp_number);
            // sessionStorage.setItem('dept_name', response.data.dept_name);
            // sessionStorage.setItem('emp_role_name', response.data.emp_role_name);

            // sessionStorage.setItem('loginSuccess', '1');

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


            // ✅ Show success toast
            $(document).Toasts('create', {
              class: 'bg-success',
              position: 'bottomRight',
              title: 'Login Successful',
              body: `Welcome, ${response.data.emp_name}`,
              autohide: true,
              delay: 3000
            });

            // Redirect after short delay
            setTimeout(() => {
              const roleId = parseInt(response.data.emp_role_id);
              if (roleId === 1 || roleId === 2) {
                window.location.href = 'dashboard-admin.php';
              } else if (roleId === 3 || roleId === 4) {
                window.location.href = 'dashboard-user.php';
              }
            }, 2000);

  } else {
    alert("Login failed");
  }
}
,
        error: function (xhr) {
          alert('Request error: ' + xhr.responseText);
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
