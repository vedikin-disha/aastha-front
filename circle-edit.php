<?php 
include 'common/header.php';

// Get and decode the circle_id from URL parameter
$encoded_id = isset($_GET['id']) ? $_GET['id'] : '';
$circle_id = '';

if ($encoded_id) {
    $circle_id = base64_decode($encoded_id);
    if (!$circle_id || !is_numeric($circle_id)) {
        echo "<script>showToast('Invalid circle ID', false); setTimeout(() => { window.location.href = 'circle-list'; }, 2000);</script>";
        exit();
    }
} else {
    echo "<script>showToast('No circle ID provided', false); setTimeout(() => { window.location.href = 'circle-list'; }, 2000);</script>";
    exit();
}
?>
<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Edit Circle</h3>
  </div>
  <div class="card-body">
  <form method="post" id="circleForm">
      
      <div class="form-group mb-3">
        <label for="circle_name" class="form-label">Circle</label>
        <div class="input-group">
          <input type="text" name="circle_name" id="circle_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>
      <div class="mt-3">
        <button type="submit" class="btn btn-primary"style="background-color: #30b8b9;border: 1px solid #30b8b9;">Save</button>
        <a href="circle-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
   
    // add nav-link active class to id = circle
    $('#circle a').addClass('active');
    $('#circle a').addClass('nav-link');
</script>

<script>
  // call /circle api to get individual circle by id
  $(document).ready(function() {
    $.ajax({
      url: '<?php echo API_URL; ?>circle',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        circle_id: <?php echo $circle_id; ?>, 
        access_token: "<?php echo $_SESSION['access_token']; ?>"
      }),
      success: function(response) {
        // Handle the actual response structure from the API
        if (Array.isArray(response) && response.length > 0) {
          // If response is an array, get the first item
          var circle = response[0];
          $('#circle_name').val(circle.circle_name);
        } else if (response && typeof response === 'object' && response.circle_name) {
          // If response is a direct object
          $('#circle_name').val(response.circle_name);
        } else {
          showToast('Failed to load circle data', false);
        }
      },
      error: function(response) {
        showToast('Error loading circle data', false);
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('#circleForm').submit(function(e) {
      e.preventDefault();
      var circle_name = $('#circle_name').val();
      
      if (!circle_name) {
        showToast('Please enter circle name', false);
        return;
      }
      
      $.ajax({
        url: '<?php echo API_URL; ?>update-circle',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          circle_name: circle_name, 
          access_token: "<?php echo $_SESSION['access_token']; ?>",
          circle_id: <?php echo $circle_id; ?>
        }),
        success: function(response) {
          showToast('Circle updated successfully');
          window.location.href = "<?php echo BASE_URL; ?>circle-list";
        },
        error: function(response) {
          showToast('Failed to update circle', false);
          console.error('API error:', response);
        }
      });
    });
  });
</script>

<?php include 'common/footer.php'; ?>