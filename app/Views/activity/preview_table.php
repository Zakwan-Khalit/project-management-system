<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .preview-container {
            margin: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .preview-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .preview-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }
        .btn-back {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
        }
        .btn-back:hover {
            background: #4b5563;
            color: white;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header d-flex justify-content-between align-items-center">
            <h1 class="preview-title mb-0">
                <i class="fas fa-eye me-2"></i>Table Preview
            </h1>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
        
        <div id="tableContainer">
            <div class="text-center text-muted py-5">
                <i class="fas fa-spinner fa-spin me-2"></i>Loading table...
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        const templateId = "<?= esc($template_id) ?>";
        const projectId = "<?= esc($project_id) ?>";
        
        if (!templateId || !projectId) {
            $('#tableContainer').html('<div class="alert alert-danger">Missing template or project ID</div>');
            return;
        }
        
        $.ajax({
            url: "<?= base_url('activity/get_preview_table_data') ?>",
            method: 'GET',
            data: { template_id: templateId, project_id: projectId },
            dataType: 'json',
            success: function(response) {
                if (!response.success) {
                    $('#tableContainer').html('<div class="alert alert-danger">' + (response.message || 'Failed to load table') + '</div>');
                    return;
                }
                
                if (!response.fields || response.fields.length === 0) {
                    $('#tableContainer').html('<div class="alert alert-info">No table structure configured</div>');
                    return;
                }
                
                // Build table HTML
                let tableHtml = '<div class="table-responsive">';
                tableHtml += '<table class="table table-striped table-hover">';
                
                // Headers
                tableHtml += '<thead class="table-light">';
                tableHtml += '<tr>';
                response.fields.forEach(function(fieldId) {
                    const fieldName = response.headerMap[fieldId] || 'Unknown';
                    tableHtml += '<th>' + fieldName + '</th>';
                });
                tableHtml += '</tr>';
                tableHtml += '</thead>';
                
                // Body
                tableHtml += '<tbody>';
                if (!response.tasks || response.tasks.length === 0) {
                    tableHtml += '<tr><td colspan="' + response.fields.length + '" class="text-center text-muted">No data available</td></tr>';
                } else {
                    response.tasks.forEach(function(task) {
                        const taskData = task.data || {};
                        tableHtml += '<tr>';
                        response.fields.forEach(function(fieldId) {
                            const fieldName = response.headerMap[fieldId] || 'Unknown';
                            let cellValue = taskData[fieldId] || '';
                            
                            if (fieldName === 'Image' && task.images && task.images.length > 0) {
                                cellValue = task.images.length + ' image(s)';
                            }
                            
                            tableHtml += '<td>' + cellValue + '</td>';
                        });
                        tableHtml += '</tr>';
                    });
                }
                tableHtml += '</tbody>';
                tableHtml += '</table>';
                tableHtml += '</div>';
                
                $('#tableContainer').html(tableHtml);
            },
            error: function(xhr, status, error) {
                $('#tableContainer').html('<div class="alert alert-danger">Error loading table: ' + error + '</div>');
            }
        });
    });
    </script>
</body>
</html>
