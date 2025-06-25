<?php include 'common/header.php'; ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Add New License</h1>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
        <h3 class="card-title">License Information</h3>
      </div>
      <div class="card-body">
        <form id="licenseForm">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="user_name">Person Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="user_name" name="user_name" required>
              </div>
            </div>
          
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
              </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                <label for="user_email">Email </label>
                <input type="email" class="form-control" id="user_email" name="user_email" >
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="<?php echo date('Y-m-d'); ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="end_date">End Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="activation_key">Activation Key</label>
            <div class="input-group">
              <input type="text" class="form-control" id="activation_key" name="activation_key" readonly>
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="generateKeyBtn">
                  <i class="fas fa-sync-alt"></i> Generate
                </button>
              </div>
            </div>
            <small class="form-text text-muted">Click the generate button to create a new activation key</small>
          </div>

          <div class="form-group">
            <label for="additional_details">Additional Details</label>
            <textarea class="form-control" id="additional_details" name="additional_details" rows="3"></textarea>
          </div>

          <div class="form-group">
            <label for="status">License Status <span class="text-danger">*</span></label>
            <select class="form-control" id="status" name="status" required>
              <option value="1">Active</option>
              <option value="0" selected>Inactive</option>
            </select>
          </div>

          <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary" id="submitBtn" style="background-color: #30b8b9;border:none;">
              <i class="fas fa-save"></i> Save License
            </button>
            <a href="license-list" class="btn btn-secondary">
              <i class="fas fa-times"></i> Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>

<script>
$(document).ready(function() {
  // Generate activation key
  function generateActivationKey() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    const length = 50;
    
    // Create a more secure random value using crypto if available
    const crypto = window.crypto || window.msCrypto;
    
    if (crypto && crypto.getRandomValues) {
      // Use crypto API for better randomness
      const values = new Uint32Array(length);
      crypto.getRandomValues(values);
      
      for (let i = 0; i < length; i++) {
        result += chars[values[i] % chars.length];
      }
    } else {
      // Fallback to Math.random if crypto API is not available
      for (let i = 0; i < length; i++) {
        result += chars[Math.floor(Math.random() * chars.length)];
      }
    }
    
    return result;
  }

  // Generate and set activation key
  function setNewActivationKey() {
    const newKey = generateActivationKey();
    $('#activation_key').val(newKey);
    return newKey;
  }
  
  // Handle generate button click
  $('#generateKeyBtn').on('click', function() {
    const newKey = setNewActivationKey();
    showToast('New activation key generated!', true);
    return newKey;
  });
  
  // Ensure a key is generated when form is submitted if empty
  $('#licenseForm').on('submit', function() {
    if (!$('#activation_key').val()) {
      setNewActivationKey();
    }
  });

  // Form submission
  $('#licenseForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = {
      user_name: $('#user_name').val(),
      user_email: $('#user_email').val(),
      user_phone_number: $('#phone_number').val(),
      product_id:1,
      start_date: $('#start_date').val(),
      end_date: $('#end_date').val(),
      activation_key: $('#activation_key').val(),
      description: $('#additional_details').val(),
      status: $('#status').is(':checked') ? 1 : 0,
      access_token: '<?php echo $_SESSION['access_token']; ?>'
    };

    // Disable submit button and show loading state
    const $submitBtn = $('#submitBtn');
    $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    // Make API call
    $.ajax({
      url: '<?php echo API_URL; ?>license-add',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(formData),
      success: function(response) {
        if (response.is_successful === '1') {
          showToast('License added successfully!', true);
          // Redirect to license list after 1.5 seconds
          setTimeout(() => {
            window.location.href = 'license-list';
          }, 1500);
        } else {
          showToast(response.errors || 'Failed to add license', false);
          $submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save License');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
        showToast('An error occurred while saving the license', false);
        $submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save License');
      }
    });
  });

  // Date fields will be empty by default
  // Add any date validation or formatting here if needed
});
</script>
