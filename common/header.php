<?php
// Start the session at the beginning before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config/constant.php';
include 'common/common.php';
isUserLoggedIn();


// get requested url. remove .php, querystring and any other # things from URL. need only page name
$request_uri = $_SERVER['REQUEST_URI'];
$request_uri = explode('/', $request_uri);
$request_uri = end($request_uri);
$request_uri = explode('.', $request_uri);
$request_uri = $request_uri[0];
// remove string from ? 
$request_uri = explode('?', $request_uri);
$request_uri = $request_uri[0];
// echo $request_uri;
// exit();

if (!isUserHasRights($request_uri)) {
  echo "<h3 style='color: red; text-align: center;'>Access Denied: You do not have permission to view this page.</h3>";
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- favicon -->
  <link rel="icon" href="assets/favicon.ico">

  <title>Aastha-PMS</title>



  <!-- Google Font: Source Sans Pro -->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

  <!-- Font Awesome -->

  <link rel="stylesheet" href="css/all.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="css/ionicons.min.css">

  <!-- Tempusdominus Bootstrap 4 -->

  <link rel="stylesheet" href="css/tempusdominus-bootstrap-4.min.css">

  <!-- iCheck -->

  <link rel="stylesheet" href="css/icheck-bootstrap.min.css">

  <!-- JQVMap -->

  <link rel="stylesheet" href="css/jqvmap.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="css/adminlte.min2167.css">

  <!-- overlayScrollbars -->

  <link rel="stylesheet" href="css/OverlayScrollbars.min.css">

  <!-- Daterange picker -->

  <link rel="stylesheet" href="css/daterangepicker.css">

  <!-- Summernote -->

  <link rel="stylesheet" href="css/summernote-bs4.min.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">



  <!-- DataTables CSS jQuery -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize AdminLTE sidebar toggle
      $('[data-widget="pushmenu"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
      });
    });
  </script>

  <script>
      $(document).ready(function() {
        // Wait a moment for AdminLTE to initialize
        setTimeout(function() {
          // Remove AdminLTE's default behaviors
          $(document).off('click', '[data-widget="treeview"] .nav-link');
          $('.nav-sidebar').off('click');
          
          // Get the report menu item specifically
          var $reportMenuItem = $('a#report').closest('.nav-item');
          var $reportLink = $reportMenuItem.find('> .nav-link');
          var $reportSubmenu = $reportMenuItem.find('> .nav-treeview');
          
          // Don't hide the submenu initially if it's already open
          if (!$reportMenuItem.hasClass('menu-open')) {
            $reportSubmenu.hide();
          }
          
          // Clear any existing click handlers
          $reportLink.off('click');
          
          // Add our custom click handler with animation
          $reportLink.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle submenu visibility with animation
            if ($reportMenuItem.hasClass('menu-open')) {
              // Close submenu with animation
              $reportMenuItem.removeClass('menu-open');
              $reportSubmenu.slideUp(400);
            } else {
              // Open submenu with animation
              $reportMenuItem.addClass('menu-open');
              $reportSubmenu.slideDown(400);
            }
          });
          
          // Prevent clicks on submenu items from closing the submenu
          $reportSubmenu.find('.nav-link').on('click', function(e) {
            e.stopPropagation();
          });
        }, 200); // Small delay to ensure AdminLTE is fully initialized
      });
  </script>



  <!-- Optional custom styles -->

  <style>
    
    .content-wrapper {

      min-height: calc(100vh - 57px - 70px); /* navbar + footer */

    }

    .page-item.active .page-link {
        background-color: #30b8b9 !important;
        border-color: #30b8b9 !important;
        color: white !important;
    }
    .page-link {
        color: #30b8b9;
    }
    .page-link:hover {
        color: #30b8b9;
    }

    .card-primary.card-outline{
      border-top: 3px solid #30b8b9;
    }

    .badge-in-progress {
      background-color: #30b8b9 !important;
    }

    .new-pms-ap {
      width: 100% !important;
      overflow-x: auto !important;
      overflow-y: hidden !important;
    }
    
    /* Search form styles */
    .search-form .form-control {
      border-radius: 4px 0 0 4px !important;
    }
    .search-form .btn-navbar {
      border: 1px solid #ced4da;
      border-left: none;
      border-radius: 0 4px 4px 0;
      background-color: #f8f9fa;
    }
    .search-form .btn-navbar:hover {
      background-color: #e9ecef;
    }
    

  </style>

