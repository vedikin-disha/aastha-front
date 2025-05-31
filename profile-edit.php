<?php 
include 'common/header.php';

// API call to fetch user data
$url = rtrim(API_URL, '/') . '/user';
$access_token = $_SESSION['access_token'] ?? '';

// Get emp_id from session or query parameter
$emp_id = $_SESSION['emp_id'] ?? $_GET['emp_id'] ?? 1;

$data = array(
    'access_token' => $access_token,
    'emp_id' => $emp_id
);

$options = array(
    'http' => array(
        'header'  => "Content-Type: application/json\r\n" .
                   "Accept: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true,
        'timeout' => 30,
        'protocol_version' => 1.1
    ),
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false
    )
);

$context = stream_context_create($options);

try {
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        throw new Exception('Failed to connect to server');
    }
    $response = json_decode($result, true);
    if ($response['is_successful'] === '1') {
        $user = $response['data'];
    } else {
        $user = null;
        $error = $response['errors'] ?: 'Failed to fetch user data';
    }
} catch (Exception $e) {
    $user = null;
    $error = $e->getMessage();
}
?>
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
    <h3 class="card-title">Edit Profile</h3>
  </div>
  <div class="card-body">
    <form method="post" id="editProfileForm">

      <!-- Email -->
      <div class="form-group">
        <label for="emp_email_id">Email ID <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" name="emp_email_id" id="emp_email_id" class="form-control" value="<?php echo htmlspecialchars(isset($user) ? $user['emp_email_id'] : ''); ?>" required>
        </div>
        <?php if (isset($errors['emp_email_id'])): ?>
        <div class="text-danger"><?php echo htmlspecialchars($errors['emp_email_id']); ?></div>
        <?php endif; ?>
      </div>

      <!-- Name -->
      <div class="form-group">
        <label for="emp_name">Full Name <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" name="emp_name" id="emp_name" class="form-control" value="<?php echo htmlspecialchars(isset($user) ? $user['emp_name'] : ''); ?>" required>
        </div>
        <?php if (isset($errors['emp_name'])): ?>
        <div class="text-danger"><?php echo htmlspecialchars($errors['emp_name']); ?></div>
        <?php endif; ?>
      </div>

      <!-- Phone Number -->
      <div class="form-group">
        <label for="emp_phone_number">Phone Number <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-phone"></i></span>
          <input type="text" name="emp_phone_number" id="emp_phone_number" class="form-control" value="<?php echo htmlspecialchars(isset($user) ? $user['emp_phone_number'] : ''); ?>" required>
        </div>
        <?php if (isset($errors['emp_phone_number'])): ?>
        <div class="text-danger"><?php echo htmlspecialchars($errors['emp_phone_number']); ?></div>
        <?php endif; ?>
      </div>

      <!-- WhatsApp Number -->
      <div class="form-group">
        <label for="emp_whatsapp_number">WhatsApp Number</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
          <input type="text" name="emp_whatsapp_number" id="emp_whatsapp_number" class="form-control" value="<?php echo htmlspecialchars(isset($user) ? $user['emp_whatsapp_number'] : ''); ?>">
        </div>
        <?php if (isset($errors['emp_whatsapp_number'])): ?>
        <div class="text-danger"><?php echo htmlspecialchars($errors['emp_whatsapp_number']); ?></div>
        <?php endif; ?>
      </div>

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="profile" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<script>
  // add nav-link active class to id = circle
  $('#profile a').addClass('active');
  $('#profile a').addClass('nav-link');

  $(document).ready(function() {
    $('#editProfileForm').on('submit', function(e) {
      e.preventDefault();
      
      // Get the user data from the already fetched profile
      const access_token = '<?php echo addslashes($access_token); ?>';
      const emp_id = '<?php echo addslashes($user["emp_id"]); ?>';
      
      const formData = {
        access_token: access_token,
        emp_id: emp_id,
        full_name: $('#emp_name').val(),
        phone: $('#emp_phone_number').val(),
        w_phone: $('#emp_whatsapp_number').val() || undefined
      };

      // // Debug output
      // console.log('Form data being sent:', formData);

      $.ajax({
        url: '<?php echo rtrim(API_URL, '/'); ?>/update-user',
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function(response) {
          if (response.is_successful === '1') {
            // Show success message
            const successDiv = $('<div>')
              .addClass('alert alert-success')
              .text('Profile updated successfully!')
              .insertBefore('#editProfileForm');
            
            // Redirect after showing message
            setTimeout(function() {
              window.location.href = 'profile';
            }, 1500);
          } else {
            const errorDiv = $('<div>')
              .addClass('alert alert-danger')
              .text('Error: ' + (response.errors || 'Failed to update profile'))
              .insertBefore('#editProfileForm');
          }
        },
        error: function(xhr, status, error) {
          const errorDiv = $('<div>')
              .addClass('alert alert-danger')
              .text('Error: ' + error)
              .insertBefore('#editProfileForm');
        }
      });
    });
  });
</script>

<?php include 'common/footer.php'; ?>
