<!-- Modern Activity Page -->
    
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                </a>
                <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
            </li>
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('activity') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-tasks" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                    Activities
                </a>
                <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
            </li>
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('activity/activity_scope/' . ($_GET['project_id'] ?? '')) ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-sitemap" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                    Project Scope
                </a>
                <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem;">
                <i class="fas fa-cogs" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Activity Management
            </li>
        </ol>
    </nav>
    
    

    <!-- Activity Header -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        
        <!-- Activity Title Section -->
        <div style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <h1 style="margin: 0; font-size: 2rem; font-weight: 700; color: #1f2937; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 0.75rem;" id="taskTableTitle">
                            <i class="fas fa-tasks" style="color: #667eea; font-size: 1.8rem;"></i>
                            <?= esc($template['name'] ?? 'Activity') ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="padding: 2rem;">
            <?php 
            // Ensure $fields is an array and validate header IDs
            $validFields = [];
            if (!empty($fields) && is_array($fields)) {
                foreach ($fields as $headerId) {
                    // Only include valid header IDs (numeric or string that can be used as array key)
                    if (is_scalar($headerId) && isset($headerMap[$headerId])) {
                        $validFields[] = $headerId;
                    }
                }
            }
            $hasValidFields = !empty($validFields);
            ?>
            
            <?php if ($hasValidFields): ?>
                <!-- Export & Preview Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4" style="padding: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                    <div class="d-flex gap-3" id="exportBtnGroupLeft">
                        <button id="exportCsvBtn" class="btn btn-outline-primary" type="button" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                            <i class="fas fa-file-csv me-2"></i>Export CSV
                        </button>
                        <button id="exportExcelBtn" class="btn btn-outline-success" type="button" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </button>
                        <button id="exportPdfBtn" class="btn btn-outline-danger" type="button" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </button>
                        <button id="previewTableBtn" type="button" class="btn btn-outline-info" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: all 0.3s ease;">
                            <i class="fas fa-eye me-2"></i>Preview
                        </button>
                    </div>
                    <div>
                        <button id="tableSettingsBtn" class="btn btn-outline-secondary" type="button" title="Table Settings" style="border-radius: 0.5rem; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-weight: 500; transition: all 0.3s ease;">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>

            <?php endif; ?>
            
            <?php if (!$hasValidFields): ?>
                <!-- Empty state when no table structure exists -->
                <div class="text-center py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 1.5rem; border: 2px dashed #cbd5e1;">
                    <div class="mb-4">
                        <i class="fas fa-table" style="font-size: 4rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                    </div>
                    <h4 style="color: #475569; font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 1rem;">
                        No Table Structure Yet
                    </h4>
                    <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                        This component doesn't have any table headers configured yet. Get started by adding some table columns to organize your tasks and data.
                    </p>
                    <button id="configureTableBtn" class="btn btn-primary btn-lg" style="padding: 0.75rem 2rem; font-size: 1.1rem; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(102,126,234,0.3);">
                        <i class="fas fa-cog me-2"></i>
                        Configure Table Structure
                    </button>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                    <table class="table table-hover mb-0" id="dynamicTaskTable" style="border-radius: 0.75rem; overflow: hidden; min-width: 1100px;">
                        <thead style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            <tr>
                                <?php foreach ($validFields as $headerId): ?>
                                    <?php $columnName = $headerMap[$headerId] ?? null; ?>
                                    <th style="border-bottom: 1px solid #e2e8f0; padding: 1rem; font-weight: 600; color: #374151;"><?= esc($columnName) ?></th>
                                <?php endforeach; ?>
                                <th style="width: 72px; text-align: center; border-bottom: 1px solid #e2e8f0; padding: 1rem; font-weight: 600; color: #374151;">
                                    <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <button id="addRowBtn" type="button" class="btn btn-sm btn-primary" title="Add Row" style="border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(102,126,234,0.2);">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </th>
                                <th style="width: 32px; border-bottom: 1px solid #e2e8f0;"></th>
                            </tr>
                        </thead>
                        </thead>
                        <tbody>
                            <?php if (empty($tasks)): ?>
                                <!-- No tasks: show nothing, allow adding rows -->
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>
                            <?php $taskData = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true); ?>
                            <tr data-task-id="<?= esc($task['id']) ?>" class="task-row" style="transition:box-shadow 0.3s;">
                                <?php foreach ($validFields as $headerId): ?>
                                <?php $field = $headerMap[$headerId] ?? null; ?>
                            <?php if (in_array($field, ['Tester Name','PIC'])): ?>
                                <td class="editable-cell user-dropdown-cell" tabindex="0" data-field-id="<?= esc($headerId) ?>" data-field="<?= esc($field) ?>" style="padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle;"><span class="user-display"><?= esc($taskData[$headerId] ?? '') ?></span></td>
                            <?php elseif (in_array($field, ['Start Date','End Date','Progress','Status'])): ?>
                                <td class="editable-cell" tabindex="0" data-field-id="<?= esc($headerId) ?>" data-field="<?= esc($field) ?>" style="padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle;"><?= esc($taskData[$headerId] ?? '') ?></td>
                            <?php elseif ($field === 'Image'): ?>
                                <td class="image-cell" data-field-id="<?= esc($headerId) ?>" data-field="Image" style="padding: 1rem; border-bottom: 1px solid #f1f5f9; min-width: 110px; max-width: 140px; text-align: center; vertical-align: middle;">
                                    <div class="task-image-list" data-task-id="<?= esc($task['id']) ?>"></div>
                                    <button class="btn btn-link p-0 upload-image-btn" title="Upload Image" data-task-id="<?= esc($task['id']) ?>" style="color: #667eea; font-size: 1.5rem; display: inline-block;"><i class="fas fa-image"></i></button>
                                    <input type="file" accept="image/*" class="d-none image-upload-input" data-task-id="<?= esc($task['id']) ?>" multiple>
                                </td>
                            <?php elseif ($field === 'Last Modified'): ?>
                                <td style="color: #6b7280; padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle;"></td>
                            <?php else: ?>
                                <td contenteditable="true" class="editable-cell" tabindex="0" data-field-id="<?= esc($headerId) ?>" data-field="<?= esc($field) ?>" style="padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle;"><?= esc($taskData[$headerId] ?? '') ?></td>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="text-center align-middle" style="width: 72px; padding: 1rem; border-bottom: 1px solid #f1f5f9;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-row-btn" title="Delete Row" style="border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center drag-handle" style="cursor: move; width: 32px; padding: 1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle;">
                                <i class="fas fa-grip-vertical" style="color: #667eea; font-size: 1.2rem;"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius:1.2rem; max-width:900px; margin:auto;">
            <div class="modal-header" style="border-bottom:0;">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Task Image" style="max-width:100%;max-height:700px;border-radius:0.7rem;box-shadow:0 2px 12px rgba(102,126,234,0.13);margin-bottom:1rem;" />
            </div>
            <div class="modal-footer" style="border-top:0;justify-content:center;">
                <button id="deleteImageBtn" type="button" class="btn btn-danger" data-image-id=""><i class="fas fa-trash-alt me-1"></i>Delete Image</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Add modern styling
