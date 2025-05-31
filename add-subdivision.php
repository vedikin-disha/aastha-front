{% extends 'base.html' %}
{% load static %}

{% block title %}Add Subdivision{% endblock %}
{% block content %}

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add Subdivision</h3>
  </div>
  <div class="card-body">
    <!-- data table data -->
  </div>
  <div class="mt-3">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="subdivision_list.php" class="btn btn-secondary">Cancel</a>
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
    width: '80%',
    dropdownParent: $('#subdivisionForm')
  });
  
  $('#id_division').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#subdivisionForm')
  }).prop('disabled', true); // Initially disable division dropdown
  
  // Handle circle change event
  $('#id_circle').on('change', function() {
    var circleId = $(this).val();
    var divisionSelect = $('#id_division');
    
    // Clear and disable division dropdown
    divisionSelect.empty().prop('disabled', true).trigger('change');
    
    if (circleId) {
      // Fetch divisions for selected circle
      $.get('{% url "get_divisions" %}', {circle_id: circleId}, function(data) {
        // Populate division dropdown
        divisionSelect.empty();
        divisionSelect.append($('<option></option>').val('').text('Select Division'));
        
        $.each(data, function(index, item) {
          divisionSelect.append(
            $('<option></option>').val(item.division_id).html(item.division_name)
          );
        });
        
        // Enable division dropdown after populating
        divisionSelect.prop('disabled', false).trigger('change');
      });
    }
  });
  
  // Add active class to navigation
  $('#subdivision a').addClass('active nav-link');
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