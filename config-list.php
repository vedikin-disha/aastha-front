<?php 
include 'common/header.php';
if (!defined('API_URL')) {
    include 'config/constant.php';
}

$access_token = '';
if (isset($_SESSION['access_token'])) {
    $access_token = $_SESSION['access_token'];
}
?>

<script>
  const API_URL = '<?php echo API_URL; ?>';
</script>

<div class="card card-primary card-outline">
  <div class="card-header">
    <h3 class="card-title">Configuration</h3>
  </div>
  <div class="card-body">
    <form id="configForm">
      <div id="configContainer">
        <!-- Configuration fields will be loaded here -->
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#configuration a').addClass('active nav-link');
    
    // Load configurations
    $.ajax({
      url: API_URL + 'config-edit',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        access_token: '<?php echo $access_token; ?>'
      }),
      success: function(response) {
        if (response.is_successful === '1') {
          displayConfigs(response.data);
        } else {
          showToast('error', 'Error', response.errors || 'Failed to load configurations');
        }
      },
      error: function() {
        showToast('error', 'Error', 'Failed to load configurations');
      }
    });

    // Handle form submission
    $('#configForm').on('submit', function(e) {
      e.preventDefault();
      
      const configs = [];
      $('.config-input').each(function() {
        configs.push({
          config_id: $(this).data('config-id'),
          config_value: $(this).val()
        });
      });

      $.ajax({
        url: API_URL + 'config-update',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
          access_token: '<?php echo $access_token; ?>',
          configs: configs
        }),
        success: function(response) {
          console.log('Update Response:', response);
          if (response.is_successful === '1' || response.is_successful === true) {
            showToast('success', 'Success', response.success_message || 'Configurations updated successfully');
            displayConfigs(response.data);
          } else {
            console.error('Update Error:', response);
            showToast('error', 'Error', response.errors || response.error || 'Failed to update configurations');
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', {xhr: xhr, status: status, error: error});
          showToast('error', 'Error', 'Failed to update configurations. Please try again.');
        }
      });
    });

    function displayConfigs(data) {
      const container = $('#configContainer');
      container.empty();

      data.forEach(function(group) {
        const section = $('<div class="mb-4"></div>');
        section.append(`<h5 class="mt-4 mb-3">${group.config_type.charAt(0).toUpperCase() + group.config_type.slice(1)} Configuration</h5>`);

        group.config.forEach(function(config) {
          const field = $(`
            <div class="form-group row mb-3">
              <label class="col-sm-3 col-form-label">${config.config_name}</label>
              <div class="col-sm-6">
                <input type="text" class="form-control config-input" 
                  data-config-id="${config.config_id}" 
                  value="${config.config_value}">
              </div>
            </div>
          `);
          section.append(field);
        });

        container.append(section);
      });
    }

    function showToast(type, title, message) {
      $(document).Toasts('create', {
        class: type === 'error' ? 'bg-danger' : 'bg-success',
        title: title,
        position: 'bottomRight',
        body: message,
        autohide: true,
        delay: 3000
      });
    }
  });
</script>
<?php if (isset($messages)): ?>
<script>
  $(document).ready(function() {
    // {% for message in messages %}
      $(document).Toasts('create', {
        class: 'bg-success',
        title: 'Success',
        position: 'bottomRight',
        body: '<?php echo htmlspecialchars($message); ?>',
        autohide: true,
        delay: 3000
      });
    // {% endfor %}
  });
</script>
<?php endif; ?>

<?php include 'common/footer.php'; ?> 