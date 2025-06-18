<?php include 'common/header.php'; ?>

<!-- SweetAlert2 CSS -->

<link rel="stylesheet" href="css/sweetalert2.min.css">

<!-- SweetAlert2 JS -->

<script src="js/sweetalert2.all.min.js"></script>



<div class="card card-primary card-outline">

  <div class="card-header">

    <h3 class="card-title">Project Templates</h3>

    <div class="card-tools">

      <a href="add_project_template" class="btn btn-success btn-sm">

        <i class="fas fa-plus"></i> Add Template

      </a>

    </div>

  </div>



  <div class="card-body">





    <table id="templateTable" class="table table-bordered table-hover">

      <thead>

        <tr>

          <th>Project Type</th>

          <th>Actions</th>

        </tr>

      </thead>

      <tbody>

            <!-- datatable data -->

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

            // Get CSRF token

            const csrftoken = getCookie('csrftoken');

            

            // Send DELETE request

            fetch(`/project-templates/delete/${templateId}/`, {

                method: 'POST',

                headers: {

                    'X-CSRFToken': csrftoken,

                    'Content-Type': 'application/json'

                }

            })

            .then(response => {

                if (response.ok) {

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

                        'Failed to delete project template.',

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



<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">



<script src="js/jquery-3.5.1.js"></script>

<script src="js/jquery.dataTables.min.js"></script>

<script src="js/dataTables.bootstrap4.min.js"></script>



<script>

  $(document).ready(function() {

    $('#templateTable').DataTable({

      "paging": true,

      "lengthChange": false,

      "searching": true,

      "ordering": true,

      "autoWidth": false,

      "responsive": true,

      "language": {

        "search": "_INPUT_",

        "searchPlaceholder": "Search..."

      }

    });

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

        body: '{{ message }}',

        autohide: true,

        delay: 3000

      });

  });

</script>

<?php include 'common/footer.php'; ?>