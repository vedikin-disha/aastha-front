<?php
include 'config/constant.php';
include 'common/header.php';

// Display success message if set
if (isset($_SESSION['success_message'])) {
    echo "<script>
        $(document).ready(function() {
            showToast('{$_SESSION['success_message']}', true);
        });
    </script>";
    unset($_SESSION['success_message']);
}
?>

<!-- Debug Info -->
<?php
echo '<!-- Debug: API_URL = ' . API_URL . ' -->';
echo '<!-- Debug: Session token exists = ' . (isset($_SESSION['access_token']) ? 'yes' : 'no') . ' -->';
?>

<script>
const API_URL = '<?php echo API_URL; ?>';
const ACCESS_TOKEN = '<?php echo $_SESSION['access_token']; ?>';
</script>

<!-- SweetAlert2 CSS -->
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.min.css
" rel="stylesheet">

<style>
    @media (max-width:593px) {

        #new-editbtn-ap {
            margin-bottom: 5px;       
        }
    }
</style>
<!-- SweetAlert2 JS -->
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js
"></script>
<!-- Common JS with showToast function -->
<script src="js/common.js"></script>

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
    <div class="new-pms-ap">
        <table id="templateTable" class="table table-bordered table-hover">
        <thead>
            <tr>
            <th width="20%">Project Type ID</th>
            <th width="50%">Type Name</th>
            <th width="30%">Actions</th>
            </tr>
        </thead>
        <tbody>
        <!-- Table data will be populated by JavaScript -->
        </tbody>
        </table>
    </div>
  </div>
</div>

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
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                        
            "language": {
                "search": "Search:",
               
            },
            "columnDefs": [
                { "orderable": false, "targets": 2 } // Disable sorting on Actions column
            ],
            "ajax": {
                "url": API_URL + 'template-listing',
                "type": "POST",
                "contentType": "application/json",
                "processData": false,
                "data": function() {
                    return JSON.stringify({
                        "access_token": ACCESS_TOKEN
                    });
                },
                "error": function(xhr, error, thrown) {
                    showToast('Error loading data: ' + error, false);
                },
                "dataSrc": function(response) {
                    if (response && response.is_successful === '1' && response.data) {
                        return response.data;
                    } else {
                        showToast(response?.errors || 'Failed to fetch templates', false);
                        return [];
                    }
                }
            },
            "order": [[0, "desc"]],
            "columns": [
                { "data": "project_type_id" },
                { 
                    "data": "project_type_name",
                    "type": "string"
                },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        // Encode the project_type_id using base64 for the URL
                        const encodedId = btoa(row.project_type_id);
                        return `
                            <a href="project-template-edit?id=${(encodedId)}" id="new-editbtn-ap" class="btn btn-sm btn-primary" style="background-color: #30b8b9;border:none;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        `;
                    }
                }
            ]
        });
    } catch (error) {
        showToast('Error initializing table: ' + error.message, false);
    }
});

// <button onclick="confirmDelete('${row.project_type_name.replace(/'/g, "\\'")}', '${row.project_type_id}')" class="btn btn-sm btn-danger">
//                                 <i class="fas fa-trash"></i> Delete
//                             </button>
</script>

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

// function confirmDelete(templateName, templateId) {
//     Swal.fire({
//         title: 'Delete Project Template?',
//         text: `Are you sure you want to delete "${templateName}"? This action cannot be undone.`,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#dc3545',
//         cancelButtonColor: '#6c757d',
//         confirmButtonText: 'Yes, delete it!',
//         cancelButtonText: 'Cancel'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             // Send DELETE request to your API endpoint
//             fetch(API_URL + 'template-delete', {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json'
//                 },
//                 body: JSON.stringify({
//                     access_token: getCookie('access_token'),
//                     project_type_id: templateId
//                 })
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.is_successful === '1') {
//                     // Show success message and reload
//                     Swal.fire(
//                         'Deleted!',
//                         'Project template has been deleted.',
//                         'success'
//                     ).then(() => {
//                         window.location.reload();
//                     });
//                 } else {
//                     // Show error message
//                     Swal.fire(
//                         'Error!',
//                         data.errors || 'Failed to delete project template.',
//                         'error'
//                     );
//                 }
//             })
//             .catch(error => {
//                 Swal.fire(
//                     'Error!',
//                     'An error occurred while deleting the template.',
//                     'error'
//                 );
//             });
//         }
//     });
// }
</script>

<script>
    $('#project-template a').addClass('active');
    $('#project-template a').addClass('nav-link');
</script>


<!-- <script>
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
</script> -->
<?php include 'common/footer.php'; ?>