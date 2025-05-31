<?php include 'common/header.php'; ?>

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add Taluka</h3>
  </div>
  <div class="card-body">
  <form method="post" id="talukaForm">
      
      <!-- Circle Selection -->
      <div class="form-group mb-3">
        <label for="id_circle" class="form-label">Select Circle</label>
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

      <!-- Division Selection -->
      <div class="form-group mb-3">
        <label for="id_division" class="form-label">Select Division</label>
        <div class="input-group">
          <select name="division_id" id="id_division" class="form-control select2">
            <option value="">Select Division</option>
            <!-- Division options will be populated via API based on selected circle -->
          </select>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-building"></i></span>
          </div>
        </div>
      </div>

      <!-- Subdivision Selection -->
      <div class="form-group mb-3">
        <label for="id_subdivision" class="form-label">Select Subdivision</label>
        <div class="input-group">
          <select name="sub_id" id="id_subdivision" class="form-control select2">
            <option value="">Select Subdivision</option>
            <!-- Subdivision options will be populated via API based on selected division -->
          </select>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-code-branch"></i></span>
          </div>
        </div>
      </div>

      <!-- Taluka Name -->
      <div class="form-group mb-3">
        <label for="id_taluka_name" class="form-label">Taluka Name</label>
        <div class="input-group">
          <input type="text" name="taluka_name" id="id_taluka_name" class="form-control">
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
          </div>
        </div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="taluka-list" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  // Initialize Select2 for all dropdowns
  $('#id_circle, #id_division, #id_subdivision').select2({
    theme: 'bootstrap-5',
    width: '100%',
    dropdownParent: $('#talukaForm')
  });
  
  // Initially disable division and subdivision dropdowns
  $('#id_division, #id_subdivision').prop('disabled', true);
  
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
    var subdivisionSelect = $('#id_subdivision');
    
    // Clear and disable division and subdivision dropdowns
    divisionSelect.empty().prop('disabled', true).trigger('change');
    divisionSelect.append($('<option></option>').val('').text('Select Division'));
    
    subdivisionSelect.empty().prop('disabled', true).trigger('change');
    subdivisionSelect.append($('<option></option>').val('').text('Select Subdivision'));
    
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
  
  // Handle division change event
  $('#id_division').on('change', function() {
    var divisionId = $(this).val();
    var subdivisionSelect = $('#id_subdivision');
    
    // Clear and disable subdivision dropdown
    subdivisionSelect.empty().prop('disabled', true).trigger('change');
    subdivisionSelect.append($('<option></option>').val('').text('Select Subdivision'));
    
    if (divisionId) {
      // Fetch subdivisions for selected division
      $.ajax({
        url: '<?php echo API_URL; ?>subdivision',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          division_id: divisionId,
          access_token: "<?php echo $_SESSION['access_token']; ?>"
        }),
        success: function(response) {
          // Populate subdivision dropdown
          $.each(response, function(index, subdivision) {
            subdivisionSelect.append(new Option(subdivision.subdivision_name, subdivision.sub_id));
          });
          
          // Enable subdivision dropdown after populating
          subdivisionSelect.prop('disabled', false).trigger('change');
        },
        error: function(response) {
          showToast('Failed to load subdivisions', false);
        }
      });
    }
  });
  
  // Add active class to navigation
  $('#taluka a').addClass('active nav-link');
  
  // Handle form submission
  $('#talukaForm').submit(function(e) {
    e.preventDefault();
    var taluka_name = $('#id_taluka_name').val();
    var sub_id = $('#id_subdivision').val();
    var division_id = $('#id_division').val();
    var circle_id = $('#id_circle').val();
    
    if (!taluka_name) {
      showToast('Please enter taluka name', false);
      return;
    }
    
    if (!sub_id) {
      showToast('Please select a subdivision', false);
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
      url: '<?php echo API_URL; ?>add-taluka',
      type: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        taluka_name: taluka_name,
        sub_id: sub_id,
        division_id: division_id,
        circle_id: circle_id,
        access_token: "<?php echo $_SESSION['access_token']; ?>"
      }),
      success: function(response) {
        // console.log('Add taluka response:', response);
        
        if (response.is_successful === "1") {
          showToast(response.success_message || 'Taluka added successfully');
          window.location.href = "<?php echo BASE_URL; ?>taluka-list";
        } else {
          let errorMsg = 'Failed to add taluka';
          
          if (response.errors && Object.keys(response.errors).length > 0) {
            errorMsg = Object.values(response.errors).flat().join(', ');
          }
          
          showToast(errorMsg, false);
        }
      },
      error: function(response) {
        // console.error('Add taluka error:', response);
        showToast('Taluka with this name already exists', false);
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
