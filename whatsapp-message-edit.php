<?php 

// Get schedule ID from URL and decode it
$scheduleId = isset($_GET['id']) ? base64_decode($_GET['id']) : null;
if (!$scheduleId) {
    header('Location: whatsapp-message-list.php');
    exit();
}

$pageTitle = 'Edit Scheduled Message';
include 'common/header.php';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Edit Scheduled Message</h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="whatsapp-message-list.php" class="btn btn-secondary">
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
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="user_select" class="form-label fw-bold">Select User <span class="text-muted">(Optional)</span></label>
                  <select class="form-control select2" id="user_select" name="user_select" style="width: 100%;" data-placeholder="Select User">
                    <option value="">-- Select User --</option>
                    <!-- Users will be loaded here -->
                  </select>
                  <small class="form-text text-muted">Select a user to auto-fill their phone number</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="phone_number" class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light">+91</span>
                    </div>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" 
                           placeholder="Enter 10-digit phone number" pattern="[0-9]{10}">
                  </div>
                  <small class="form-text text-muted">Enter phone number without country code</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="schedule_time" class="form-label fw-bold">Schedule Time</label>
                  <input type="datetime-local" class="form-control" id="schedule_time" name="schedule_time" 
                         min="<?php echo date('Y-m-d\TH:i'); ?>">
                  <small class="form-text text-muted">Leave empty to send immediately</small>
                </div>

                <div class="col-12 mb-3">
                  <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="message" name="message" rows="5" 
                            placeholder="Type your message here..." required></textarea>
                  <div class="d-flex justify-content-between mt-1">
                    <small class="form-text text-muted">Maximum 1000 characters</small>
                    <span class="text-muted"><span id="charCount">0</span>/1000</span>
                  </div>
                </div>
              </div>

              <input type="hidden" id="schedule_id" value="<?php echo htmlspecialchars($scheduleId); ?>">
              
              <div class="form-group mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-4" id="submitBtn" style="background-color: #30b8b9;border:none;">
                  <i class="fas fa-save me-2"></i> Update Message
                </button>
                <a href="whatsapp-message-list.php" class="btn btn-outline-secondary ms-2">
                  <i class="fas fa-times me-2"></i> Cancel
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
$(document).ready(function() {
  // Initialize Select2 with better configuration
 

  // Character counter for message
  $('#message').on('input', function() {
    const maxLength = 1000;
    const currentLength = $(this).val().length;
    const remaining = maxLength - currentLength;
    
    $('#charCount').text(currentLength);
    
    if (remaining < 0) {
      $(this).val($(this).val().substring(0, maxLength));
      $('#charCount').text(maxLength);
    }
  });

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
          // Sort users by name
          const sortedUsers = response.data.sort((a, b) => a.emp_name.localeCompare(b.emp_name));
          
          sortedUsers.forEach(function(user) {
            if (user.emp_name && user.emp_whatsapp_number) {
              $select.append(`
                <option value="${user.emp_id}" 
                        data-phone="${user.emp_whatsapp_number}"
                        data-name="${user.emp_name}">
                  ${user.emp_name}
                </option>
              `);
            }
          });
          
          // Refresh Select2 to show the new options
          $select.trigger('change');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading users:', error);
      }
    });
  }

  // Load users when page loads
  loadUsers();

  // Load schedule data
  function loadScheduleData() {
    const scheduleId = $('#schedule_id').val();
    
    // Convert the schedule ID to a number if it's a string
    const scheduleIdNum = isNaN(scheduleId) ? scheduleId : parseInt(scheduleId, 10);
    
    $.ajax({
      url: '<?php echo API_URL; ?>schedule-message-list',
      type: 'POST',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify({
        access_token: '<?php echo $_SESSION['access_token']; ?>',
        shed_id: scheduleIdNum
      }),
      success: function(response) {
        if (response.is_successful === "1" && response.data && response.data.length > 0) {
          const message = response.data[0];
          
          // Set form values
          $('#phone_number').val(message.phone_number.replace(/^91/, ''));
          $('#message').val(message.message);
          
          // Set the selected user in dropdown if emp_id exists
          if (message.emp_id) {
            const $userSelect = $('#user_select');
            // Clear previous selection
            $userSelect.val(null).trigger('change');
            
            // Check if the user exists in the dropdown
            const $option = $userSelect.find(`option[value="${message.emp_id}"]`);
            if ($option.length > 0) {
              // User exists in dropdown, select it
              $userSelect.val(message.emp_id).trigger('change');
            } else if (message.emp_name) {
              // User doesn't exist, add it to the dropdown
              const newOption = new Option(
                message.emp_name,
                message.emp_id,
                true,
                true
              );
              newOption.dataset.phone = message.phone_number.replace(/^91/, '');
              newOption.dataset.name = message.emp_name;
              $userSelect.append(newOption).trigger('change');
            }
          }
          
          // Set character count
          $('#charCount').text(message.message.length);
          
          // Set user in dropdown if emp_id exists
          if (message.emp_id) {
            $('#user_select').val(message.emp_id).trigger('change');
          }
          
          // Set schedule time if available
          if (message.schedule_time) {
            // Parse the GMT time string directly without timezone conversion
            const dateStr = message.schedule_time; // e.g., "Sat, 21 Jun 2025 00:00:00 GMT"
            const dateParts = dateStr.split(' ');
            if (dateParts.length >= 6) {
              const months = {
                'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06',
                'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
              };
              
              const day = dateParts[1].padStart(2, '0');
              const month = months[dateParts[2]];
              const year = dateParts[3];
              const time = dateParts[4];
              
              // Format: YYYY-MM-DDTHH:MM
              const formattedDate = `${year}-${month}-${day}T${time}`.slice(0, 16);
              
              // Set the value
              const $scheduleTime = $('#schedule_time');
              $scheduleTime.val(formattedDate);
              
              // Set min time to current time
              const now = new Date();
              const currentFormatted = now.toISOString().slice(0, 16);
              $scheduleTime.attr('min', currentFormatted);
            }
          }
        } else {
          showToast('Failed to load message details', false);
          setTimeout(() => {
            window.location.href = 'whatsapp-message-list.php';
          }, 1500);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading schedule data:', error);
        showToast('Error loading message details', false);
      }
    });
  }

  // When user is selected, update phone number
  $('#user_select').on('change', function() {
    const selectedOption = $(this).find('option:selected');
    if (selectedOption.val()) {
      const phone = selectedOption.data('phone') || '';
      if (typeof phone === 'string') {
        $('#phone_number').val(phone.replace(/^91/, ''));
      }
    }
  });

  // Form submission
  $('#messageForm').on('submit', function(e) {
    e.preventDefault();
    
    const scheduleId = $('#schedule_id').val();
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
      shed_id: scheduleId,
      message: message
    };
    
    // Add phone number based on selection or custom input
    if (selectedUser) {
      // Get the selected user's phone number from the dropdown
      const selectedOption = $('#user_select option:selected');
      const userPhone = String(selectedOption.data('phone') || '');
      requestData.phone_number = userPhone.replace(/^91/, '');
    } else {
      requestData.phone_number = '91' + phoneNumber;
    }
    
    // Add schedule time if provided
    if (scheduleTime) {
      // Convert datetime-local format to ISO string
      const date = new Date(scheduleTime);
      // Format as: 'YYYY-MM-DD HH:mm:ss' (MySQL DATETIME format)
      const formattedDateTime = date.toISOString().replace('T', ' ').slice(0, 19);
      requestData.schedule_time = formattedDateTime;
    }
    
    const submitBtn = $('#submitBtn');
    const originalBtnText = submitBtn.html();
    
    // Disable button and show loading state
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
    
    // Make API request
    $.ajax({
      url: '<?php echo API_URL; ?>schedule-message-update',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(requestData),
      success: function(response) {
        if (response.is_successful === "1") {
          showToast('Message updated successfully!', true);
          // Redirect to list page after 1.5 seconds
          setTimeout(() => {
            window.location.href = 'whatsapp-message-list.php';
          }, 1500);
        } else {
          showToast(response.errors || 'Failed to update message', false);
          submitBtn.prop('disabled', false).html(originalBtnText);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error updating message:', error);
        showToast('An error occurred while updating the message', false);
        submitBtn.prop('disabled', false).html(originalBtnText);
      }
    });
  });

  // Load schedule data when page loads
  loadScheduleData();
});
</script>