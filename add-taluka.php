{% extends 'base.html' %}
{% load static %}

{% block title %}Add Taluka{% endblock %}

{% block content %}

<!-- Select2 CSS -->
<link href="css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap Theme -->
<link href="css/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add Taluka</h3>
  </div>
  <div class="card-body">
    <!-- datatable data -->
  </div>
</div>

<!-- Select2 JS -->
<script src="js/select2.full.min.js"></script>
<script src="js/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  // Initialize Select2 for all dropdowns
  $('#id_circle').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#talukaForm')
  });
  
  $('#id_division').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#talukaForm')
  }).prop('disabled', true);
  
  $('#id_sub').select2({
    theme: 'bootstrap-5',
    width: '80%',
    dropdownParent: $('#talukaForm')
  }).prop('disabled', true);
  
  // Handle circle change event
  $('#id_circle').on('change', function() {
    var circleId = $(this).val();
    var divisionSelect = $('#id_division');
    var subSelect = $('#id_sub');
    
    // Clear and disable division and subdivision dropdowns
    divisionSelect.empty().prop('disabled', true).trigger('change');
    subSelect.empty().prop('disabled', true).trigger('change');
    
    if (circleId) {
      // Fetch divisions for selected circle
      $.ajax({
        url: "get_divisions",
        data: {circle_id: circleId},
        dataType: 'json',
        success: function(data) {
          // Populate division dropdown
          divisionSelect.empty();
          divisionSelect.append($('<option></option>').val('').text('Select Division'));
          
          $.each(data, function(index, item) {
            divisionSelect.append(
              $('<option></option>').val(item.division_id).text(item.division_name)
            );
          });
          
          // Enable division dropdown after populating
          divisionSelect.prop('disabled', false).trigger('change');
        },
        error: function(xhr, status, error) {
          console.error('Error fetching divisions:', error);
        }
      });
    }
  });
  
  // Handle division change event
  $('#id_division').on('change', function() {
    var divisionId = $(this).val();
    var subSelect = $('#id_sub');
    
    // Clear and disable subdivision dropdown
    subSelect.empty().prop('disabled', true).trigger('change');
    
    if (divisionId) {
      // Fetch subdivisions for selected division
      $.ajax({
        url: 'get_subdivisions',
        data: {division_id: divisionId},
        dataType: 'json',
        success: function(data) {
          // Populate subdivision dropdown
          subSelect.empty();
          subSelect.append($('<option></option>').val('').text('Select Subdivision'));
          
          $.each(data, function(index, item) {
            subSelect.append(
              $('<option></option>').val(item.sub_id).text(item.sub_name)
            );
          });
          
          // Enable subdivision dropdown after populating
          subSelect.prop('disabled', false).trigger('change');
        },
        error: function(xhr, status, error) {
          console.error('Error fetching subdivisions:', error);
        }
      });
    }
  });
  
  // Add active class to navigation
  $('#taluka a').addClass('active nav-link');
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
