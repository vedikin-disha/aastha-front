
<!-- ---attachment Modal --- -->

<div class="modal fade" id="attachmentsModal" tabindex="-1" role="dialog" aria-labelledby="attachmentsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="attachmentForm" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="attachmentsModalLabel">Add Attachment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="attachment_name">Attachment Name</label>
            <input type="text" class="form-control" id="attachment_name" name="attachment_name" required />
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
          </div>

          <div class="form-group">
            <label for="attachment">Choose File</label>
            <input type="file" class="form-control-file" id="attachment" name="attachment" required accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" />
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$("document").ready(function() {
    $('#attachmentForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this); // Create FormData from the form
        formData.append('access_token', '<?php echo $_SESSION['access_token']; ?>');
        formData.append('project_id', '<?php echo $project_id; ?>');
        formData.append('attachment', $('#attachment')[0].files[0]);

        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...');

        $.ajax({
            url: '<?php echo API_URL; ?>attachment-add',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.is_successful === "1") {
                    $('#attachmentsModal').modal('hide');
                    $('#attachmentForm')[0].reset();
                    loadProjectAttachments();
                    $(document).Toasts('create', {

    class: 'bg-success',

    title: 'Success',

    body: response.success_message || "Attachment uploaded successfully!",

    autohide: true,

    delay: 3000

    }); 

                } else {
                    $(document).Toasts('create', {

    class: 'bg-danger',

    title: 'Error',

    body: response.errors || "Upload failed. Please try again.",

    autohide: true,

    delay: 3000

    }); 
                }
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', error);
                alert("Something went wrong while uploading the attachment. Please try again.");
            },
            complete: function() {
                // Re-enable the button
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });


    loadProjectAttachments();
    function loadProjectAttachments() {
        $.ajax({
            url: '<?php echo API_URL; ?>project-attachments',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                access_token: '<?php echo $_SESSION['access_token']; ?>',
                project_id: '<?php echo $project_id; ?>'
            }),
            success: function(response) {
                try {
                    if (response.is_successful === "1" && response.success_message) {
                        const attachments = response.success_message.attachments || [];
                        const totalAttachments = response.success_message.total_attachments || 0;

                        // Clear existing table rows
                        $('#attachments-table-body').empty();

                        if (attachments.length === 0) {
                            $('#attachments-table-body').html('<tr><td colspan="5">No attachments found.</td></tr>');
                            return;
                        }

                        // Helper to choose icon based on extension
                        function iconByExt(path){
                            const ext = path.split('.').pop().toLowerCase().split('?')[0];
                            if(['pdf'].includes(ext)) return 'fas fa-file-pdf text-danger';
                            if(['doc','docx'].includes(ext)) return 'fas fa-file-word text-primary';
                            if(['xls','xlsx','csv'].includes(ext)) return 'fas fa-file-excel text-success';
                            if(['png','jpg','jpeg','gif','bmp','webp'].includes(ext)) return 'fas fa-file-image text-info';
                            return 'fas fa-file text-secondary';
                        }

                        // Create and append each attachment as a table row
                        attachments.forEach(function(attachment) {
                            const icon = iconByExt(attachment.file_path);
                            const attachmentHtml = `
        <tr>
            <td>${attachment.attachment_name}</td>
            <td>${attachment.description || ""}</td>
            <td>
                <a href="${attachment.file_path}" target="_blank"><i class="${icon} fa-2x"></i></a>
            </td>
            <td>
            <button class="btn btn-danger btn-sm" onclick="deleteAttachment(${attachment.attachment_id})"><i class="fas fa-trash"> </i> Delete </button>
            </td>
        </tr>
    `;
                            $('#attachments-table-body').append(attachmentHtml);
                        });

                        $('#attachmentsCount').text(totalAttachments);
                    } else {
                        console.error('Failed to load attachments:', response.errors || 'Unknown error');
                    }
                } catch (error) {
                    console.error('Error processing attachments:', error);
                }
            }
        });
    }

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
                    loadProjectAttachments(); // Reload the table
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