</head>



<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">



  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Search Form -->
    <div class="search-form ml-3" style="width: 400px;">
      <form id="globalSearchForm" class="form-inline">
        <div class="input-group input-group-sm" style="width: 100%;">
          <input class="form-control form-control-navbar" type="search" id="globalSearchInput" placeholder="Search projects..." aria-label="Search" required>
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" id="notificationDropdown">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="notification-count">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width: 426px; height: 700px; overflow:scroll ;">
          <div class="dropdown-header d-flex justify-content-between align-items-center">
            <span>Notifications</span>
            <button type="button" class="close" onclick="event.stopPropagation(); $('.dropdown-menu').removeClass('show');" aria-label="Close" style="font-size: 1.5rem; line-height: 1; outline: none;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="dropdown-divider"></div>
          <div id="notification-list">
            <div class="dropdown-item text-center py-3">
              <i class="fas fa-spinner fa-spin"></i> Loading...
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="d-flex justify-content-between px-3 py-2">
            <a href="#" class="btn btn-sm btn-outline-danger" id="clear-all-notifications">
              <i class="fas fa-trash-alt mr-1"></i> Clear All
            </a>
            <a href="view-all-notifications" class="btn btn-sm btn-primary">
              <i class="fas fa-list mr-1"></i> View All
            </a>
          </div>
        </div>
      </li>
    </ul>

  </nav>

  <!-- Notification Handling JavaScript -->
