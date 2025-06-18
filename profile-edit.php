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
  
  .profile-picture-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 15px;
    display: block;
    border: 3px solid #30b8b9;
  }
  
  .profile-picture-upload {
    text-align: center;
    margin-bottom: 20px;
  }

</style>



<div class="card card-primary">

  <div class="card-header" style="background-color: transparent !important;border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);color:#212529;">

    <h3 class="card-title">Edit Profile</h3>

  </div>

  <div class="card-body">

    <form method="post" id="editProfileForm" enctype="multipart/form-data">
      <!-- Profile Picture Upload -->
      <div class="form-group profile-picture-upload">
        <img id="profilePicturePreview" class="profile-picture-preview" 
             src="<?php echo !empty($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['emp_name'] ?? 'User') . '&background=30b8b9&color=fff&size=150'; ?>" 
             alt="Profile Picture">
        <div class="input-group mb-3" style="max-width: 220px; margin: 0 auto;">
          <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*" style="display: none;">
          <label for="profile_picture" class="btn btn-primary btn-block" style="background-color: #30b8b9;border:none;">
            <i class="fas fa-camera"></i> Choose Profile Picture
          </label>
        </div>
        <small class="text-muted">Max file size: 5MB. Allowed formats: JPG, PNG, JPEG</small>
      </div>



      <!-- Email -->

      <div class="form-group">

        <label for="emp_email_id">Email ID <span class="text-danger">*</span></label>

        <div class="input-group">

          <span class="input-group-text"><i class="fas fa-envelope"></i></span>

          <input type="email" name="emp_email_id" id="emp_email_id" class="form-control" value="<?php echo htmlspecialchars(isset($user) ? $user['emp_email_id'] : ''); ?>" required readonly>

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



      <div class="card-footer p-0" style="background-color: #fff;">
        <button type="submit" class="btn btn-primary" id="saveChangesBtn" style="background-color: #30b8b9;border:none; min-width: 120px;">
          <span class="btn-text">Save Changes</span>
          <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="saveLoader"></span>
        </button>
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
  
  // Handle profile picture preview
  $('#profile_picture').on('change', function() {
    const file = this.files[0];
    if (file) {
      // Validate file type
      const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
      if (!validTypes.includes(file.type)) {
        alert('Please select a valid image file (JPEG, JPG, or PNG)');
        $(this).val('');
        return;
      }
      
      // Validate file size (5MB max)
      if (file.size > 5 * 1024 * 1024) {
        alert('File size exceeds 5MB limit');
        $(this).val('');
        return;
      }
      
      // Show preview
      const reader = new FileReader();
      reader.onload = function(e) {
        $('#profilePicturePreview').attr('src', e.target.result);
        
        // Upload the file
        const formData = new FormData();
        formData.append('access_token', '<?php echo $access_token; ?>');
        formData.append('activity_id', 'profile_picture');
        formData.append('emp_id', '<?php echo $emp_id; ?>');
        formData.append('attachment', file);
        
        $.ajax({
          url: '<?php echo rtrim(API_URL, '/'); ?>/attachment-add',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.is_successful === '1') {
              // Update the profile picture URL in the preview
              const profilePicUrl = response.data.attachments.attachment_url;
              $('#profilePicturePreview').attr('src', profilePicUrl);
              // You may want to update the profile picture in the session or local storage
              // and refresh the profile page to show the updated picture
              // location.reload(); // Uncomment this if you want to refresh the page after upload
            } else {
              alert('Failed to upload profile picture: ' + (response.errors || 'Unknown error'));
            }
          },
          error: function(xhr, status, error) {
            console.error('Error uploading file:', error);
            alert('Error uploading profile picture. Please try again.');
          }
        });
      };
      reader.readAsDataURL(file);
    }
  });

  $('#profile a').addClass('nav-link');



  $(document).ready(function() {
    $('#editProfileForm').on('submit', function(e) {
      e.preventDefault();
      
      // Disable the save button and show loader
      const $saveBtn = $('#saveChangesBtn');
      const $btnText = $saveBtn.find('.btn-text');
      const $loader = $('#saveLoader');
      
      $saveBtn.prop('disabled', true);
      $btnText.text('Saving...');
      $loader.removeClass('d-none');

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
          // Reset button state
          $saveBtn.prop('disabled', false);
          $btnText.text('Save Changes');
          $loader.addClass('d-none');

          if (response.is_successful === '1') {
            // Show success toast
            $(document).Toasts('create', {
              class: 'bg-success',
              title: 'Success',
              position: 'bottomRight',
              body: 'Profile updated successfully!',
              autohide: true,
              delay: 3000
            });

            // Redirect after showing message
            setTimeout(function() {
              window.location.href = 'profile';
            }, 1500);
          } else {
            const errorMsg = 'Error: ' + (response.errors || 'Failed to update profile');
            const errorDiv = $('<div>')
              .addClass('alert alert-danger')
              .text(errorMsg)
              .insertBefore('#editProfileForm');
              
            // Remove error message after 5 seconds
            setTimeout(() => errorDiv.fadeOut(500, () => errorDiv.remove()), 5000);
          }
        },
        error: function(xhr, status, error) {
          // Reset button state on error
          $saveBtn.prop('disabled', false);
          $btnText.text('Save Changes');
          $loader.addClass('d-none');
          
          const errorMsg = 'Error: ' + (xhr.responseJSON?.message || error || 'Failed to update profile');
          const errorDiv = $('<div>')
            .addClass('alert alert-danger')
            .text(errorMsg)
            .insertBefore('#editProfileForm');
            
          // Remove error message after 5 seconds
          setTimeout(() => errorDiv.fadeOut(500, () => errorDiv.remove()), 5000);
        },
        complete: function() {
          // Ensure button is always reset when request completes
          $saveBtn.prop('disabled', false);
          $btnText.text('Save Changes');
          $loader.addClass('d-none');
        }

      });

    });

  });

</script>



<?php include 'common/footer.php'; ?>

