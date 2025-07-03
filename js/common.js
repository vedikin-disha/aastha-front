function showToast(message, isSuccess=true) {
    $(document).Toasts('create', {
      class: isSuccess ? 'bg-success' : 'bg-danger',
      title: isSuccess ? 'Success' : 'Error',
      position: 'bottomRight',
      body: message,
      autohide: true,
      delay: 6000
    });
}

function showSuccessPopupAndRedirect(message, redirectUrl) {
    // Create modal HTML
    const modalHtml = `
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>${message}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="okButton">OK</button>
                </div>
            </div>
        </div>
    </div>`;
    
    // Add modal to body
    $('body').append(modalHtml);
    
    // Show modal
    $('#successModal').modal('show');
    
    // Handle OK button click
    $('#okButton').on('click', function() {
        $('#successModal').modal('hide');
        if (redirectUrl) {
            // Open in new tab
            window.open(redirectUrl, '_blank');
        }
        // Remove modal from DOM after hiding
        $('#successModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    });
}
// $("docuemnt").ready(function(){
//     showToast("Hello World", true);
// });