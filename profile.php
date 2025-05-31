<?php 
include 'common/header.php';

// API call to fetch user data
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fix double slash in URL
$url = rtrim(API_URL, '/') . '/user';
$access_token = $_SESSION['access_token'] ?? '';

// Debug information
echo '<div style="display:none">';
echo 'API URL: ' . $url . '<br>';
echo 'Access Token: ' . $access_token . '<br>';
echo 'Session Data: <pre>' . print_r($_SESSION, true) . '</pre><br>';

// Get emp_id from session or query parameter
$emp_id = $_SESSION['emp_id'] ?? $_GET['emp_id'] ?? 1; // Default to 1 if not specified

$data = array(
    'access_token' => $access_token,
    'emp_id' => $emp_id
);

echo 'Request Data: <pre>' . print_r($data, true) . '</pre><br>';

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
// Try to make the API call
try {
    $result = file_get_contents($url, false, $context);
    $response_headers = $http_response_header ?? array();
    
    // Get HTTP status code
    $status_line = $response_headers[0] ?? '';
    preg_match('{HTTP/\S*\s(\d{3})}', $status_line, $match);
    $status = $match[1] ?? 500;
    
    echo '<div style="display:none">HTTP Status: ' . $status . '</div>';
    
    if ($result === FALSE) {
        throw new Exception('Failed to connect to server');
    }
} catch (Exception $e) {
    $result = false;
    echo '<div style="display:none">Error: ' . $e->getMessage() . '</div>';
}

echo 'API Response: <pre>' . print_r($result, true) . '</pre><br>';
echo '</div>';

if ($result === FALSE) {
    $user = null;
    $error = 'Failed to fetch user data';
} else {
    $response = json_decode($result, true);
    if ($response['is_successful'] === '1') {
        $user = $response['data'];
    } else {
        $user = null;
        $error = $response['errors'] ?: 'Failed to fetch user data';
    }
}
?>
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card card-primary card-outline">
        <div class="card-body box-profile">
          <div class="text-center mb-3">
            <img class="profile-user-img img-fluid img-circle"
                 src="https://ui-avatars.com/api/?name=<?php echo htmlspecialchars($user['emp_name'] ?? 'User'); ?>&background=007bff&color=fff&size=128"
                 alt="User profile picture">
          </div>
          <h3 class="profile-username text-center"><?php echo htmlspecialchars($user['emp_name'] ?? 'User'); ?></h3>
          <p class="text-muted text-center"><?php echo htmlspecialchars($user['emp_role_name'] ?? ''); ?></p>
          <ul class="list-group list-group-unbordered mb-3">
            <?php if ($user): ?>
            <li class="list-group-item"><b>Email</b> <span class="float-right"><?php echo htmlspecialchars($user['emp_email_id']); ?></span></li>
            <li class="list-group-item"><b>Phone Number</b> <span class="float-right"><?php echo htmlspecialchars($user['emp_phone_number']); ?></span></li>
            <li class="list-group-item"><b>WhatsApp Number</b> <span class="float-right"><?php echo htmlspecialchars($user['emp_whatsapp_number']); ?></span></li>
            <li class="list-group-item"><b>Role</b> <span class="float-right"><?php echo htmlspecialchars($user['emp_role_name']); ?></span></li>
            <?php else: ?>
            <li class="list-group-item text-center text-danger"><?php echo htmlspecialchars($error ?? 'User data not available'); ?></li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="text-center mb-3">
          <a href="profile-edit" class="btn btn-primary">Edit Profile</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#profile a').addClass('active');
  $('#profile a').addClass('nav-link');
</script>


<!-- Toast Notifications -->
<?php if (isset($messages) && $messages): ?>
<script>
  $(document).ready(function() {
    // {% for message in messages %}
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Success',
        position: 'bottomRight',
        body: '{{ message }}',
        autohide: true,
        delay: 3000
      });
    // {% endfor %}
  });
</script>
<?php endif ?>

<?php include 'common/footer.php'; ?>