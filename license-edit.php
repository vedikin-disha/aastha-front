<?php 
include 'common/header.php';

// Get and decode license ID from URL
$encoded_id = isset($_GET['id']) ? $_GET['id'] : '';
$license_id = 0;

if ($encoded_id) {
    $decoded_id = base64_decode($encoded_id);
    $license_id = is_numeric($decoded_id) ? intval($decoded_id) : 0;
}

if (!$license_id) {
    echo "<script>window.location.href = 'license-list.php';</script>";
    exit();
}
?>
<input type="hidden" id="license_id" value="<?php echo $license_id; ?>">


 
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
                <label for="user_email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="user_email" name="user_email" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date">Start Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
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
              
            </div>
            <small class="form-text text-muted">Activation key cannot be changed</small>
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

</div>

<?php include 'common/footer.php'; ?>

<script>
$(document).ready(function() {
    const accessToken = '<?php echo isset($_SESSION['access_token']) ? $_SESSION['access_token'] : ''; ?>';
    const licenseId = $('#license_id').val();
    
    if (!accessToken) {
        showToast('Please login first', false);
        setTimeout(() => { window.location.href = 'login.php'; }, 1500);
        return;
    }

    // Function to format date for input field (YYYY-MM-DD)
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    }

    // Fetch license data
    function fetchLicenseData() {
        $.ajax({
            url:  '<?php echo API_URL; ?>license-list',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION['access_token']; ?>',
                license_id: parseInt(licenseId)
            }),
            success: function(response) {
                console.log('API Response:', response);
                if (response.is_successful === '1' && response.data) {
                    const licenseData = response.data;
                    
                    // Populate form fields
                    $('#user_name').val(licenseData.user_name || '');
                    $('#phone_number').val(licenseData.user_phone_number || '');
                    $('#user_email').val(licenseData.user_email || '');
                    $('#start_date').val(formatDateForInput(licenseData.start_date));
                    $('#end_date').val(formatDateForInput(licenseData.end_date));
                    $('#activation_key').val(licenseData.activation_key || '');
                    $('#status').val(licenseData.status || '0');
                    $('#additional_details').val(licenseData.description || '');
                    
                    // Set product information if available
                    if (licenseData.product_id) {
                        $('#product_id').val(licenseData.product_id);
                    }
                    
                    // Display product name if element exists
                    if ($('#product_name').length) {
                        $('#product_name').text(licenseData.product_name || 'N/A');
                    }
                    
                } else {
                    const errorMsg = response.errors || 'Failed to load license data';
                    showToast(errorMsg, false);
                    console.error('API Error:', response);
                    // Redirect back to list after showing error
                    setTimeout(() => { window.location.href = 'license-list.php'; }, 2000);
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to load license data. Please try again.';
                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.errors || 
                                xhr.responseJSON.error || 
                                JSON.stringify(xhr.responseJSON);
                }
                showToast(errorMessage, false);
                console.error('AJAX Error:', error, 'Status:', status);
                console.error('Response:', xhr.responseText);
                // Redirect back to list after showing error
                setTimeout(() => { window.location.href = 'license-list.php'; }, 2000);
            }
        });
    }

    // Call the function to fetch and populate data
    fetchLicenseData();

    // Generate activation key (functionality removed - button is disabled)
    // $('#generateKeyBtn').click(function() {
    //     const randomKey = Math.random().toString(36).substr(2, 9).toUpperCase() + 
    //                     '-' + Math.random().toString(36).substr(2, 6).toUpperCase() +
    //                     '-' + Math.random().toString(36).substr(2, 6).toUpperCase();
    //     $('#activation_key').val(randomKey);
    // });

    // Form submission handler
    $('#licenseForm').on('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button to prevent multiple submissions
        const $submitBtn = $('#submitBtn');
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        // Prepare the data for the API
        const formData = {
            access_token: '<?php echo $_SESSION['access_token']; ?>',
            license_id: parseInt(licenseId),
            user_name: $('#user_name').val().trim(),
            user_email: $('#user_email').val().trim(),
            user_phone_number: $('#phone_number').val().trim(),
            activation_key: $('#activation_key').val().trim(),
            status: $('#status').val(),
            start_date: $('#start_date').val() + 'T00:00:00', // Add time component
            end_date: $('#end_date').val() + 'T23:59:59',     // Add time component
            product_id: $('#product_id').val() || 1,           // Default to 1 if not set
            description: $('#additional_details').val().trim()
        };
        
        // Log the data being sent (for debugging)
        console.log('Sending update data:', formData);
        
        // Send the update request
        $.ajax({
            url: '<?php echo API_URL; ?>license-update',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.log('Update Response:', response);
                if (response.is_successful === '1') {
                    showToast(response.success_message || 'License updated successfully', true);
                    // Redirect to list page after a short delay
                    setTimeout(() => {
                        window.location.href = 'license-list.php';
                    }, 1500);
                } else {
                    const errorMsg = response.errors || 'Failed to update license';
                    showToast(errorMsg, false);
                    $submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save License');
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to update license. Please try again.';
                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.errors || 
                                xhr.responseJSON.error || 
                                JSON.stringify(xhr.responseJSON);
                }
                showToast(errorMessage, false);
                console.error('Update Error:', error, 'Status:', status);
                console.error('Response:', xhr.responseText);
                $submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save License');
            }
        });
    });
});
</script>

