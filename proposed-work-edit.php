<?php 
include 'common/header.php';

// Get and decode the proposed_work_id from URL parameter
$encoded_id = isset($_GET['id']) ? $_GET['id'] : '';
$proposed_work_id = '';

if ($encoded_id) {
    $proposed_work_id = base64_decode($encoded_id);
    if (!$proposed_work_id || !is_numeric($proposed_work_id)) {
        echo "<script>showToast('Invalid work ID', false); setTimeout(() => { window.location.href = 'proposed-work-list'; }, 2000);</script>";
        exit();
    }
} else {
    echo "<script>showToast('No work ID provided', false); setTimeout(() => { window.location.href = 'proposed-work-list'; }, 2000);</script>";
    exit();
}
?>

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Edit Proposed Work</h3>
  </div>
  <div class="card-body">
    <form method="post" id="proposedWorkForm">
      <div class="form-group mb-3">
        <label for="work_name" class="form-label">Proposed Work Name</label>
        <div class="input-group">
          <input type="text" name="work_name" id="work_name" class="form-control" required>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-tasks"></i></span>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9; border: 1px solid #30b8b9;">Update</button>
        <a href="proposed-work-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<style>
  .form-control {
    height: 38px;
  }
  textarea.form-control {
    height: auto;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
</style>

<script>
  // Add active class to navigation
  $('#proposedWorkNav a').addClass('active');
  $('#proposedWorkNav a').addClass('nav-link');
</script>

<script>
  $(document).ready(function() {
    // Get the decoded ID from PHP
    const proposedWorkId = '<?php echo $proposed_work_id; ?>';
    
    if (!proposedWorkId) {
      showToast('Invalid work ID', false);
      setTimeout(() => { window.location.href = 'proposed-work-list'; }, 2000);
      return;
    }
    
    $.ajax({
      url: '<?php echo API_URL; ?>proposed-work-edit',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        proposed_work_id: proposedWorkId, 
        access_token: "<?php echo $_SESSION['access_token']; ?>"
      }),
      success: function(response) {
        // Handle the response structure from the API
        if (response && response.data) {
          // If response has data object
          $('#work_name').val(response.data.proposed_work_name);
        } else if (response && response.is_successful === '1' && response.data) {
          // Alternative response format check
          $('#work_name').val(response.data.proposed_work_name);
        } else {
          console.error('Unexpected response format:', response);
          showToast('Failed to load work data: Unexpected response format', false);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error loading work data:', error, xhr.responseText);
        showToast('Error loading work data. Please try again.', false);
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('#proposedWorkForm').submit(function(e) {
      e.preventDefault();
      var work_name = $('#work_name').val();
      
      if (!work_name) {
        showToast('Please enter work name', false);
        return;
      }
      
      $.ajax({
        url: '<?php echo API_URL; ?>proposed-work-update',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          proposed_work_name: work_name, 
          access_token: "<?php echo $_SESSION['access_token']; ?>",
          proposed_work_id: proposedWorkId
        }),
        success: function(response) {
          showToast('Work updated successfully');
          window.location.href = "<?php echo BASE_URL; ?>proposed-work-list";
        },
        error: function(response) {
          showToast('Failed to update work', false);
          console.error('API error:', response);
        }
      });
    });
  });
</script>

<?php include 'common/footer.php'; ?>