$(document).ready(function() {
    // Add hover effects for table rows
    $('#dynamicTaskTable tbody tr').hover(
        function() {
            $(this).css('background-color', '#f8fafc');
        },
        function() {
            $(this).css('background-color', '');
        }
    );
    
    // Add hover effects for buttons
    $('.btn').hover(
        function() {
            $(this).css('transform', 'translateY(-1px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});

$('#exportPdfBtn').on('click', function() {
    if (typeof jsPDF === 'undefined' && !(window.jspdf && window.jspdf.jsPDF)) {
        Swal.fire('PDF Export Not Available', 'jsPDF is not loaded.', 'error');
        return;
    }
    var jsPDFConstructor = window.jspdf && window.jspdf.jsPDF ? window.jspdf.jsPDF : jsPDF;
    if (typeof html2canvas === 'undefined') {
        Swal.fire('PDF Export Not Available', 'html2canvas is not loaded.', 'error');
        return;
    }
    const doc = new jsPDFConstructor('l', 'pt', 'a4');
    const title = $('#taskTableTitle').text();
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(20);
    doc.text(title, 40, 40);
    html2canvas(document.getElementById('dynamicTaskTable')).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pageWidth = doc.internal.pageSize.getWidth();
        const imgProps = doc.getImageProperties(imgData);
        const imgHeight = (imgProps.height * (pageWidth - 80)) / imgProps.width;
        doc.addImage(imgData, 'PNG', 40, 60, pageWidth - 80, imgHeight);
        doc.save(title.replace(/\s+/g, '_') + '_tasks.pdf');
    });
});

// Excel Export (SheetJS, custom extraction for user-display)
$('#exportExcelBtn').on('click', function() {
    if (typeof XLSX === 'undefined') {
        Swal.fire('Excel Export Not Available', 'SheetJS (XLSX) is not loaded.', 'error');
        return;
    }
    // Extract headers
    const headers = [];
    $('#dynamicTaskTable thead th').each(function() {
        if ($(this).find('button').length === 0) {
            headers.push($(this).text().trim());
        }
    });
    // Extract data
    const data = [];
    $('#dynamicTaskTable tbody tr').each(function() {
        const row = [];
        $(this).find('td').each(function() {
            const userDisplay = $(this).find('.user-display');
            if (userDisplay.length) {
                row.push(userDisplay.text().trim());
            } else {
                row.push($(this).text().trim());
            }
        });
        // Remove action/drag columns
        if (row.length > headers.length) row.length = headers.length;
        if (row.length) data.push(row);
    });
    // SheetJS expects an array of objects
    const wsData = [headers, ...data];
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Tasks');
    const title = $('#taskTableTitle').text().replace(/\s+/g, '_');
    XLSX.writeFile(wb, title + '_tasks.xlsx');
});

// CSV Export (custom extraction for user-display)
$('#exportCsvBtn').on('click', function() {
    // Extract headers
    const headers = [];
    $('#dynamicTaskTable thead th').each(function() {
        if ($(this).find('button').length === 0) {
            headers.push($(this).text().trim());
        }
    });
    // Extract data
    const data = [];
    $('#dynamicTaskTable tbody tr').each(function() {
        const row = [];
        $(this).find('td').each(function() {
            const userDisplay = $(this).find('.user-display');
            if (userDisplay.length) {
                row.push(userDisplay.text().trim());
            } else {
                row.push($(this).text().trim());
            }
        });
        // Remove action/drag columns
        if (row.length > headers.length) row.length = headers.length;
        if (row.length) data.push(row);
    });
    // Build CSV
    let csv = '';
    csv += headers.map(h => '"' + h.replace(/"/g, '""') + '"').join(',') + '\n';
    data.forEach(row => {
        csv += row.map(val => '"' + val.replace(/"/g, '""') + '"').join(',') + '\n';
    });
    const title = $('#taskTableTitle').text().replace(/\s+/g, '_');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (window.navigator.msSaveBlob) {
        window.navigator.msSaveBlob(blob, title + '_tasks.csv');
    } else {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = title + '_tasks.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});
// --- Patch: Always send project_id and template_id in AJAX ---
const projectId = "<?= esc($project_id ?? $template['project_id'] ?? $project['id'] ?? '') ?>";
const templateId = "<?= esc($template_id ?? $template['id'] ?? '') ?>";

// --- Preview button AJAX functionality ---
$('#previewTableBtn').on('click', function() {
    const templateId = "<?= esc($template_id ?? $template['id'] ?? '') ?>";
    const projectId = "<?= esc($project_id ?? $template['project_id'] ?? $project['id'] ?? '') ?>";
    
    // Navigate to preview page with parameters
    window.location.href = "<?= base_url('activity/preview_table') ?>?template_id=" + encodeURIComponent(templateId) + "&project_id=" + encodeURIComponent(projectId);
});

// --- User dropdown for Tester Name and PIC ---
let projectUsers = [];
function fetchProjectUsers() {
    return $.ajax({
        url: '<?= base_url('activity/project_users/') ?>' + projectId,
        method: 'GET',
        dataType: 'text', // Accept any response, parse manually
        success: function(res) {
            let json;
            try {
                json = JSON.parse(res);
            } catch (e) {
                // Not JSON, likely session timeout or error page
                console.error('Error parsing project users JSON:', e, res);
                Swal.fire('Session Expired', 'Please log in again. (Project users)', 'error');
                return;
            }
            if (json.success) {
                projectUsers = json.users;
            } else {
                Swal.fire('Error', json.message || 'Failed to load project users.', 'error');
            }
        },
        error: function(xhr, status, error) {
            let msg = xhr.responseText && xhr.responseText.length < 200 ? xhr.responseText : error;
            console.error('Error fetching project users:', msg);
            Swal.fire('Error', 'Failed to load project users.\n' + msg, 'error');
        }
    });
}
$(document).ready(function() {
    fetchProjectUsers();
});

function showUserDropdown(cell, currentValue) {
    const cellOffset = cell.offset();
    const cellWidth = cell.outerWidth();
    const cellHeight = cell.outerHeight();
    let selectHtml = `<select class="cell-overlay-editor form-select form-select-sm" style="position:absolute;z-index:9999;left:${cellOffset.left}px;top:${cellOffset.top}px;width:${cellWidth}px;height:${cellHeight}px;">`;
    selectHtml += `<option value="">-- Select User --</option>`;
    projectUsers.forEach(u => {
        const fullName = u.full_name.trim();
        selectHtml += `<option value="${fullName}"${currentValue === fullName ? ' selected' : ''}>${fullName}</option>`;
    });
    selectHtml += `</select>`;
    const overlay = $(selectHtml);
    $('body').append(overlay);
    overlay.focus();
    overlay.on('blur change', function() {
        let val = this.value;
        cell.find('.user-display').text(val);
        cell.css('background', '');
        $('.cell-overlay-editor').remove();
        cell.trigger('blur');
    });
    overlay.on('keydown', function(ev) {
        if (ev.key === 'Enter' || ev.key === 'Tab') {
            this.blur();
        }
    });
}

// Recolor after sorting
const sortableTableBody = document.querySelector('#dynamicTaskTable tbody');
if (sortableTableBody) {
    new Sortable(sortableTableBody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-light',
        onEnd: function(evt) {
            // Send new task_order to backend
            const task_order = Array.from(document.querySelectorAll('#dynamicTaskTable tbody tr.task-row'))
                .map(row => row.getAttribute('data-task-id'))
                .filter(id => id);
            $.ajax({
                url: '<?= base_url('activity/update_task_order') ?>',
                method: 'POST',
                data: { 
                    task_order: task_order,
                    template_id: templateId,
                    project_id: projectId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        // Optionally show a success message
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Task Order Save Failed',
                            text: res.message || 'Could not save task order.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating task order:', error);
                    Swal.fire('Error', 'Failed to update task order.', 'error');
                }
            });
        }
    });
} else {
    console.log('Sortable table body not found, skipping second Sortable initialization');
}

// Row hover effect for modern look
$('#dynamicTaskTable tbody').on('mouseenter', 'tr.task-row', function() {
    $(this).css('box-shadow', '0 12px 32px rgba(102,126,234,0.13)');
});
$('#dynamicTaskTable tbody').on('mouseleave', 'tr.task-row', function() {
    $(this).css('box-shadow', 'none');
});
// Editable cell focus effect
// --- Refactored editable cell logic for dynamic fields ---
$('#dynamicTaskTable').on('click', 'td.editable-cell', function(e) {
    if (e.target !== this) return;
    $(this).css('background', '');
    const field = $(this).data('field');
    const cell = $(this);
    const cellOffset = cell.offset();
    const cellWidth = cell.outerWidth();
    const cellHeight = cell.outerHeight();
    let overlay;

    function removeOverlay(val, displayText) {
        if (["Tester Name","PIC"].includes(field)) {
            cell.find('.user-display').text(displayText || val);
        } else {
            cell.text(displayText || val);
        }
        cell.css('background', '');
        $('.cell-overlay-editor').remove();
        cell.trigger('blur');
    }

    if (["Tester Name","PIC"].includes(field)) {
        const currentValue = cell.find('.user-display').text().trim() || cell.text().trim();
        showUserDropdown(cell, currentValue);
        return;
    }
    if (field === 'Start Date' || field === 'End Date') {
        const currentValue = cell.text().trim();
        overlay = $(`<input type="date" class="cell-overlay-editor form-control form-control-sm" value="${currentValue}" style="position:absolute;z-index:9999;left:${cellOffset.left}px;top:${cellOffset.top}px;width:${cellWidth}px;height:${cellHeight}px;">`);
        $('body').append(overlay);
        overlay.focus();
        overlay.on('blur', function() {
            removeOverlay(this.value);
        });
        overlay.on('keydown', function(ev) {
            if (ev.key === 'Enter' || ev.key === 'Tab') {
                this.blur();
            }
        });
    } else if (field === 'Progress') {
        // Only allow editing if status is 'In Progress' (1-79%)
        const statusCell = cell.closest('tr').find('td[data-field="Status"]');
        let statusText = statusCell.text().trim();
        if (statusText === 'In Progress (1-79%)' || statusText.toLowerCase().includes('in progress')) {
            let current = parseInt(cell.text().replace('%','').trim(), 10) || 1;
            overlay = $(`<input type="number" min="1" max="79" class="cell-overlay-editor form-control form-control-sm" value="${current}" style="position:absolute;z-index:9999;left:${cellOffset.left}px;top:${cellOffset.top}px;width:${cellWidth}px;height:${cellHeight}px;">`);
            $('body').append(overlay);
            overlay.focus();
            overlay.on('blur', function() {
                let val = Math.max(1, Math.min(79, parseInt(this.value, 10) || 1));
                removeOverlay(val + '%', val + '%');
            });
            overlay.on('keydown', function(ev) {
                if (ev.key === 'Enter' || ev.key === 'Tab') {
                    this.blur();
                }
            });
        } // else: not editable
    } else if (field === 'Status') {
        // New status options and progress mapping
        const statusOptions = [
            { code: 'new', name: 'New (0%)', progress: '0%' },
            { code: 'in_progress', name: 'In Progress (1-79%)', progress: null },
            { code: 'completed', name: 'Completed (80%)', progress: '80%' },
            { code: 'closed', name: 'Closed (100%)', progress: '100%' },
            { code: 'reassigned', name: 'Reassigned (50%)', progress: '50%' }
        ];
        let current = cell.text().trim().toLowerCase().replace(/\s|\(|\)|%/g, '_');
        let selectHtml = `<select class="cell-overlay-editor form-select form-select-sm" style="position:absolute;z-index:9999;left:${cellOffset.left}px;top:${cellOffset.top}px;width:${cellWidth}px;height:${cellHeight}px;">`;
        statusOptions.forEach(opt => {
            selectHtml += `<option value="${opt.code}"${current.includes(opt.code) ? ' selected' : ''}>${opt.name}</option>`;
        });
        selectHtml += `</select>`;
        overlay = $(selectHtml);
        $('body').append(overlay);
        overlay.focus();
        overlay.on('blur change', function() {
            let val = this.value;
            let display = statusOptions.find(opt => opt.code === val)?.name || val;

            const progressCell = cell.closest('tr').find('td[data-field="Progress"]');
            let progressVal = null;

            // If status is 'in_progress', auto-set progress to 1% and make it editable
            if (val === 'in_progress') {
                // Check if progress cell already has a value between 1-79%
                let currentProgress = progressCell.text().replace('%', '').trim();
                let currentNum = parseInt(currentProgress, 10);
                if (isNaN(currentNum) || currentNum < 1 || currentNum > 79) {
                    progressVal = '1%';
                } else {
                    progressVal = currentNum + '%';
                }
                progressCell.text(progressVal);
                progressCell.addClass('editable-cell');
            } else {
                // If not 'in_progress', auto-set progress based on status mapping
                progressVal = statusOptions.find(opt => opt.code === val)?.progress;
                if (progressVal !== null && progressVal !== undefined) {
                    progressCell.text(progressVal);
                    progressCell.removeClass('editable-cell');
                }
            }

            // Always store the progress value for immediate saving
            if (progressVal !== null) {
                const progressFieldId = progressCell.data('field-id');
                if (progressFieldId) {
                    const row = cell.closest('tr');
                    // Trigger a blur event on the progress cell to ensure it gets saved
                    setTimeout(function() {
                        progressCell.trigger('blur');
                    }, 50);
                }
            }

            // Trigger blur only after updating progress
            removeOverlay(val, display);
        });
        overlay.on('keydown', function(ev) {
            if (ev.key === 'Enter' || ev.key === 'Tab') {
                this.blur();
            }
        });
    }
});

$('#dynamicTaskTable').on('blur', 'td.editable-cell', function() {
    $(this).css('background', '');
    const cell = $(this);
    const row = cell.closest('tr');
    let taskId = row.data('task-id');
    // Debug: Log the task ID
    console.log('Saving task. Task ID:', taskId, 'Type:', typeof taskId);
    const data = {};
    let statusValue = null;
    let progressCell = null;
    let progressFieldId = null;
    // First pass: collect values and references
    row.find('td.editable-cell').each(function() {
        const field = $(this).data('field');
        const fieldId = $(this).data('field-id');
        let value;
        if (["Tester Name","PIC"].includes(field)) {
            value = $(this).find('.user-display').text().trim();
        } else if (field === 'Start Date' || field === 'End Date') {
            value = $(this).find('input[type="date"]').val() || $(this).text();
        } else if (field === 'Progress') {
            progressCell = $(this);
            progressFieldId = fieldId;
            let progressValue = $(this).text().trim();
            if (!progressValue.endsWith('%')) {
                progressValue += '%';
            }
            value = progressValue;
        } else if (field === 'Status') {
            statusValue = $(this).text().trim().toLowerCase();
            value = $(this).text().trim();
        } else {
            value = $(this).text().trim();
        }
        data[fieldId] = value;
    });
    
    // Always ensure progress field is included, even if not directly edited
    if (!progressCell) {
        // Find progress cell if it wasn't in the editable cells loop
        const foundProgressCell = row.find('td[data-field="Progress"]');
        if (foundProgressCell.length > 0) {
            progressCell = foundProgressCell;
            progressFieldId = foundProgressCell.data('field-id');
            let progressValue = foundProgressCell.text().trim();
            if (!progressValue.endsWith('%')) {
                progressValue += '%';
            }
            if (progressFieldId) {
                data[progressFieldId] = progressValue;
            }
        }
    }
    // --- Auto-update Progress based on Status ---
    if (progressCell && progressFieldId) {
        let newProgress = null;
        if (statusValue === 'new' || statusValue === 'new_(0%)' || statusValue === 'new (0%)') {
            newProgress = '0%';
        } else if (statusValue === 'in progress' || statusValue === 'in_progress' || statusValue === 'in progress (1-79%)') {
            // Allow manual entry (handled in click handler)
            let manualVal = progressCell.text().replace('%', '').trim();
            let num = parseInt(manualVal, 10);
            if (isNaN(num) || num < 1) num = 1;
            if (num > 79) num = 79;
            newProgress = num + '%';
        } else if (statusValue === 'completed (80%)' || statusValue === 'completed') {
            newProgress = '80%';
        } else if (statusValue === 'closed (100%)' || statusValue === 'closed') {
            newProgress = '100%';
        } else if (statusValue === 'reassigned (50%)' || statusValue === 'reassigned') {
            newProgress = '50%';
        }
        
        // Always ensure progress has a value and percentage sign
        if (newProgress !== null) {
            data[progressFieldId] = newProgress;
            progressCell.text(newProgress);
        } else {
            // Fallback: ensure current progress value is saved with %
            let currentProgress = progressCell.text().trim();
            if (currentProgress && !currentProgress.endsWith('%')) {
                currentProgress += '%';
            }
            if (currentProgress) {
                data[progressFieldId] = currentProgress;
                progressCell.text(currentProgress);
            }
        }
    }
    
    // Final check: ensure progress field is always in data object
    if (progressFieldId && !data.hasOwnProperty(progressFieldId)) {
        let fallbackProgress = progressCell ? progressCell.text().trim() : '';
        if (fallbackProgress && !fallbackProgress.endsWith('%')) {
            fallbackProgress += '%';
        }
        if (fallbackProgress) {
            data[progressFieldId] = fallbackProgress;
        }
    }
    // Restore cell display
    row.find('td.editable-cell').each(function() {
        const field = $(this).data('field');
        const fieldId = $(this).data('field-id');
        if (["Tester Name","PIC"].includes(field)) {
            $(this).find('.user-display').text(data[fieldId]);
        } else if (field === 'Start Date' || field === 'End Date') {
            $(this).text(data[fieldId]);
        } else if (field === 'Progress') {
            $(this).text(data[fieldId]);
        } else if (field === 'Status') {
            $(this).text(data[fieldId]);
        }
    });
    // Always send project_id and template_id
    data['project_id'] = projectId;
    data['template_id'] = templateId;
    if (!taskId) {
        $.ajax({
            url: '<?= base_url('activity/save_task') ?>',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                console.log('Create response:', res);
                if (!res.success) {
                    console.error('Failed to create task:', res.message);
                    Swal.fire('Error', 'Failed to create new task.', 'error');
                } else {
                    if (res.id) row.attr('data-task-id', res.id);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error creating task:', error);
                Swal.fire('Error', 'Failed to create new task.', 'error');
            }
        });
    } else {
        data['id'] = taskId;
        console.log('Updating existing task. Data being sent:', data);
        $.ajax({
            url: '<?= base_url('activity/save_task') ?>',
            method: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                console.log('Update response:', res);
                if (!res.success) {
                    console.error('Failed to update task:', res.message);
                    Swal.fire('Error', 'Failed to update task.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating task:', error);
                Swal.fire('Error', 'Failed to update task.', 'error');
            }
        });
    }
});

// Add new row functionality - only if table structure exists
$('#addRowBtn').on('click', function() {
    const headerIds = <?php echo json_encode($validFields ?? []); ?>;
    if (!headerIds || !Array.isArray(headerIds) || headerIds.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Table Structure',
            text: 'Please configure the table structure first by clicking the settings button.',
            confirmButtonText: 'Configure Now'
        }).then((result) => {
            if (result.isConfirmed) {
                // Trigger the modal opening function
                openTableSettingsModal();
            }
        });
        return;
    }
    
    const table = $('#dynamicTaskTable tbody');
    const lastRow = table.find('tr.task-row:last');
    let firstColValue = '';
    if (lastRow.length) {
        firstColValue = lastRow.find('td:first').text() || '';
    }
    // --- Refactored: Use JS for dynamic field handling ---
    let rowHtml = '<tr class="task-row" style="transition:box-shadow 0.3s;">';
    let allHeaderOptions = <?php echo json_encode($all_headers ?? []); ?>;
    let headerMap = {};
    
    // Safety check for variables
    if (!allHeaderOptions || !Array.isArray(allHeaderOptions)) {
        allHeaderOptions = [];
        console.warn('Header options not available');
        return;
    }
    
    allHeaderOptions.forEach(h => { 
        if (h && h.id && h.column_name) {
            headerMap[h.id] = h.column_name; 
        }
    });
    // For each header, generate the correct cell type
    headerIds.forEach(function(headerId, idx) {
        let field = headerMap[headerId] || '';
        let dataFieldIdAttr = ' data-field-id="'+headerId+'"';
        if (idx === 0) {
            rowHtml += '<td contenteditable="true" class="editable-cell" tabindex="0" data-field="'+field+'"'+dataFieldIdAttr+' style="padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;">'+firstColValue+'</td>';
        } else if (field === 'Last Modified') {
            rowHtml += '<td'+dataFieldIdAttr+' style="color:#6b7280; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        } else if (field === 'Image') {
            rowHtml += '<td class="image-cell" data-field="Image"'+dataFieldIdAttr+' style="padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0; min-width:110px; max-width:140px; text-align:center; vertical-align:middle;">';
            rowHtml += '<div class="task-image-list" data-task-id=""></div>';
            rowHtml += '<button class="btn btn-link p-0 upload-image-btn" title="Upload Image" data-task-id="" style="color:#667eea; font-size:1.5rem; display:inline-block;"><i class="fas fa-image"></i></button>';
            rowHtml += '<input type="file" accept="image/*" class="d-none image-upload-input" data-task-id="" multiple>';
            rowHtml += '</td>';
        } else if (["Start Date","End Date","Progress","Status"].includes(field)) {
            rowHtml += '<td class="editable-cell" tabindex="0" data-field="'+field+'"'+dataFieldIdAttr+' style="padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        } else if (["Tester Name","PIC"].includes(field)) {
            rowHtml += '<td class="editable-cell user-dropdown-cell" tabindex="0" data-field="'+field+'"'+dataFieldIdAttr+'><span class="user-display"></span></td>';
        } else {
            rowHtml += '<td contenteditable="true" class="editable-cell" tabindex="0" data-field="'+field+'"'+dataFieldIdAttr+' style="padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        }
    });
    // Only the trash button in the row
    rowHtml += '<td class="text-center align-middle" style="width:72px;">';
    rowHtml += '<div style="display:flex;align-items:center;justify-content:center;gap:8px;">';
    rowHtml += '<button type="button" class="btn btn-sm btn-link text-danger delete-row-btn" title="Delete Row" style="padding:0;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-trash"></i></button>';
    rowHtml += '</div>';
    rowHtml += '</td>';
    rowHtml += '<td class="text-center drag-handle" style="cursor:move;width:32px;"><i class="fas fa-grip-vertical" style="color:#667eea;font-size:1.2rem;"></i></td>';
    rowHtml += '</tr>';
    table.append(rowHtml);
});

// Delete row functionality with SweetAlert2 confirmation
$('#dynamicTaskTable').on('click', '.delete-row-btn', function() {
    const row = $(this).closest('tr');
    const taskId = row.data('task-id');
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this row?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            if (taskId) {
                $.ajax({
                    url: '<?= base_url('activity/delete_task') ?>',
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
        }
    });
});

// Make rows sortable - only if table exists
const tableBody = document.querySelector('#dynamicTaskTable tbody');
if (tableBody) {
    new Sortable(tableBody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-light',
        onEnd: function(evt) {
            // Send new task_order to backend
            const task_order = Array.from(document.querySelectorAll('#dynamicTaskTable tbody tr.task-row'))
                .map(row => row.getAttribute('data-task-id'))
                .filter(id => id);
            $.ajax({
                url: '<?= base_url('activity/update_task_order') ?>',
                method: 'POST',
                data: { 
                    task_order: task_order,
                    template_id: templateId,
                    project_id: projectId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        // Optionally show a success message
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Task Order Save Failed',
                            text: res.message || 'Could not save task order.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating task order:', error);
                    Swal.fire('Error', 'Failed to update task order.', 'error');
                }
            });
        }
    });
} else {
    console.log('Table body not found, skipping Sortable initialization');
}

function loadTaskImages(taskId, cell) {
    $.ajax({
        url: '<?= base_url('task-images/list/') ?>' + taskId,
        method: 'GET',
        dataType: 'text', // Accept any response, parse manually
        success: function(res) {
            let json;
            try {
                json = JSON.parse(res);
            } catch (e) {
                // Not JSON, likely session timeout or error page
                console.error('Error parsing task images JSON:', e, res);
                // Optionally show a warning for images
                return;
            }
            if (json.success) {
                const list = cell.find('.task-image-list');
                list.empty();
                json.images.forEach(img => {
                    let filename = img.file_address;
                    if (filename && filename.includes('/')) {
                        filename = filename.substring(filename.lastIndexOf('/') + 1);
                    }
                    const imgPath = '<?= base_url('task-images/view/') ?>' + encodeURIComponent(filename);
                    const thumb = $('<img>').attr('src', imgPath)
                        .addClass('img-thumbnail task-thumb me-1 mb-1')
                        .css({width:'48px',height:'48px',objectFit:'cover',cursor:'pointer'})
                        .attr('data-image-id', img.id)
                        .attr('data-image-src', imgPath)
                        .attr('title', img.file_name);
                    list.append(thumb);
                });
            }
        },
        error: function(xhr, status, error) {
            let msg = xhr.responseText && xhr.responseText.length < 200 ? xhr.responseText : error;
            console.error('Error loading task images:', msg);
            // Don't show error to user for images, just log it
        }
    });
}

// Click thumbnail to show modal
$('#dynamicTaskTable').on('click', '.task-thumb', function() {
    const img = $(this);
    $('#modalImage').attr('src', img.data('image-src'));
    $('#deleteImageBtn').attr('data-image-id', img.data('image-id'));
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
});

$('#deleteImageBtn').off('click').on('click', function() {
    const imageId = $(this).attr('data-image-id');
    if (!imageId) return;
    // Find the cell and taskId before deleting
    const thumb = $('.task-thumb[data-image-id="'+imageId+'"]').first();
    const cell = thumb.closest('.image-cell');
    const taskId = cell.closest('tr').data('task-id');
    $.ajax({
        url: '<?= base_url('task-images/delete/') ?>' + imageId,
        method: 'POST',
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                const modalEl = document.getElementById('imageModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            // Only reload images for the affected cell/task
            if (taskId && cell.length) {
                loadTaskImages(taskId, cell);
            }
        } else if (res.message && res.message.toLowerCase().includes('not found')) {
            // Suppress error if image is already gone
            const modalEl = document.getElementById('imageModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        } else {
            Swal.fire('Delete Failed', res.message || 'Could not delete image.', 'error');
        }
    },
    error: function(xhr, status, error) {
        console.error('Error deleting image:', error);
        Swal.fire('Error', 'Failed to delete image.', 'error');
    }
    });
});

// On page load, load images for all image cells
$(document).ready(function() {
    $('.image-cell').each(function() {
        const cell = $(this);
        const taskId = cell.closest('tr').data('task-id');
        if (taskId) loadTaskImages(taskId, cell);
    });
});

// Upload image button click
$('#dynamicTaskTable').on('click', '.upload-image-btn', function() {
    const btn = $(this);
    const taskId = btn.data('task-id');
    btn.siblings('.image-upload-input').trigger('click');
});

// Handle file input change
$('#dynamicTaskTable').on('change', '.image-upload-input', function() {
    const input = $(this)[0];
    const files = input.files;
    const taskId = $(this).data('task-id');
    if (!files.length) return;
    const cell = $(this).closest('.image-cell');
    for (let i = 0; i < files.length; i++) {
        const formData = new FormData();
        formData.append('task_id', taskId);
        formData.append('image', files[i]);
        $.ajax({
            url: '<?= base_url('task-images/upload') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    loadTaskImages(taskId, cell);
                } else {
                    Swal.fire('Upload Failed', res.message || 'Could not upload image.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error uploading image:', error);
                Swal.fire('Error', 'Failed to upload image.', 'error');
            }
        });
    }
    input.value = '';
});



// --- Table Settings Modal ---
let headerIds = <?php echo json_encode($validFields ?? []); ?>;
let allHeaderOptions = <?php echo json_encode($all_headers ?? []); ?>;
let headerMap = {};

// Safety checks for PHP variables
if (!headerIds || !Array.isArray(headerIds)) {
    headerIds = [];
}
if (!allHeaderOptions || !Array.isArray(allHeaderOptions)) {
    allHeaderOptions = [];
}

allHeaderOptions.forEach(h => { 
    if (h && h.id && h.column_name) {
        headerMap[h.id] = h.column_name; 
    }
});

let currentHeaders = [...headerIds]; // Keep as header IDs, not names

// Initialize event handlers when DOM is ready
$(document).ready(function() {
    // Setup button click handler for Configure Table Structure
    $(document).on('click', '#configureTableBtn', function() {
        console.log('Configure Table button clicked');
        showTableModal();
    });
    
    // Setup button click handler for Table Settings (gear icon)
    $(document).on('click', '#tableSettingsBtn', function() {
        console.log('Table Settings button clicked');
        showTableModal();
    });
});

// Simple function to show the modal
function showTableModal() {
    // Ensure modal exists first
    if (!document.getElementById('tableSettingsModal')) {
        createTableModal();
    }
    
    const modalElement = $('#tableSettingsModal');
    if (modalElement.length === 0) {
        console.error('Modal element not found!');
        return;
    }
    
    renderHeaderList();
    renderHeaderSelect2();
    modalElement.modal('show');
}

// Function to create the modal if it doesn't exist
function createTableModal() {
    const modalHtml = `
    <div class="modal fade" id="tableSettingsModal" tabindex="-1" aria-labelledby="tableSettingsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tableSettingsModalLabel"><i class='fas fa-cog'></i> Table Header Settings</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Current Headers (Drag to Sort):</label>
              <ul id="headerList" class="list-group mb-2" style="min-height:40px;"></ul>
            </div>
            <div class="mb-3 d-flex align-items-end" style="gap:0.5rem;">
              <div style="width:220px;">
                <label class="form-label">Add Header:</label>
                <select id="headerSelect2" class="form-select" style="width:100%;min-width:120px;max-width:220px;"></select>
              </div>
              <button id="addHeaderBtn" class="btn btn-primary" type="button" style="height:38px;">Add</button>
            </div>
          </div>
          <div class="modal-footer">
            <button id="saveHeadersBtn" class="btn btn-success" type="button">Save</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
          </div>
        </div>
      </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Add the save button event listener
    document.getElementById('saveHeadersBtn').onclick = function() {
        const templateId = window.templateId || <?php echo json_encode($template_id ?? null); ?>;
        let saveBtn = document.getElementById('saveHeadersBtn');
        saveBtn.disabled = true;
        // Only treat currentHeaders as an array of IDs. If a value is not a number, insert it and replace with new ID.
        let headersToInsert = [];
        let headerInsertIndices = [];
        currentHeaders.forEach((headerIdOrName, idx) => {
            if (typeof headerIdOrName === 'number' || (typeof headerIdOrName === 'string' && /^\d+$/.test(headerIdOrName))) {
                // Already an ID, do nothing
            } else {
                // Not an ID, treat as name to insert
                headersToInsert.push(headerIdOrName);
                headerInsertIndices.push(idx);
            }
        });
        if (headersToInsert.length === 0) {
            // All are IDs, do just save
            $.ajax({
                url: '<?= base_url('activity/updateHeaders') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ fields: currentHeaders, template_id: templateId }),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                dataType: 'json',
                success: function(data) {
                    saveBtn.disabled = false;
                    if (data.success) {
                        Swal.fire('Saved!', 'Table headers updated.', 'success');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('tableSettingsModal'));
                        modal.hide();
                        location.reload();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to save headers.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving headers:', error);
                    saveBtn.disabled = false;
                    Swal.fire('Error', 'Failed to save headers.', 'error');
                }
            });
            return;
        }
        // Insert new headers sequentially, then update template
        let i = 0;
        function insertNextHeader() {
            if (i < headersToInsert.length) {
                let headerText = headersToInsert[i];
                $.ajax({
                    url: '<?= base_url('activity/add_task_header') ?>',
                    method: 'POST',
                    data: { 
                        column_name: headerText,
                        template_id: templateId,
                        project_id: projectId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res && res.success && res.id) {
                            // Place the new id in the correct position in currentHeaders
                            let idx = headerInsertIndices[i];
                            currentHeaders[idx] = res.id;
                        } else {
                            Swal.fire('Error', 'Failed to add header: ' + headerText, 'error');
                        }
                        i++;
                        insertNextHeader();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding header:', error);
                        Swal.fire('Error', 'Failed to add header: ' + headerText, 'error');
                        i++;
                        insertNextHeader();
                    }
                });
            } else {
                // All new headers inserted, now update template
                $.ajax({
                    url: '<?= base_url('activity/updateHeaders') ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ fields: currentHeaders, template_id: templateId }),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    dataType: 'json',
                    success: function(data) {
                        saveBtn.disabled = false;
                        if (data.success) {
                            Swal.fire('Saved!', 'Table headers updated.', 'success');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('tableSettingsModal'));
                            modal.hide();
                            location.reload();
                        } else {
                            Swal.fire('Error', data.message || 'Failed to save headers.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating headers:', error);
                        saveBtn.disabled = false;
                        Swal.fire('Error', 'Failed to save headers.', 'error');
                    }
                });
            }
        }
        insertNextHeader();
    };
}
function renderHeaderList() {
    const list = document.getElementById('headerList');
    if (!list) return;
    
    list.innerHTML = '';
    list.style.display = 'grid';
    list.style.gridTemplateColumns = 'repeat(5, minmax(0, 1fr))';
    list.style.gap = '0.5rem';
    list.style.border = 'none';
    list.style.background = '';
    
    if (!currentHeaders || currentHeaders.length === 0) {
        list.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; color: #6b7280; padding: 1rem;">No headers added yet. Use the dropdown below to add some.</div>';
        return;
    }
    
    currentHeaders.forEach((headerId, idx) => {
        // If it's a number/string that looks like an ID, try to get the name from headerMap
        // If it's not in headerMap, it might be a new header name
        let headerName;
        if (typeof headerId === 'number' || (typeof headerId === 'string' && /^\d+$/.test(headerId))) {
            headerName = headerMap[headerId] || `Header ID ${headerId}`;
        } else {
            headerName = headerId; // It's a header name for a new header
        }
        
        const item = document.createElement('div');
        item.className = 'header-grid-item d-flex align-items-center justify-content-between';
        item.setAttribute('draggable', 'true');
        item.setAttribute('data-idx', idx);
        item.style.background = '';
        item.style.borderRadius = '0.7rem';
        item.style.padding = '0.5rem 0.7rem';
        item.style.fontSize = '0.98rem';
        item.style.boxShadow = '0 2px 8px rgba(102,126,234,0.07)';
        item.style.margin = '0';
        item.style.border = '1px solid #e2e8f0';
        item.style.cursor = 'grab';
        item.innerHTML = `<span style="flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${headerName}</span><button class='btn btn-sm btn-link text-danger remove-header-btn' title='Remove' style='margin-left:8px;'><i class='fas fa-times'></i></button>`;
        
        item.querySelector('.remove-header-btn').onclick = function() {
            currentHeaders.splice(idx, 1);
            renderHeaderList();
        };
        
        // Drag and drop functionality
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', idx);
            item.classList.add('dragging');
        });
        item.addEventListener('dragend', function(e) {
            item.classList.remove('dragging');
        });
        item.addEventListener('dragover', function(e) {
            e.preventDefault();
            item.classList.add('drag-over');
        });
        item.addEventListener('dragleave', function(e) {
            item.classList.remove('drag-over');
        });
        item.addEventListener('drop', function(e) {
            e.preventDefault();
            item.classList.remove('drag-over');
            const fromIdx = parseInt(e.dataTransfer.getData('text/plain'));
            const toIdx = idx;
            if (fromIdx !== toIdx) {
                const moved = currentHeaders.splice(fromIdx, 1)[0];
                currentHeaders.splice(toIdx, 0, moved);
                renderHeaderList();
            }
        });
        
        list.appendChild(item);
    });
}
function renderHeaderSelect2() {
    const select2El = $('#headerSelect2');
    if (select2El.length === 0) return;
    
    // Destroy if already initialized
    if (select2El.hasClass('select2-hidden-accessible')) {
        select2El.select2('destroy');
    }
    
    let headerLookupOptions = <?php echo json_encode($header_lookup ?? []); ?>;
    if (!headerLookupOptions || !Array.isArray(headerLookupOptions)) {
        headerLookupOptions = [];
    }
    
    select2El.empty();
    headerLookupOptions.forEach(opt => {
        if (opt && opt.column_name) {
            select2El.append(new Option(opt.column_name, opt.column_name, false, false));
        }
    });
    
    // Check if Select2 is available
    if (typeof select2El.select2 !== 'function') {
        return;
    }
    
    // Init Select2
    select2El.select2({
        tags: true,
        width: 200,
        placeholder: 'Type or select header...',
        dropdownParent: $('#tableSettingsModal'),
        allowClear: true,
        closeOnSelect: true,
        multiple: false
    });
    select2El.val(null).trigger('change');
}

// Add header functionality
$(document).off('click', '#addHeaderBtn').on('click', '#addHeaderBtn', function() {
    const select2El = $('#headerSelect2');
    let val = select2El.val();
    if (val && val.trim() !== '') {
        const headerName = val.trim();
        
        // First, check if this header already exists in our header options
        let existingHeaderId = null;
        allHeaderOptions.forEach(h => {
            if (h && h.column_name === headerName) {
                existingHeaderId = h.id;
            }
        });
        
        if (existingHeaderId) {
            // Use existing header ID
            currentHeaders.push(existingHeaderId);
            renderHeaderList();
            select2El.val(null).trigger('change');
        } else {
            // Mark this as a new header that needs to be created when saving
            currentHeaders.push(headerName); // Temporary name, will be replaced with ID when saving
            renderHeaderList();
            select2El.val(null).trigger('change');
        }
    }
});

$(document).off('keydown', '#headerSelect2').on('keydown', '#headerSelect2', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        $('#addHeaderBtn').click();
    }
});

</script>