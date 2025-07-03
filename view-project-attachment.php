<div class="d-flex justify-content-between align-items-center">

    <h6 class="m-0 font-weight-bold text-primary" style="color:#30b8b9 !important;">Project Attachments</h6>

    <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 ): ?>

        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#attachmentsModal" style="margin-bottom: 20px;">

            <i class="fas fa-plus"></i> Add New Attachment

        </button>

    <?php endif; ?>

</div>

<table class="table table-striped table-bordered" id="attachments-table">
    <thead class="thead-dark">
        <tr>
            <th>Attachment Name</th>
            <th>Description</th>
            <th>File</th>
        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2 ): ?>
            <th>Action</th>
        <?php endif; ?>
        </tr>
    </thead>
    <tbody id="attachments-table-body">
        <!-- Attachments will be loaded here -->
    </tbody>
</table>

<style>
/* Remove modal backdrop */
.modal-backdrop.show {
    opacity: 0 !important;
}

/* Ensure modal is still clickable */
.modal {
    pointer-events: none;
}

.modal-dialog {
    pointer-events: auto;
}
</style>

<script>
$(document).ready(function() {
    // This will remove the backdrop when modal is shown
    $('#attachmentsModal').on('show.bs.modal', function () {
        $('.modal-backdrop').css('opacity', '0');
    });
    
    // Reset when modal is hidden
    $('#attachmentsModal').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
    });

    

    
    function deleteAttachment(attachmentId) {
        if (!confirm("Are you sure you want to delete this attachment?")) return;

        $.ajax({
            url: '<?php echo API_URL; ?>attachment-delete',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION['access_token']; ?>',
                attachment_id: attachmentId
            }),
            success: function(response) {
                if (response.is_successful === "1") {
                    $(document).Toasts('create', {

                        class: 'bg-success',

                        title: 'Success',

                        body: 'Attachment deleted successfully!',

                        autohide: true,

                        delay: 3000

                    });
                  // reload the [view-project-attachment.php]
                  location.reload();
                } else {
                    $(document).Toasts('create', {

                        class: 'bg-danger',

                        title: 'Error',

                        body: 'Failed to delete attachment: ' + (response.errors || "Unknown error"),

                        autohide: true,

                        delay: 3000

                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error);
                $(document).Toasts('create', {

                    class: 'bg-danger',

                    title: 'Error',

                    body: 'An error occurred while deleting the attachment.',

                    autohide: true,

                    delay: 3000

                });
            }
        });
    }
});
</script>