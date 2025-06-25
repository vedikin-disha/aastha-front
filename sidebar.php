<?php
// Define which menu items are visible for each role
$menu_visibility = [
    // Role 1 (Admin) has access to everything
    1 => ['dashboard', 'project', 'project-task', 'report', 'configuration', 'project-template', 'user', 'circle', 'division', 'subdivision', 'taluka', 'department', 'profile', 'change-password', 'proposed-work', 'license', 'whatsapp-message'],
    
    // Role 2 (Data Entry Operator) - as specified
    2 => ['dashboard', 'project', 'project-task', 'user', 'circle', 'division', 'subdivision', 'taluka', 'department', 'profile', 'change-password', 'proposed-work', 'license', 'whatsapp-message'],
    
    // Roles 3 & 4 (Regular Users)
    3 => ['dashboard', 'project', 'project-task', 'profile', 'change-password'],
    4 => ['dashboard', 'project', 'project-task', 'profile', 'change-password']
];

// Get current user role
$user_role = isset($_SESSION['emp_role_id']) ? $_SESSION['emp_role_id'] : 0;

// If role doesn't exist in our mapping, default to most restricted view
if (!isset($menu_visibility[$user_role])) {
    $user_role = 3; // Default to regular user view
}

// Get the allowed menu items for this role
$allowed_menus = $menu_visibility[$user_role];

// Function to check if a menu should be visible
function shouldShowMenu($menu_id, $allowed_menus) {
    return in_array($menu_id, $allowed_menus);
}

// Get current page
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Function to check if current page is related to a menu item
function isActivePage($menu_item, $current_page) {
    // Special cases for add/edit pages
    if (($menu_item === 'circle' && in_array($current_page, ['circle-add', 'circle-edit'])) ||
        ($menu_item === 'user' && in_array($current_page, ['user-add', 'user-edit'])) ||
        ($menu_item === 'project' && in_array($current_page, ['projects', 'add-project', 'edit-project', "view-project"])) ||
        ($menu_item === 'project-template' && in_array($current_page, ['project-template-add', 'project-template-edit'])) ||
        ($menu_item === 'proposed-work' && in_array($current_page, ['proposed-work-add', 'proposed-work-edit'])) || 
        ($menu_item === 'report' && in_array($current_page, ['department-wise-summary', 'report-job-wise-status', 'dept-wise-report', 'employee-wise-report'])) ||
        ($menu_item === 'department' && in_array($current_page, ['department-add', 'department-edit'])) ||
        ($menu_item === 'license' && in_array($current_page, ['license-add', 'license-edit'])) ||
        ($menu_item === 'whatsapp-message' && in_array($current_page, ['whatsapp-message-add', 'whatsapp-message-edit'])) ||
        ($menu_item === 'change-password' && in_array($current_page, ['change-password']))
        ) {
        return true;
    }
    // Regular list pages
    if (strpos($current_page, $menu_item . '-list') === 0) {
        return true;
    }
    // Exact match for dashboard
    if ($menu_item === 'dashboard' && $current_page === 'index') {
        return true;
    }
    return false;
}
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->

    <a href="" class="brand-link">

      <span class="brand-text font-weight-light">Admin Panel</span>

    </a>

<style>
    .nav-link.active {
        background-color: #30b8b9 !important;
        color: white !important;
    }
