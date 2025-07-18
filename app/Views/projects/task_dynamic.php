<!-- Modern Dynamic Task Page - Enhanced UI -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #3b3b3b; letter-spacing: -1px; margin-bottom: 2.5rem; font-size:2.4rem; text-align:center;"><?= esc($template['name'] ?? 'Tasks') ?></h2>
        <div style="background: #fff; border-radius: 2rem; box-shadow: 0 16px 48px rgba(102,126,234,0.13); border: none; padding: 2.5rem 2.5rem 2rem 2.5rem; margin-bottom:2rem;">
            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered" id="dynamicTaskTable" style="background: #fff; border-radius: 1.2rem; overflow: visible; box-shadow: 0 2px 12px rgba(102,126,234,0.07); margin-bottom:0; min-width:1100px;">
                    <thead class="table-light" style="background: linear-gradient(90deg, #e9ecef 0%, #f8fafc 100%);">
                        <tr>
                            <?php if (empty($fields)): ?>
                                <th class="text-center text-muted" colspan="100%">No template fields found.</th>
                            <?php else: ?>
                                <?php foreach ($fields as $field): ?>
                                    <th style="font-family:'Poppins',sans-serif;font-weight:600;font-size:1.08rem;color:#667eea; padding:1.1rem 1rem; border-bottom:2px solid #e2e8f0; background:#f8fafc;"><?= esc($field) ?></th>
                                <?php endforeach; ?>
                                <th style="width:56px; text-align:center; background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                                    <button id="addRowBtn" type="button" class="btn btn-sm btn-outline-primary" title="Add Row" style="border-radius:50%;box-shadow:0 2px 8px rgba(102,126,234,0.12);width:40px;height:40px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-plus"></i></button>
                                </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fields)): ?>
                            <tr><td class="text-center text-muted" colspan="100%">No template fields available.</td></tr>
                        <?php elseif (empty($tasks)): ?>
                            <!-- No tasks: show nothing, allow adding rows -->
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr data-task-id="<?= esc($task['id']) ?>" class="task-row" style="transition:box-shadow 0.3s,background 0.3s;">
                                    <?php foreach ($fields as $idx => $field): ?>
                                        <?php if ($field === 'Last Modified'): ?>
                                            <td style="background:#f8fafc;color:#6b7280; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"><?= esc($task['date_modified'] ?? '') ?></td>
                                        <?php else: ?>
                                            <td contenteditable="true" class="editable-cell" data-field="<?= esc($field) ?>" style="background:#fff;transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"><?= esc($task[$field] ?? '') ?></td>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <td class="text-center align-middle" style="width:40px;">
                                        <button type="button" class="btn btn-sm btn-link text-danger delete-row-btn" title="Delete Row" style="padding:0;border-radius:50%;background:#f8d7da;width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>
                                    </td>
                                    <td class="text-center drag-handle" style="cursor:move;width:32px;">
                                        <i class="fas fa-grip-vertical" style="color:#667eea;font-size:1.2rem;"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
// --- Patch: Always send project_id and template_code in AJAX ---
const projectId = "<?= esc($project_id ?? $template['project_id'] ?? $project['id'] ?? '') ?>";
const templateCode = "<?= esc($template['code'] ?? '') ?>";

// Row hover effect for modern look
$('#dynamicTaskTable tbody').on('mouseenter', 'tr.task-row', function() {
    $(this).css({
        'box-shadow': '0 12px 32px rgba(102,126,234,0.13)',
        'background': 'linear-gradient(90deg,#e9ecef 0%,#f8fafc 100%)'
    });
});
$('#dynamicTaskTable tbody').on('mouseleave', 'tr.task-row', function() {
    $(this).css({
        'box-shadow': 'none',
        'background': '#fff'
    });
});
// Editable cell focus effect
$('#dynamicTaskTable tbody').on('focus', 'td.editable-cell', function() {
    $(this).css('background', '#e0e7ff');
});
$('#dynamicTaskTable tbody').on('blur', 'td.editable-cell', function() {
    $(this).css('background', '#fff');
    const cell = $(this);
    const row = cell.closest('tr');
    let taskId = row.data('task-id');
    const data = {};
    row.find('td.editable-cell').each(function() {
        data[$(this).data('field')] = $(this).text();
    });
    // Always send project_id and template_code
    data['project_id'] = projectId;
    data['template_code'] = templateCode;
    // If no taskId, create new task
    if (!taskId) {
        $.ajax({
            url: '<?= base_url('projects/save_task') ?>',
            method: 'POST',
            data: data,
            success: function(res) {
                if (res.success && res.task_id) {
                    row.attr('data-task-id', res.task_id);
                }
            }
        });
    } else {
        $.ajax({
            url: '<?= base_url('projects/save_task') ?>',
            method: 'POST',
            data: { id: taskId, ...data },
            success: function(res) {
                // No SweetAlert
            }
        });
    }
});

// Add new row functionality
$('#addRowBtn').on('click', function() {
    const table = $('#dynamicTaskTable tbody');
    const lastRow = table.find('tr.task-row:last');
    let firstColValue = '';
    if (lastRow.length) {
        firstColValue = lastRow.find('td:first').text() || '';
    }
    let rowHtml = '<tr class="task-row" style="transition:box-shadow 0.3s,background 0.3s;">';
    <?php foreach ($fields as $idx => $field): ?>
        if (<?= $idx ?> === 0) {
            rowHtml += '<td contenteditable="true" class="editable-cell" data-field="<?= esc($field) ?>" style="background:#fff;transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;">'+firstColValue+'</td>';
        } else if ('<?= esc($field) ?>' === 'Last Modified') {
            rowHtml += '<td style="background:#f8fafc;color:#6b7280; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        } else {
            rowHtml += '<td contenteditable="true" class="editable-cell" data-field="<?= esc($field) ?>" style="background:#fff;transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        }
    <?php endforeach; ?>
    rowHtml += '<td class="text-center align-middle" style="width:40px;"><button type="button" class="btn btn-sm btn-link text-danger delete-row-btn" title="Delete Row" style="padding:0;border-radius:50%;background:#f8d7da;width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button></td>';
    rowHtml += '<td class="text-center drag-handle" style="cursor:move;width:32px;"><i class="fas fa-grip-vertical" style="color:#667eea;font-size:1.2rem;"></i></td>';
    rowHtml += '</tr>';
    table.append(rowHtml);
});

// Delete row functionality
$('#dynamicTaskTable').on('click', '.delete-row-btn', function() {
    const row = $(this).closest('tr');
    const taskId = row.data('task-id');
    if (taskId) {
        $.ajax({
            url: '<?= base_url('projects/delete_task') ?>',
            method: 'POST',
            data: { id: taskId },
            success: function(res) {
                if (res.success) {
                    row.remove();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: res.message || 'Could not delete task.'
                    });
                }
            }
        });
    } else {
        row.remove(); // Just remove if not saved in DB
    }
});

// Make rows sortable
new Sortable(document.querySelector('#dynamicTaskTable tbody'), {
    handle: '.drag-handle',
    animation: 150,
    ghostClass: 'bg-light',
    onEnd: function(evt) {
        // Optionally, send new order to backend
    }
});
</script>
