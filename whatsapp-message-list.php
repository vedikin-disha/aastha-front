<?php include 'common/header.php'; ?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header" style="border-top: 3px solid #30b8b9; border-bottom: 1px solid rgba(0, 0, 0, .125);">
      <div class="d-flex justify-content-between align-items-center">
        <h3 class="card-title">Whatsapp Message List</h3>
        <?php if ($_SESSION['emp_role_id'] == 1 || $_SESSION['emp_role_id'] == 2): ?>
          <div class="card-tools">
            <a href="whatsapp-message-add" class="btn btn-success">
              <i class="fas fa-plus"></i> Add New Message
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card-body">
      <!-- Table -->
      <div class="new-pms-ap">
        <table id="whatsappMessageTable" class="table table-bordered table-hover dataTable">
          <thead>
            <tr>
              <th>Employee</th>
              <th>Message</th>
              <th>Status</th>
              <th>Scheduled Time</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include 'common/footer.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap4.min.js"></script>

<script>

  
$(document).ready(function () {
    // Format function outside the DataTable initialization
    function formatScheduleTime(gmtTimeString) {
        if (!gmtTimeString) return '';
        
        try {
            const date = new Date(gmtTimeString);
            if (isNaN(date.getTime())) return gmtTimeString; // Return original if invalid date
            
            const day = String(date.getUTCDate()).padStart(2, '0');
            const month = String(date.getUTCMonth() + 1).padStart(2, '0');
            const year = date.getUTCFullYear();
            let hours = date.getUTCHours();
            const minutes = String(date.getUTCMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert 0 to 12 for 12-hour format
            
            return `${day}/${month}/${year}, ${hours}:${minutes} ${ampm}`;
        } catch (e) {
            console.error('Error formatting date:', e);
            return gmtTimeString; // Return original string if there's an error
        }
    }

    var table = $('#whatsappMessageTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo API_URL; ?>schedule-message-list",
            "type": "POST",
            "contentType": "application/json",
            "data": function (d) {
                // Add null checks to prevent errors
                const sortCol = d.columns && d.order && d.order[0] && d.columns[d.order[0].column] 
                    ? d.columns[d.order[0].column].data 
                    : 'emp_name';
                const sortDir = d.order && d.order[0] 
                    ? d.order[0].dir 
                    : 'desc';
                    
                return JSON.stringify({
                    access_token: '<?php echo $_SESSION['access_token']; ?>',
                    sort_column: sortCol,
                    sort_order: sortDir,
                    limit: d.length || 10,
                    page: d.start && d.length ? Math.floor(d.start / d.length) + 1 : 1,
                    search: d.search ? d.search.value || '' : ''
                });
            },
            "dataSrc": function (json) {
                if (json && json.is_successful === "1") {
                    json.recordsTotal = json.data && json.data.meta ? json.data.meta.total : 0;
                    json.recordsFiltered = json.data && json.data.meta ? json.data.meta.total : 0;
                    return json.data && json.data.records ? json.data.records : [];
                } else {
                    const errorMsg = json && json.errors ? json.errors : 'Failed to load scheduled messages';
                    showToast(errorMsg, false);
                    return [];
                }
            },
            "error": function (xhr, error, thrown) {
                console.error('Error loading scheduled messages:', error, thrown);
                showToast('Error loading scheduled messages', false);
            }
        },
        "columns": [
            {
                "data": "emp_name",
                "render": function (data, type, row) {
                    const name = data || 'N/A';
                    const phone = row.phone_number || '';
                    return `${name}<br><small class="text-muted">${phone}</small>`;
                }
            },
            { 
                "data": "message",
                "render": function (data) {
                    return data || '';
                }
            },
            {
                "data": "is_sent",
                "render": function (data) {
                    return data == 1
                        ? '<span class="badge badge-success">Sent</span>'
                        : '<span class="badge badge-warning">Pending</span>';
                }
            },
            {
                "data": "schedule_time",
                "render": function (data) {
                    return data ? formatScheduleTime(data) : '';
                }
            },
            {
                "data": "shed_id",
                "orderable": false,
                "render": function (data) {
                    if (!data) return '';
                    try {
                        return `
                            <div class="btn-group">
                                <a href="whatsapp-message-edit?id=${btoa(String(data))}" 
                                   class="btn btn-sm btn-primary" 
                                   style="background-color: #30b8b9; border: none;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        `;
                    } catch (e) {
                        console.error('Error generating edit link:', e);
                        return '';
                    }
                }
            }
        ],
        "responsive": true,
        "autoWidth": false,
        "order": [[0, 'desc']],
        "pageLength": 10,
        "language": {
            "emptyTable": "No scheduled messages found",
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "zeroRecords": "No matching records found",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)"
        }
    });
});
</script>