<script>
$(document).ready(function() {
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Handle notification dropdown show
  $('#notificationDropdown').on('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    // Show loading state
    $('#notification-list').html('<div class="dropdown-item text-center py-3"><i class="fas fa-spinner fa-spin"></i> Loading notifications...</div>');
    
    // Fetch notifications
    $.ajax({
      url: '<?php echo API_URL; ?>notification-list',
      type: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        access_token: '<?php echo isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ""; ?>'
      }),
      contentType: 'application/json',
      success: function(response) {
        if (response.is_successful === '1' && response.data && response.data.length > 0) {
          let notificationsHtml = '';
          
          // Get only the 10 most recent notifications
          const recentNotifications = response.data.slice(0, 10);
          
          // Format each notification
          recentNotifications.forEach(function(notification) {
            const date = new Date(notification.created_at);
            const today = new Date();
            let formattedDate;
            
            // Check if the notification is from today
            if (date.getDate() === today.getDate() && 
                date.getMonth() === today.getMonth() && 
                date.getFullYear() === today.getFullYear()) {
                // Show only time for today's notifications
                formattedDate = date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            } else {
                // Show full date for older notifications
                formattedDate = date.toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }
            
            // Set default avatar if no profile image
            const userImage = notification.creator_profile || 'assets/img/default-avatar.png';
            const userName = notification.creator_name || 'User';
            
            notificationsHtml += `
              <div class="dropdown-item position-relative">
                <button type="button" class="close position-absolute" style="right: 16px;" data-notification-id="${notification.notification_id}">
                  <span aria-hidden="true"><i class="far fa-trash-alt"style="font-size: 15px; color: red;"></i></span>
                </button>
                <div class="media">
                  <img src="${userImage}" alt="User Image" class="img-size-50 mr-3 img-circle" onerror="this.onerror=null; this.src='assets/img/default-avatar.png';">
                  <div class="media-body pr-4">
                    <div class="d-flex justify-content-between align-items-start">
                      <h6 class="mt-0 mb-1" style="font-size: 0.9rem; font-weight: 600;">${userName}</h6>
                      <small class="text-muted">${formattedDate}</small>
                    </div>
                    <p class="text-sm mb-0" style="font-size: 0.85rem;">${notification.notification}</p>
                  </div>
                </div>
              </div>
              <div class="dropdown-divider"></div>`;
          });
          
          // Update notification count
          $('#notification-count').text(response.data.length);
          
          // If no notifications
          if (response.data.length === 0) {
            notificationsHtml = '<div class="dropdown-item">No notifications found</div>';
          }
          
          $('#notification-list').html(notificationsHtml);
        } else {
          $('#notification-list').html('<div class="dropdown-item">No notifications found</div>');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching notifications:', error);
        $('#notification-list').html('<div class="dropdown-item text-danger">Error loading notifications</div>');
      }
    });
    
    // Toggle dropdown
    $(this).next('.dropdown-menu').toggleClass('show');
  });
  
  // Close dropdown when clicking outside
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.dropdown').length) {
      $('.dropdown-menu').removeClass('show');
    }
  });
  
  // Handle Clear All Notifications
  $(document).on('click', '#clear-all-notifications', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    if (confirm('Are you sure you want to clear all notifications?')) {
      const $clearBtn = $(this);
      $clearBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Clearing...');
      
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
            // Clear the notification list
            $('#notification-list').html('<div class="dropdown-item text-success">All notifications have been cleared</div>');
            // Update notification count to 0
            $('#notification-count').text('0');
            // Show success message
            showToast('success', 'Success', 'All notifications have been cleared');
          } else {
            showToast('error', 'Error', response.message || 'Failed to clear notifications');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error clearing notifications:', error);
          showToast('error', 'Error', 'Failed to clear notifications');
        },
        complete: function() {
          $clearBtn.prop('disabled', false).html('<i class="fas fa-trash-alt mr-1"></i> Clear All');
        }
      });
    }
  });
  
  // Handle global search form submission
  $('#globalSearchForm').on('submit', function(e) {
    e.preventDefault();
    const searchTerm = $('#globalSearchInput').val().trim();
    if (searchTerm) {
      window.location.href = 'report-job-wise-status.php?project_name=' + encodeURIComponent(searchTerm);
    }
  });

  // Handle notification close button click
  $(document).on('click', '.close[data-notification-id]', function(e) {
    e.stopPropagation();
    const $btn = $(this);
    const notificationId = $btn.data('notification-id');
    
    // Make the API call to mark notification as read
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
          // Remove the notification from the UI
          $btn.closest('.dropdown-item').next('.dropdown-divider').remove();
          $btn.closest('.dropdown-item').remove();
          
          // Update the notification count
          const currentCount = parseInt($('#notification-count').text()) || 0;
          const newCount = Math.max(0, currentCount - 1);
          
          if (newCount > 0) {
            $('#notification-count').text(newCount);
          } else {
            $('#notification-count').hide();
            $('#notification-list').html('<div class="dropdown-item">No notifications found</div>');
          }
        } else {
          console.error('Failed to mark notification as read');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error marking notification as read:', error);
      }
    });
  });
  
  // Load notification count on page load
  function updateNotificationCount() {
    $.ajax({
      url: '<?php echo API_URL; ?>notification-list',
      type: 'POST',
      dataType: 'json',
      data: JSON.stringify({
        access_token: '<?php echo isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : ""; ?>'
      }),
      contentType: 'application/json',
      success: function(response) {
        if (response.is_successful === '1' && response.data && response.data.length > 0) {
          $('#notification-count').text(response.data.length).show();
        } else {
          $('#notification-count').hide();
        }
      },
      error: function() {
        console.error('Error updating notification count');
      }
    });
  }
  
  // Initial count update
  updateNotificationCount();
  
  // Update count every 5 minutes
  setInterval(updateNotificationCount, 5 * 60 * 1000);
});
</script>

  



  <!-- Main Sidebar -->

<?php include 'sidebar.php'; ?>





  <div class="content-wrapper">

    <div class="content pt-3">

      <div class="container-fluid">

      

