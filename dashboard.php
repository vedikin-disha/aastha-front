
<?php include 'common/header.php'; 

isUserLoggedIn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Optional: Bootstrap CSS for styling -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>

  <div class="container mt-5">
    <div id="dashboard-container"></div>
  </div>

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

  <!-- Role-Based Dashboard Logic -->
  <script>
   
    const container = document.getElementById('dashboard-container');

    if (user.emp_role_id == 0 || user.emp_role_id == 1) {
      container.innerHTML = `
        <div class="card">
          <div class="card-header bg-primary text-white">Dashboard 1</div>
          <div class="card-body">
            <p>Welcome, <strong>${user.emp_name}</strong> (Role: ${user.emp_role_name})</p>
            <p>This is Dashboard 1 content for Admin and Data Entry Operator.</p>
          </div>
        </div>
      `;
    } else if (user.emp_role_id == 2 || user.emp_role_id == 3) {
      container.innerHTML = `
        <div class="card">
          <div class="card-header bg-success text-white">Dashboard 2</div>
          <div class="card-body">
            <p>Welcome, <strong>${user.emp_name}</strong> (Role: ${user.emp_role_name})</p>
            <p>This is Dashboard 2 content for HOD and Employee.</p>
          </div>
        </div>
      `;
    } else {
      container.innerHTML = `
        <div class="alert alert-warning">Unknown role. No dashboard found.</div>
      `;
    }
  </script>

</body>
</html>

<?php include 'common/footer.php'; ?>
