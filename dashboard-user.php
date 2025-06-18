<?php include 'common/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard 2</title>
</head>
<body>
  <h1>Welcome to User Dashboard</h1>
  
</body>
</html>

<script>
  $(document).ready(function() {
    // Highlight dashboard in sidebar
    $('#dashboard a').addClass('active');
    
    if (sessionStorage.getItem('loginSuccess') === '1') {
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Login Successful',
        body: `Welcome, ${user.emp_name}`,
        autohide: true,
        delay: 2000
      });

      sessionStorage.removeItem('loginSuccess'); // Remove the flag
    }
  });
</script>

<?php include 'common/footer.php'; ?>

