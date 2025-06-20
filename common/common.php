<!-- restrict user to directly go to any page -->
<?php
function isUserLoggedIn() {
    // Start session if it hasn't been started yet
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
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
// Start session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        $access_rights[2][] = "change-password";
        $access_rights[2][] = "proposed-work-edit";
        $access_rights[2][] = "proposed-work-add";
        $access_rights[2][] = "proposed-work-list";
        $access_rights[2][] = "view-all-notifications";
        $access_rights[2][] = "license-edit";
        $access_rights[2][] = "license-list";

    $access_rights[3] = array();
    $access_rights[3][] = "dashboard-user";
    $access_rights[3][] = "edit-profile";
    $access_rights[3][] = "edit-project-task";
    $access_rights[3][] = "profile-edit";
    $access_rights[3][] = "profile";
    $access_rights[3][] = "project-task-list";
    $access_rights[3][] = "add-project-task";
    $access_rights[3][] = "projects";
    $access_rights[3][] = "view-project";
    $access_rights[3][] = "change-password";
    $access_rights[3][] = "view-all-notifications";

    $access_rights[4] = array();
    $access_rights[4][] = "dashboard-user";
    $access_rights[4][] = "edit-profile";
    $access_rights[4][] = "edit-project-task";
    $access_rights[4][] = "profile-edit";
    $access_rights[4][] = "profile";
    $access_rights[4][] = "project-task-list";
    $access_rights[4][] = "projects"; 
    $access_rights[4][] = "view-project";
    $access_rights[4][] = "change-password";    
    $access_rights[4][] = "view-all-notifications";

    if (in_array($page, $access_rights[$_SESSION['emp_role_id']])) {
        return true;
    }
    return false;
    
}


function getProjectDetails($project_id) {
    // Initialize project data

    $project = null;



    // API URL for fetching project details

    $api_url = API_URL . "project-edit";



    // Prepare API request data

    $request_data = json_encode([

        'access_token' => $_SESSION['access_token'],

        'project_id' => $project_id

    ]);



    // Set up cURL request

    $ch = curl_init($api_url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for testing

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL host verification for testing

    curl_setopt($ch, CURLOPT_HTTPHEADER, [

        'Content-Type: application/json',

        'Content-Length: ' . strlen($request_data)

    ]);



    // Execute the request

    $response = curl_exec($ch);

    $error = curl_error($ch);

    curl_close($ch);



    // Process the response

    if ($error) {

        echo '<div class="alert alert-danger">Error fetching project details: ' . $error . '</div>';

    } else {

        $result = json_decode($response, true);

        

        if (isset($result['is_successful']) && $result['is_successful'] === '1' && isset($result['data'])) {

            $project = $result['data'];

        } else {

            echo '<div class="alert alert-danger">Failed to retrieve project details: ' . 

                (isset($result['errors']) ? $result['errors'] : 'Unknown error') . '</div>';

        }

    }

    return $project;
}
?>
