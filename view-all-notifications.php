<?php include 'common/header.php'; ?>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Add DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
    /* Custom styles for the clear all button */
    #clear-all-notifications {
        padding: 0.375rem 1rem;
        font-weight: 500;
    }
    #clear-all-notifications i {
        margin-right: 5px;
    }
    .notification-link {
    color: #007bff;
    text-decoration: underline;
    word-break: break-all;
}
.notification-link:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap4.min.css">
<!-- Add Bootstrap Switch CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-switch@3.3.4/dist/css/bootstrap3/bootstrap-switch.min.css">
<style>
    /* Custom styles for notifications */
    .notifications-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .notifications-card .card-header {
        background: #30b8b9;
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }
    
    .notifications-card .card-title {
        font-weight: 600;
        margin-bottom: 0;
    }
    
    .notifications-table {
        margin-bottom: 0;
    }
    
    .notifications-table thead th {
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .notifications-table tbody tr {
        transition: background-color 0.2s;
    }
    
    .notifications-table tbody tr:hover {
        background-color: #f8f9fc;
    }
    
    .notification-user {
        display: flex;
        align-items: center;
    }
    
    .notification-user img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        border: 2px solid #e3e6f0;
    }
    
    .notification-user .user-info h6 {
        margin: 0;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .notification-user .user-info small {
        color: #858796;
        font-size: 0.8rem;
    }
    
    .notification-message {
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .notification-time {
        color: #858796;
        font-size: 0.85rem;
    }
    
    .notification-action .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .notification-unread {
        background-color: #f8f9fc;
    }
    
    .notification-unread .notification-message {
        font-weight: 600;
    }
    
    .notification-important .notification-message::before {
        content: '!';
        display: inline-block;
        width: 18px;
        height: 18px;
        background-color: #e74a3b;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 18px;
        margin-right: 8px;
        font-size: 0.7rem;
        font-weight: bold;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-lg-12">
            <div class="card notifications-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0" style=" color: #fff">
                        <i class="fas fa-bell mr-2"></i>All Notifications
                    </h3>
                    <!-- end card-header -->
                    <div class="card-tools" style="margin-right: -57.625rem;">
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse" style="color: #fff;">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- end card-tools -->
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle notifications-table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">User</th>
                                    <th>Notification</th>
                                    <th style="width: 180px;" class="text-center">Date & Time</th>
                                    <th style="width: 80px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="all-notifications-list">
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Loading notifications...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-flex justify-content-between">
                        <button id="clear-all-notifications" class="btn btn-danger">
                            <i class="fas fa-trash-alt mr-2"></i>Clear All Notifications
                        </button>
                        <div>
                            <a href="dashboard" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
  </div>
</div>


<script>
$(document).ready(function() {
    // Load all notifications
    function loadAllNotifications() {
        $.ajax({
            url: '<?php echo API_URL; ?>notification-list',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({
                access_token: '<?php echo isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ""; ?>'
            }),
            contentType: 'application/json',
            success: function(response) {
                let notificationsHtml = '';
                
                if (response.is_successful === '1' && response.data && response.data.length > 0) {
                    response.data.forEach(function(notification) {
                        const today = new Date();
                        const notificationDate = new Date(notification.created_at);
                        let formattedDate = '';
                        
                        if (notificationDate.toDateString() === today.toDateString()) {
                            // Show time for today's notifications
                            formattedDate = 'Today ' + notificationDate.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });
                        } else {
                            // Show date and time for older notifications
                            formattedDate = notificationDate.toLocaleString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });
                        }
                        
                        // Get user details
                        const displayName = notification.creator_name || 'User';
                        const userImage = notification.creator_profile || 'assets/img/default-avatar.png';
                        
                        notificationsHtml += `
                            <tr class="notification-row ${notification.is_active === '1' ? 'font-weight-bold' : ''}" data-notification-id="${notification.notification_id}">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative" style="width: 40px; height: 40px; min-width: 40px;">
                                            <img src="${userImage}" alt="${displayName}" 
                                                class="img-fluid rounded-circle" 
                                                style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #dee2e6;"
                                                onerror="this.src='assets/img/default-avatar.png'"
                                            >
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-dark" style="font-size: 0.9rem;">${displayName}</div>
                                           
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas ${notification.is_important ? 'fa-exclamation-circle text-danger' : 'fa-info-circle text-primary'}"></i>
                                        </div>
                                        <div>
                                           <div class="text-dark">${formatNotificationText(notification.notification)}</div>
                                            ${notification.project_name ? `<small class="text-muted">Project: ${notification.project_name}</small>` : ''}
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="text-muted" style="font-size: 0.85rem;">
                                        <i class="far fa-clock mr-1"></i>${formattedDate}
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger delete-notification" 
                                            data-notification-id="${notification.notification_id}"
                                            title="Mark as read">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    notificationsHtml = `
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                No notifications found
                            </td>
                        </tr>`;
                }
                
                $('#all-notifications-list').html(notificationsHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching all notifications:', error);
                $('#all-notifications-list').html(`
                    <tr>
                        <td colspan="4" class="text-center text-danger py-4">
                            Error loading notifications. Please try again later.
                        </td>
                    </tr>`);
            }
        });
    }
    
    // Handle Clear All Notifications
    $(document).on('click', '#clear-all-notifications', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Clearing...');
            
            // Call the API to clear all notifications
            $.ajax({
                url: '<?php echo API_URL; ?>inactive-notification',
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify({
                    access_token: '<?php echo isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ""; ?>'
                }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.is_successful === '1') {
                        // Reload the page to show empty state
                        window.location.reload();
                    } else {
                        alert('Failed to clear notifications: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error clearing all notifications:', error);
                    alert('Failed to clear notifications. Please try again.');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="fas fa-trash-alt mr-2"></i>Clear All Notifications');
                }
            });
        }
    });
    
    // Mark a single notification as read
    $(document).on('click', '.delete-notification', function() {
        const $btn = $(this);
        const notificationId = $btn.data('notification-id');
        
        $.ajax({
            url: '<?php echo API_URL; ?>inactive-notification',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({
                access_token: '<?php echo isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ""; ?>',
                notification_id: notificationId
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.is_successful === '1') {
                    $btn.closest('.notification-row').fadeOut(300, function() {
                        $(this).remove();
                        // Reload notifications if there are no more notifications
                        if ($('.notification-row').length === 0) {
                            loadAllNotifications();
                        }
                    });
                } else {
                    alert('Failed to delete notification. Please try again.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the notification.');
            }
        });
    });
    
    // Mark all notifications as read
    $('#mark-all-read').on('click', function() {
        if (confirm('Are you sure you want to mark all notifications as read?')) {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            
            $.ajax({
                url: '<?php echo API_URL; ?>inactive-all-notification',
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify({
                    access_token: '<?php echo isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ""; ?>',
                    is_active: '0' // Mark as read
                }),
                contentType: 'application/json',
                success: function(response) {
                    $btn.prop('disabled', false).html('<i class="far fa-check-circle mr-1"></i> Mark All as Read');
                    
                    if (response.is_successful === '1') {
                        // Remove all notification rows
                        $('.notification-row').fadeOut(300, function() {
                            $(this).remove();
                            // Show success message
                        const successHtml = `
                            <tr>
                                <td colspan="4" class="text-center py-4 text-success">
                                    <i class="fas fa-check-circle mr-2"></i> All notifications marked as read
                                </td>
                            </tr>`;
                        $('#all-notifications-list').html(successHtml);
                        
                        // Hide the mark all read button since all are now read
                        $('#mark-all-read').hide();
                        });
                        // Update notification count in header if it exists
                        if ($('#notification-count').length) {
                            $('#notification-count').text('0').hide();
                            $('.nav-item.dropdown.notifications-menu').removeClass('active');
                        }
                    } else {
                        alert('Failed to mark all notifications as read. ' + (response.message || 'Please try again.'));
                    }
                },
                error: function(xhr, status, error) {
                    $btn.prop('disabled', false).html('<i class="far fa-check-circle mr-1"></i> Mark All as Read');
                    console.error('Error marking all notifications as read:', error);
                    alert('An error occurred while marking all notifications as read. Please try again.');
                }
            });
        }
    });
    
    // Initial load of all notifications
    loadAllNotifications();
});
function formatNotificationText(text) {
    if (!text) return '';
    return text.replace(
        /(https?:\/\/[^\s]+)/g, 
        '<a href="$1" target="_blank" class="notification-link">$1</a>'
    );
}
</script>

<?php include 'common/footer.php'; ?>
