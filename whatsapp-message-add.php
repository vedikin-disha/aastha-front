<?php 
include 'common/header.php';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Schedule New WhatsApp Message</h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="whatsapp-message-list" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to List
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
            <h3 class="card-title">Message Details</h3>
          </div>
          <div class="card-body">
            <form id="messageForm">
              <div class="form-group">
                <label for="user_select">Select User <span class="text-muted">(Optional)</span></label>
                <select class="form-control select2" id="user_select" name="user_select">
                  <option value="">-- Select User --</option>
                  <!-- Users will be loaded here -->
                </select>
                <small class="form-text text-muted">Select a user to auto-fill their phone number</small>
              </div>

              <div class="form-group">
                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">+91</span>
                  </div>
                  <input type="text" class="form-control" id="phone_number" name="phone_number" 
                         placeholder="Enter 10-digit phone number" pattern="[0-9]{10}" >
                </div>
                <small class="form-text text-muted">Enter phone number without country code</small>
              </div>

              <div class="form-group">
                <label for="message">Message <span class="text-danger">*</span></label>
                <textarea class="form-control" id="message" name="message" rows="5" 
                          placeholder="Type your message here..." required></textarea>
                <small class="form-text text-muted">Maximum 1000 characters</small>
                <div class="text-right"><span id="charCount">0</span>/1000</div>
              </div>

              <div class="form-group">
                <label for="schedule_time">Schedule Time</label>
                <input type="datetime-local" class="form-control" id="schedule_time" name="schedule_time" 
                       value="<?php 
                         $currentTime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                         echo $currentTime->format('Y-m-d\TH:i');
                       ?>"
                       min="<?php echo date('Y-m-d\TH:i'); ?>">
                <small class="form-text text-muted">Leave empty to send immediately</small>
              </div>

              <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn" style="background-color: #30b8b9;border:none;">
                  <i class="fas fa-paper-plane"></i> Schedule Message
                </button>
                <a href="whatsapp-message-list" class="btn btn-secondary">
                  <i class="fas fa-times"></i> Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'common/footer.php'; ?>

<!-- Include Select2 for better dropdowns -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Include Moment.js for date handling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
// Function to load users from API
function loadUsers() {
  $.ajax({
    url: '<?php echo API_URL; ?>user',
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify({
      access_token: '<?php echo $_SESSION['access_token']; ?>'
    }),
    success: function(response) {
      if (response.is_successful === "1" && response.data) {
        const $select = $('#user_select');
        response.data.forEach(function(user) {
          $select.append(`
            <option value="${user.emp_id}" 
                    data-phone="${user.emp_whatsapp_number}"
                    data-name="${user.emp_name}">
              ${user.emp_name} 
            </option>
          `);
        });
      }
    },
    error: function(xhr, status, error) {
      console.error('Error loading users:', error);
    }
  });
}

$(document).ready(function() {
  // Load users when page loads
  loadUsers();

  // When user is selected, update phone number
  $('#user_select').on('change', function() {
    const selectedOption = $(this).find('option:selected');
    if (selectedOption.val()) {
      // Get the phone number from the data attribute
      let phoneNumber = selectedOption.data('phone') || '';
      // Remove any non-digit characters
      phoneNumber = phoneNumber.toString().replace(/\D/g, '');
      // Ensure we don't have a leading 91 for display purposes
      const displayNumber = phoneNumber.startsWith('91') ? phoneNumber.substring(2) : phoneNumber;
      // Update the phone number field with display number
      $('#phone_number').val(displayNumber);
      
      // Debug log (you can remove this after confirming it works)
      console.log('Selected user phone:', phoneNumber, 'Display number:', displayNumber);
    } else {
      // Clear the field if no user is selected
      $('#phone_number').val('');
    }
  });

  // Character counter for message
  $('#message').on('input', function() {
    const maxLength = 1000;
    const currentLength = $(this).val().length;
    $('#charCount').text(currentLength);
    
    if (currentLength > maxLength) {
      $(this).val($(this).val().substring(0, maxLength));
      $('#charCount').text(maxLength);
    }
  });

  // Form submission
  $('#messageForm').on('submit', function(e) {
    e.preventDefault();
    
    const selectedUser = $('#user_select').val();
    const phoneNumber = $('#phone_number').val();
    const message = $('#message').val();
    const scheduleTime = $('#schedule_time').val();
    
    // Basic validation
    if ((!selectedUser && !phoneNumber) || !message) {
      showToast('Please fill in all required fields', false);
      return;
    }
    
    // Prepare data for API
    const requestData = {
      access_token: '<?php echo $_SESSION['access_token']; ?>',
      message: message
    };
    
    // Add phone number based on selection or custom input
    if (selectedUser) {
      // Get the selected user's phone number from the dropdown
      const selectedOption = $('#user_select option:selected');
      let userPhone = String(selectedOption.data('phone') || '');
      // Ensure the number starts with 91
      userPhone = userPhone.startsWith('91') ? userPhone : '91' + userPhone;
      requestData.phone_number = userPhone;
    } else {
      // For custom input, ensure it starts with 91 and has 12 digits total (91 + 10 digit number)
      const cleanNumber = phoneNumber.replace(/\D/g, '');
      requestData.phone_number = cleanNumber.startsWith('91') ? cleanNumber : '91' + cleanNumber;
    }
    
    // Add schedule time if provided
    if (scheduleTime) {
    // Create a Date object from the input
    const date = new Date(scheduleTime);
    
    // Format as: 'YYYY-MM-DD HH:mm:ss' (MySQL DATETIME format)
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = '00'; // Since we're not collecting seconds in the input
    
    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    requestData.schedule_time = formattedDateTime;
}
    
    const submitBtn = $('#submitBtn');
    const originalBtnText = submitBtn.html();
    
    // Disable button and show loading state
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Scheduling...');
    
    // Make API request
    $.ajax({
      url: '<?php echo API_URL; ?>schedule-message',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(requestData),
      success: function(response) {
        if (response.is_successful === "1") {
          showToast('Message scheduled successfully!', true);
          // Redirect to list page after 1.5 seconds
          setTimeout(() => {
            window.location.href = 'whatsapp-message-list';
          }, 1500);
        } else {
          showToast(response.errors || 'Failed to schedule message', false);
          submitBtn.prop('disabled', false).html(originalBtnText);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error scheduling message:', error);
        showToast('An error occurred while scheduling the message', false);
        submitBtn.prop('disabled', false).html(originalBtnText);
      }
    });
  });
});
</script>

