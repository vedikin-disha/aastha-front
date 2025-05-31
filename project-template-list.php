<?php include 'common/header.php'; ?>
<?php
// API call to fetch project templates
$url = API_URL . 'template-listing';
$request_data = [
    'access_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODQyNTU4OCwianRpIjoiMmE0NjIzOTUtY2YwZi00NzY3LTlkYzgtMzA1ZTlkODMzMDZkIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6ImplbnNpLmNoYW5nYW5pQHZlZGlraW4uY29tIiwibmJmIjoxNzQ4NDI1NTg4LCJjc3JmIjoiZTVmOGJlNzgtZTQ0Ny00NjRlLWI5M2EtODljNzY5YmE1MDZkIiwiZXhwIjoxNzQ4NTExOTg4fQ.OZ94UxHkpdiNORfT3EB5sCqeKQi0oH_qgvmec6NJ_9M'
    // Uncomment these if needed:
    // 'circle_id' => 1,
    // 'division_id' => 2,
    // 'sub_division_id' => 3,
    // 'taluka_id' => 4
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// SSL settings
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);

if ($response === false) {
    echo '<div class="alert alert-danger">cURL Error: ' . curl_error($ch) . '</div>';
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Debug information
    echo '<!-- Debug Info: ' . 
         'HTTP Code: ' . $httpCode . 
         ', Response: ' . htmlspecialchars($response) . 
         ' -->';
    
    $templates = [];
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        if ($result && isset($result['is_successful']) && $result['is_successful'] === '1') {
            $templates = $result['data'];
        } else {
            $error = isset($result['errors']) ? $result['errors'] : 'Unknown error occurred';
            echo '<div class="alert alert-danger">API Error: ' . htmlspecialchars($error) . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Server returned code ' . $httpCode . '</div>';
    }
}

curl_close($ch);
?>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="css/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="js/sweetalert2.all.min.js"></script>

<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Project Templates</h3>
    <div class="card-tools">
      <a href="project-template-add.php" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Add Template
      </a>
    </div>
  </div>

  <div class="card-body">
    <table id="templateTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th width="20%">Project Type ID</th>
          <th width="50%">Type Name</th>
          <th width="30%">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php 
      if (!empty($templates) && is_array($templates)): 
        foreach ($templates as $template): 
          if (isset($template['project_type_id']) && isset($template['type_name'])): 
      ?>
        <tr>
          <td><?php echo htmlspecialchars($template['project_type_id']); ?></td>
          <td><?php echo htmlspecialchars($template['type_name']); ?></td>
          <td>
            <a href="project-template-edit?id=<?php echo htmlspecialchars($template['project_type_id']); ?>" class="btn btn-sm btn-primary">
              <i class="fas fa-edit"></i> Edit
            </a>
            <button onclick="confirmDelete('<?php echo htmlspecialchars($template['type_name']); ?>', '<?php echo htmlspecialchars($template['project_type_id']); ?>')" class="btn btn-sm btn-danger">
              <i class="fas fa-trash"></i> Delete
            </button>
          </td>
        </tr>
        <?php 
          endif;
        endforeach; 
      else: 
      ?>
        <tr>
          <td colspan="3" class="text-center">No project templates found.</td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function getCookie(name) {
    let cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

function confirmDelete(templateName, templateId) {
    Swal.fire({
        title: 'Delete Project Template?',
        text: `Are you sure you want to delete "${templateName}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send DELETE request to your API endpoint
            fetch(API_URL + 'template-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    access_token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODQyNTU4OCwianRpIjoiMmE0NjIzOTUtY2YwZi00NzY3LTlkYzgtMzA1ZTlkODMzMDZkIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6ImplbnNpLmNoYW5nYW5pQHZlZGlraW4uY29tIiwibmJmIjoxNzQ4NDI1NTg4LCJjc3JmIjoiZTVmOGJlNzgtZTQ0Ny00NjRlLWI5M2EtODljNzY5YmE1MDZkIiwiZXhwIjoxNzQ4NTExOTg4fQ.OZ94UxHkpdiNORfT3EB5sCqeKQi0oH_qgvmec6NJ_9M',
                    project_type_id: templateId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.is_successful === '1') {
                    // Show success message and reload
                    Swal.fire(
                        'Deleted!',
                        'Project template has been deleted.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    // Show error message
                    Swal.fire(
                        'Error!',
                        data.errors || 'Failed to delete project template.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error!',
                    'An error occurred while deleting the template.',
                    'error'
                );
            });
        }
    });
}
</script>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">

<!-- jQuery -->
<script src="js/jquery-3.5.1.min.js"></script>
<!-- DataTables JS -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    try {
        var table = $('#templateTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search..."
            },
            "columnDefs": [
                { "orderable": false, "targets": 2 } // Disable sorting on Actions column
            ]
        });
        console.log('DataTable initialized successfully');
    } catch (error) {
        console.error('Error initializing DataTable:', error);
    }
});
</script>

<script>
    $('#project-template a').addClass('active');
    $('#project-template a').addClass('nav-link');
</script>


<script>
  $(document).ready(function() {
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Success',
        position: 'bottomRight',
        body: '<?php echo htmlspecialchars($message); ?>',
        autohide: true,
        delay: 3000
      });
  });
</script>
<?php include 'common/footer.php'; ?>