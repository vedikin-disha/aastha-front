<!-- Compliance Modal -->

<div class="modal fade" id="complianceModal" tabindex="-1" role="dialog" aria-labelledby="complianceModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="complianceModalLabel">Add Compliance</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="editor-toolbar mb-2">

                    <button type="button" class="btn btn-sm btn-light modal-answer-toolbar" data-command="bold" title="Bold"><i class="fas fa-bold"></i></button>

                    <button type="button" class="btn btn-sm btn-light modal-answer-toolbar" data-command="italic" title="Italic"><i class="fas fa-italic"></i></button>

                    <button type="button" class="btn btn-sm btn-light modal-answer-toolbar" data-command="underline" title="Underline"><i class="fas fa-underline"></i></button>

                </div>

                <div id="modal-answer-editor" class="form-control" contenteditable="true" style="min-height: 150px; overflow-y: auto;"></div>

                <div id="char-count" class="text-muted mt-2" style="font-size: 0.875rem;">Characters remaining: 10000</div>

                <div id="char-error" class="text-danger mt-2" style="display: none;">Your answer is too long. Maximum 10000 characters allowed.</div>

                <input type="hidden" id="modal-qna-id">

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

                <button type="button" class="btn btn-primary" id="modal-submit-answer">Submit Answer</button>

            </div>

        </div>

    </div>

</div>


<script>

    $("document").ready(function() {

        // Character count validation for modal answer editor

        $('#modal-answer-editor').on('input', function() {

        const maxLength = 10000;

        const currentLength = $(this).text().length;

        const remaining = maxLength - currentLength;



        $('#char-count').text(`Characters remaining: ${remaining}`);



        if (currentLength > maxLength) {

            $('#char-error').show();

            $('#modal-submit-answer').prop('disabled', true);

        } else {

            $('#char-error').hide();

            $('#modal-submit-answer').prop('disabled', false);

        }

        });

    });

</script>