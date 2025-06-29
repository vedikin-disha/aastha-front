<?php include 'common/header.php'; ?>



<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<!-- <link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" /> -->

<style>
/* Custom Select2 highlight and selected color */
.select2-container--bootstrap-5 .select2-results__option--highlighted {
  background-color:rgb(236, 236, 236) !important;
}
.select2-container--bootstrap-5 .select2-results__option--highlighted.select2-results__option--selectable,
.select2-container--bootstrap-5 .select2-results__option--selected {
  background-color: #30b8b9 !important;
  color: #fff !important;
}

/* Style for selected item in the dropdown */
.select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
  background-color: #30b8b9 !important;
  color: #fff !important;
}
</style>

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Add Division</h3>
  </div>
  <div class="card-body">
    
  <form method="post" id="divisionForm">
      
      <!-- Circle Selection -->
      <div class="form-group mb-3">
        <label for="id_circle" class="form-label">Circle</label>
        <div class="input-group">
          <select name="circle_id" id="id_circle" class="form-control select2">
            <option value="">Select Circle</option>
            <!-- Circle options will be populated via API -->
          </select>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>

      <!-- Division Name -->
      <div class="form-group mb-3">
        <label for="id_division_name" class="form-label">Division</label>
        <div class="input-group">
          <input type="text" name="division_name" id="id_division_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-building"></i></span>
          </div>
          
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border: 1px solid #30b8b9;">Save</button>
        <a href="division-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>

  </div>

</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script>
$(document).ready(function() {
  // Call /circle API to get all circles
  $.ajax({
    url: '<?php echo API_URL; ?>circle',
    type: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({access_token: "<?php echo $_SESSION['access_token']; ?>"}),
    success: function(response) {
      // Populate circle dropdown
      var circleSelect = $('#id_circle');
      
      // Clear existing options except the first one
      circleSelect.find('option:not(:first)').remove();
      
      // Add new options from API response
      $.each(response, function(index, circle) {
        circleSelect.append(new Option(circle.circle_name, circle.circle_id));
      });
    },
    error: function(response) {
      showToast('Failed to load circles', false);
    }
  });

  // Initialize Select2
  $('#id_circle').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#divisionForm')
  });
  
  // Add active class to navigation
  $('#division a').addClass('active nav-link');
  
  // Handle form submission
  $('#divisionForm').submit(function(e) {
    e.preventDefault();
    var division_name = $('#id_division_name').val();
    var circle_id = $('#id_circle').val();
    
    if (!division_name) {
      showToast('Please enter division name', false);
      return;
    }
    
    if (!circle_id) {
      showToast('Please select a circle', false);
      return;
    }
    
    $.ajax({
      url: '<?php echo API_URL; ?>add-division',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        division_name: division_name,
        circle_id: circle_id,
        access_token: "<?php echo $_SESSION['access_token']; ?>"
      }),
      success: function(response) {
        // if (response.status == 201) {
          showToast('Division added successfully');
          window.location.href = "<?php echo BASE_URL; ?>division-list";
        // } else {
        //   showToast(response.message, false);
        // }
      },
      error: function(response) {
        showToast('Division with this name already exists', false);
      }
    });
  });
});
</script>

<style>
  .form-control {
    height: 38px;
  }
  .alert-danger {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
  }
</style>

<?php include 'common/footer.php'; ?> 