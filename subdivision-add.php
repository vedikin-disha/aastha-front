<?php include 'common/header.php'; ?>

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
    <h3 class="card-title">Add Subdivision</h3>
  </div>
  <div class="card-body">
    <form method="post" id="subdivisionForm">
      
      <!-- Circle Selection -->
      <div class="form-group mb-3">
        <label for="id_circle" class="form-label">Circle</label>
        <div class="input-group flex-nowrap">
          <select name="circle_id" id="id_circle" class="form-control select2">
            <option value="">Select Circle</option>
            <!-- Circle options will be populated via API -->
          </select>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-circle"></i></span>
          </div>
        </div>
      </div>

      <!-- Division Selection -->
      <div class="form-group mb-3">
        <label for="id_division" class="form-label">Division</label>
        <div class="input-group flex-nowrap">
          <select name="division_id" id="id_division" class="form-control select2" disabled>
            <option value="">Select Division</option>
            <!-- Division options will be populated via API based on selected circle -->
          </select>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-building"></i></span>
          </div>
        </div>
      </div>

      <!-- Subdivision Name -->
      <div class="form-group mb-3">
        <label for="id_sub_name" class="form-label">Subdivision</label>
        <div class="input-group">
          <input type="text" name="subdivision_name" id="id_sub_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
          </div>
        </div>
        
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #30b8b9;border: 1px solid #30b8b9;">Save</button>
        <a href="subdivision-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  // Initialize Select2 for both dropdowns
  $('#id_circle').select2({
    theme: 'bootstrap-5',
    width: '100%',
    dropdownParent: $('#subdivisionForm')
  });
  
  $('#id_division').select2({
    theme: 'bootstrap-5',
    width: '100%',
    dropdownParent: $('#subdivisionForm')
  }).prop('disabled', true); // Initially disable division dropdown
  
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

  // Handle circle change event
  $('#id_circle').on('change', function() {
    var circleId = $(this).val();
    var divisionSelect = $('#id_division');
    
    // Clear and disable division dropdown
    divisionSelect.empty().prop('disabled', true).trigger('change');
    divisionSelect.append($('<option></option>').val('').text('Select Division'));
    
    if (circleId) {
      // Fetch divisions for selected circle
      $.ajax({
        url: '<?php echo API_URL; ?>division',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          circle_id: circleId,
          access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
          // Populate division dropdown
          $.each(response, function(index, division) {
            divisionSelect.append(new Option(division.division_name, division.division_id));
          });
          
          // Enable division dropdown after populating
          divisionSelect.prop('disabled', false).trigger('change');
        },
        error: function(response) {
          showToast('Failed to load divisions', false);
        }
      });
    }
  });
  
  // Add active class to navigation
  $('#subdivision a').addClass('active nav-link');
  
  // Handle form submission
  $('#subdivisionForm').submit(function(e) {
    e.preventDefault();
    var subdivision_name = $('#id_sub_name').val();
    var division_id = $('#id_division').val();
    var circle_id = $('#id_circle').val();
    
    if (!subdivision_name) {
      showToast('Please enter subdivision name', false);
      return;
    }
    
    if (!division_id) {
      showToast('Please select a division', false);
      return;
    }
    
    if (!circle_id) {
      showToast('Please select a circle', false);
      return;
    }
    
    $.ajax({
      url: '<?php echo API_URL; ?>add-subdivision',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        sub_name: subdivision_name,
        division_id: division_id,
        circle_id: circle_id,
        access_token: "<?php echo $_SESSION['access_token']; ?>"
      }),
      success: function(response) {
        console.log('Add subdivision response:', response);
        
        if (response.is_successful === "1") {
          showToast(response.success_message || 'Subdivision added successfully');
          window.location.href = "<?php echo BASE_URL; ?>subdivision-list";
        } else {
          let errorMsg = 'Failed to add subdivision';
          
          if (response.errors && Object.keys(response.errors).length > 0) {
            errorMsg = Object.values(response.errors).flat().join(', ');
          }
          
          showToast(errorMsg, false);
        }
      },
      error: function(response) {
        console.error('Add subdivision error:', response);
        showToast('Subdivision with this name already exists', false);
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

<?php include 'common/footer.php'; ?> 