</style>

    <!-- Sidebar -->

    <div class="sidebar">

      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

          <?php if (shouldShowMenu('dashboard', $allowed_menus)): ?>
          <li class="nav-item" id="dashboard">
            <a href="#" class="nav-link <?php echo isActivePage('dashboard', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-tachometer-alt"></i>

              <p>Dashboard</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('project', $allowed_menus)): ?>
          <li class="nav-item" id="project">
            <a href="projects" class="nav-link <?php echo isActivePage('project', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-project-diagram"></i>

              <p>Project</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('project-task', $allowed_menus)): ?>
          <li class="nav-item" id="project-task">
            <a href="project-task-list" class="nav-link <?php echo isActivePage('project-task', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-tasks"></i>

              <p>Project Task</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('report', $allowed_menus)): ?>
            <li class="nav-item">
            <a href="report/" class="nav-link <?php echo isActivePage('report', $current_page) ? 'active' : ''; ?>" id="report">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Report
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="department-wise-summary" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dept. Wise Summary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="report-job-wise-status" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Job Wise Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="dept-wise-report" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dept Wise Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="employee-wise-report" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Wise Report</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="report-job-wise-status" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Job Wise Status</p>
                </a>
              </li> -->
            </ul>
          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('configuration', $allowed_menus) || shouldShowMenu('project-template', $allowed_menus) || $user_role == 2): ?>
          <li class="nav-header">Configuration</li>
          
          <?php if (shouldShowMenu('configuration', $allowed_menus) && $user_role != 2): ?>
          <li class="nav-item" id="configuration">
            <a href="config-list" class="nav-link <?php echo isActivePage('configuration', $current_page) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Configuration</p>
            </a>
          </li>
          <?php endif; ?>
          <?php endif; ?>



          <?php if (shouldShowMenu('project-template', $allowed_menus)): ?>
          <li class="nav-item" id="project-template">
            <a href="project-template-list" class="nav-link <?php echo isActivePage('project-template', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-copy"></i>

              <p>Project Template</p>

            </a>

          </li>
          <?php endif; ?>


          <?php if (shouldShowMenu('user', $allowed_menus)): ?>
          <li class="nav-item" id="user">
            <a href="user-list" class="nav-link <?php echo isActivePage('user', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-users"></i>

              <p>User</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('department', $allowed_menus)): ?>
          <li class="nav-item" id="department">
            <a href="department-list" class="nav-link <?php echo isActivePage('department', $current_page) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-building"></i>
              <p>Department</p>
            </a>
          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('proposed-work', $allowed_menus)): ?>
          <li class="nav-item" id="proposedWorkNav">
            <a href="proposed-work-list" class="nav-link <?php echo isActivePage('proposed-work', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-clipboard-list"></i>

              <p>Proposed Work</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('circle', $allowed_menus)): ?>
          <li class="nav-item" id="circle">
            <a href="circle-list" class="nav-link <?php echo isActivePage('circle', $current_page) ? 'active' : ''; ?>">

              <i class="nav-icon fas fa-circle-notch"></i>

              <p>Circle</p>

            </a>

          </li>
          <?php endif; ?>

       

          <?php if (shouldShowMenu('division', $allowed_menus)): ?>
          <li class="nav-item" id="division">

            <a href="division-list" class="nav-link">

              <i class="nav-icon fas fa-building"></i>

              <p>Division</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('subdivision', $allowed_menus)): ?>
          <li class="nav-item" id="subdivision">

            <a href="subdivision-list" class="nav-link">

              <i class="nav-icon fas fa-code-branch"></i>

              <p>Subdivision</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('taluka', $allowed_menus)): ?>
          <li class="nav-item" id="taluka">

            <a href="taluka-list" class="nav-link">

              <i class="nav-icon fas fa-map-marker-alt"></i>

              <p>Taluka</p>

            </a>

          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('license', $allowed_menus)): ?>
          <li class="nav-item" id="license">
            <a href="license-list" class="nav-link <?php echo isActivePage('license', $current_page) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-id-card"></i>
              <p>License</p>
            </a>
          </li>
          <?php endif; ?>

          <?php if (shouldShowMenu('whatsapp-message', $allowed_menus)): ?>
          <li class="nav-item" id="whatsapp-message">
            <a href="whatsapp-message-list" class="nav-link <?php echo isActivePage('whatsapp-message', $current_page) ? 'active' : ''; ?>">
              <i class="nav-icon fab fa-whatsapp"></i>
              <p>WhatsApp Message</p>
            </a>
          </li>
          <?php endif; ?>



          <li class="nav-header">User</li>

          <?php if (shouldShowMenu('profile', $allowed_menus)): ?>
          <li class="nav-item" id="profile">

            <a href="profile" class="nav-link">

              <i class="nav-icon fas fa-user"></i>

              <p>Profile</p>

            </a>

          </li>
          <?php endif; ?>

          <li class="nav-item" id="change-password">
            <a href="change-password" class="nav-link <?php echo isActivePage('change-password', $current_page) ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-key"></i>
              <p>Change Password</p>
            </a>
          </li>

          <li class="nav-item" id="logout">

            <a href="logout" class="nav-link">

              <i class="nav-icon fas fa-sign-out-alt"></i>

              <p>Logout</p>

            </a>

          </li>

        </ul>

      </nav>

    </div>

  </aside>