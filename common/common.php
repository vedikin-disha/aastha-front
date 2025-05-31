<!-- restrict user to directly go to any page -->
<?php
function isUserLoggedIn() {
    if (!isset($_SESSION['access_token'])) {
        header("Location: ".BASE_URL . "login");
        exit();
    }
    // get all javascript sessionstorage data to php session
    /*   sessionStorage.setItem('access_token', response.access_token);
            sessionStorage.setItem('emp_id', response.data.emp_id);
            sessionStorage.setItem('emp_name', response.data.emp_name);
            sessionStorage.setItem('emp_role_id', response.data.emp_role_id);
            sessionStorage.setItem('dept_id', response.data.dept_id);
            sessionStorage.setItem('whatsapp_number', response.data.whatsapp_number);
            sessionStorage.setItem('dept_name', response.data.dept_name);
            sessionStorage.setItem('emp_role_name', response.data.emp_role_name);
            */
            
}

function isUserHasRights($page) {
session_start();
// check key is set or not
if (!isset($_SESSION['emp_role_id'])) {
    header("Location: " . BASE_URL . "login");
    exit();
}
    if ($_SESSION['emp_role_id'] == 1) {
        return true;
    }
   
    $access_rights = array();
    $access_rights[2] = array();  
        $access_rights[2][] = "add-division";
        $access_rights[2][] = "add-project-task";
        $access_rights[2][] = "add-project";
        $access_rights[2][] = "add-subdivision";
        $access_rights[2][] = "add-taluka";
        $access_rights[2][] = "add-user";
        $access_rights[2][] = "circle-add";
        $access_rights[2][] = "circle-edit";
        $access_rights[2][] = "circle-list";
        $access_rights[2][] = "dashboard-admin";
        $access_rights[2][] = "dashboard";
        $access_rights[2][] = "division-add";
        $access_rights[2][] = "division-edit";
        $access_rights[2][] = "division-list";
        $access_rights[2][] = "edit-division";
        $access_rights[2][] = "edit-profile";
        $access_rights[2][] = "edit-project-task";
        $access_rights[2][] = "edit-subdivision";
        $access_rights[2][] = "edit-taluka";
        $access_rights[2][] = "edit-user";
        $access_rights[2][] = "profile-edit";
        $access_rights[2][] = "profile";
        $access_rights[2][] = "project-task-list";
        $access_rights[2][] = "projects"; 
        $access_rights[2][] = "project_type_task";
        $access_rights[2][] = "set_session";
        $access_rights[2][] = "subdivision-add";
        $access_rights[2][] = "subdivision-edit";
        $access_rights[2][] = "subdivision-list";
        $access_rights[2][] = "taluka-add";
        $access_rights[2][] = "taluka-edit";
        $access_rights[2][] = "taluka-list";
        $access_rights[2][] = "user-add";
        $access_rights[2][] = "user-edit";
        $access_rights[2][] = "user-list";
        $access_rights[2][] = "projects";
        $access_rights[2][] = "add-project";
        $access_rights[2][] = "edit-project";
        $access_rights[2][] = "view-project";

    $access_rights[3] = array();
    $access_rights[3][] = "dashboard-user";
    $access_rights[3][] = "edit-profile";
    $access_rights[3][] = "edit-project-task";
    $access_rights[3][] = "profile-edit";
    $access_rights[3][] = "profile";
    $access_rights[3][] = "project-task-list";
    $access_rights[3][] = "projects";

    $access_rights[3][] = "view-project";

    $access_rights[4] = array();
    $access_rights[4][] = "dashboard-user";
    $access_rights[4][] = "edit-profile";
    $access_rights[4][] = "edit-project-task";
    $access_rights[4][] = "profile-edit";
    $access_rights[4][] = "profile";
    $access_rights[4][] = "project-task-list";
    $access_rights[4][] = "projects"; 
    $access_rights[4][] = "view-project";

    if (in_array($page, $access_rights[$_SESSION['emp_role_id']])) {
        return true;
    }
    return false;
    
}
?>
