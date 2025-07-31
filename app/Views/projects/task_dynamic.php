<!-- Modern Dynamic Task Page - Enhanced UI -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <h2 id="taskTableTitle" style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #3b3b3b; letter-spacing: -1px; margin-bottom: 2.5rem; font-size:2.4rem; text-align:center;">
            <?= esc($template['name'] ?? 'Tasks') ?>
        </h2>
        <div style="background: #fff; border-radius: 2rem; box-shadow: 0 16px 48px rgba(102,126,234,0.13); border: none; padding: 2.5rem 2.5rem 2rem 2.5rem; margin-bottom:2rem;">
        <!-- Image Preview Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:1.2rem;">
            <div class="modal-header" style="border-bottom:0;">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Task Image" style="max-width:100%;max-height:400px;border-radius:0.7rem;box-shadow:0 2px 12px rgba(102,126,234,0.13);margin-bottom:1rem;" />
            </div>
            <div class="modal-footer" style="border-top:0;justify-content:center;">
                <button id="deleteImageBtn" type="button" class="btn btn-danger" data-image-id=""><i class="fas fa-trash-alt me-1"></i>Delete Image</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
            <!-- Export Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2" id="exportBtnGroupLeft">
                    <button id="exportCsvBtn" class="btn btn-outline-primary" type="button"><i class="fas fa-file-csv me-1"></i> CSV</button>
                    <button id="exportExcelBtn" class="btn btn-outline-success" type="button"><i class="fas fa-file-excel me-1"></i> Excel</button>
                    <button id="exportPdfBtn" class="btn btn-outline-danger" type="button"><i class="fas fa-file-pdf me-1"></i> PDF</button>
                </div>
                <div>
                    <button id="tableSettingsBtn" class="btn btn-outline-secondary" type="button" title="Table Settings" style="border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="table table-hover table-bordered" id="dynamicTaskTable" style="background: #fff; border-radius: 1.2rem; overflow: visible; box-shadow: 0 2px 12px rgba(102,126,234,0.07); margin-bottom:0; min-width:1100px;">
                    <thead class="table-light" style="background: linear-gradient(90deg, #e9ecef 0%, #f8fafc 100%);">
                        <tr>
                            <?php if (!empty($fields)): ?>
                                <?php foreach ($fields as $headerId): ?>
                                    <?php $columnName = $headerMap[$headerId] ?? null; ?>
                                    <th><?= esc($columnName) ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <th style="width:72px; text-align:center; background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                                    <button id="addRowBtn" type="button" class="btn btn-sm btn-outline-primary" title="Add Row" style="border-radius:50%;box-shadow:0 2px 8px rgba(102,126,234,0.12);width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </th>
                            <th style="width:32px;"></th>
                    </thead>
                    <tbody>
                        <?php if (empty($fields)): ?>
                            <tr><td class="text-center text-muted" colspan="100%">No template fields available.</td></tr>
                        <?php elseif (empty($tasks)): ?>
                            <!-- No tasks: show nothing, allow adding rows -->
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                        <?php $taskData = is_array($task['data']) ? $task['data'] : json_decode($task['data'], true); ?>
                        <tr data-task-id="<?= esc($task['id']) ?>" class="task-row" style="transition:box-shadow 0.3s;">
                            <?php foreach ($fields as $headerId): ?>
                                <?php $field = $headerMap[$headerId] ?? null; ?>
                                <?php if (in_array($field, ['Tester Name','PIC'])): ?>
                                    <td class="editable-cell user-dropdown-cell" tabindex="0" data-field="<?= esc($field) ?>"><span class="user-display"><?= esc($taskData[$field] ?? '') ?></span></td>
                                <?php elseif (in_array($field, ['Start Date','End Date','Progress','Status'])): ?>
                                    <td class="editable-cell" tabindex="0" data-field="<?= esc($field) ?>"><?= esc($taskData[$field] ?? '') ?></td>
                                <?php elseif ($field === 'Image'): ?>
                                    <td class="image-cell" data-field="Image" style="padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0; min-width:110px; max-width:140px; text-align:center; vertical-align:middle;">
                                        <div class="task-image-list" data-task-id="<?= esc($task['id']) ?>"></div>
                                        <button class="btn btn-link p-0 upload-image-btn" title="Upload Image" data-task-id="<?= esc($task['id']) ?>" style="color:#667eea; font-size:1.5rem; display:inline-block;"><i class="fas fa-image"></i></button>
                                        <input type="file" accept="image/*" class="d-none image-upload-input" data-task-id="<?= esc($task['id']) ?>" multiple>
                                    </td>
                                <?php elseif ($field === 'Last Modified'): ?>
                                    <td style="color:#6b7280; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>
                                <?php else: ?>
                                    <td contenteditable="true" class="editable-cell" tabindex="0" data-field="<?= esc($field) ?>" style="transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"><?= esc($taskData[$field] ?? '') ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td class="text-center align-middle" style="width:72px;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                                    <button type="button" class="btn btn-sm btn-link text-danger delete-row-btn" title="Delete Row" style="padding:0;border-radius:50%;background:#f8d7da;width:32px;height:32px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
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
// --- Export Table Functionality ---
// Requires jsPDF, SheetJS (xlsx), and FileSaver.js for full support. Assumes FontAwesome icons are available.
// You may need to include these libraries in your assets if not already present.

// PDF Export (jsPDF + html2canvas, table as image, with title)
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
// --- Patch: Always send project_id and template_code in AJAX ---
const projectId = "<?= esc($project_id ?? $template['project_id'] ?? $project['id'] ?? '') ?>";
const templateCode = "<?= esc($template['code'] ?? '') ?>";

// --- User dropdown for Tester Name and PIC ---
let projectUsers = [];
function fetchProjectUsers() {
    return $.ajax({
        url: '<?= base_url('projects/project_users/') ?>' + projectId,
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                projectUsers = res.users;
            }
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
        const fullName = (u.first_name + ' ' + u.last_name).trim();
        selectHtml += `<option value="${fullName}"${currentValue === fullName ? ' selected' : ''}>${fullName}</option>`;
    });
    selectHtml += `</select>`;
    const overlay = $(selectHtml);
    $('body').append(overlay);
    overlay.focus();
    overlay.on('blur change', function() {
        let val = this.value;
        cell.find('.user-display').text(val);
        cell.css('background', '#fff');
        $('.cell-overlay-editor').remove();
        cell.trigger('blur');
    });
    overlay.on('keydown', function(ev) {
        if (ev.key === 'Enter' || ev.key === 'Tab') {
            this.blur();
        }
    });
}

// --- Row coloring by module cell content ---
function colorRowsByModule() {
    const rows = document.querySelectorAll('#dynamicTaskTable tbody tr.task-row');
    let lastModule = null;
    let colorIdx = 0;
    const colors = ['#fffbe6', '#e3f6fd']; // orange, blue
    rows.forEach(row => {
        const firstCell = row.querySelector('td');
        if (!firstCell) return;
        const moduleVal = firstCell.textContent.trim();
        if (moduleVal !== lastModule) {
            colorIdx = 1 - colorIdx; // alternate color when module changes
            lastModule = moduleVal;
        }
        row.style.background = colors[colorIdx];
    });
}

// Initial coloring after page load
$(document).ready(function() {
    colorRowsByModule();
});

// Recolor after adding a row
$('#addRowBtn').on('click', function() {
    setTimeout(colorRowsByModule, 50);
});

// Recolor after deleting a row
$('#dynamicTaskTable').on('click', '.delete-row-btn', function() {
    setTimeout(colorRowsByModule, 50);
});

// Recolor after sorting
new Sortable(document.querySelector('#dynamicTaskTable tbody'), {
    handle: '.drag-handle',
    animation: 150,
    ghostClass: 'bg-light',
    onEnd: function(evt) {
        // Send new task_order to backend
        const task_order = Array.from(document.querySelectorAll('#dynamicTaskTable tbody tr.task-row'))
            .map(row => row.getAttribute('data-task-id'))
            .filter(id => id);
        $.ajax({
            url: '<?= base_url('projects/update_task_order') ?>',
            method: 'POST',
            data: { task_order: task_order },
            success: function(res) {
                if (res.success) {
                    // Optionally show a success message
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'task order Save Failed',
                        text: res.message || 'Could not save task_order.'
                    });
                }
            }
        });
        setTimeout(colorRowsByModule, 50);
    }
});

// Row hover effect for modern look
$('#dynamicTaskTable tbody').on('mouseenter', 'tr.task-row', function() {
    $(this).css({
        'box-shadow': '0 12px 32px rgba(102,126,234,0.13)',
        'background': 'linear-gradient(90deg,#e9ecef 0%,#f8fafc 100%)'
    });
});
$('#dynamicTaskTable tbody').on('mouseleave', 'tr.task-row', function() {
    $(this).css('box-shadow', 'none');
    // Restore color by module
    colorRowsByModule();
});
// Editable cell focus effect
// --- Refactored editable cell logic for dynamic fields ---
$('#dynamicTaskTable').on('click', 'td.editable-cell', function(e) {
    if (e.target !== this) return;
    $(this).css('background', '#e0e7ff');
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
        cell.css('background', '#fff');
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
        let current = parseInt(cell.text().replace('%','').trim(), 10) || 0;
        let selectHtml = `<select class="cell-overlay-editor form-select form-select-sm" style="position:absolute;z-index:9999;left:${cellOffset.left}px;top:${cellOffset.top}px;width:${cellWidth}px;height:${cellHeight}px;">`;
        for (let i = 0; i <= 100; i += 10) {
            selectHtml += `<option value="${i}"${current === i ? ' selected' : ''}>${i}%</option>`;
        }
        selectHtml += `</select>`;
        overlay = $(selectHtml);
        $('body').append(overlay);
        overlay.focus();
        overlay.on('blur change', function() {
            let val = this.value + '%';
            removeOverlay(val, val);
        });
        overlay.on('keydown', function(ev) {
            if (ev.key === 'Enter' || ev.key === 'Tab') {
                this.blur();
            }
        });
    } else if (field === 'Status') {
        const statusOptions = [
            { code: 'pending', name: 'To Do' },
            { code: 'in_progress', name: 'In Progress' },
            { code: 'review', name: 'Review' },
            { code: 'completed', name: 'Done' }
        ];
        let current = cell.text().trim().toLowerCase().replace(' ', '_');
        let selectHtml = `<select class="cell-overlay-editor form-select form-select-sm" style="position:absolute;z-index:9999;left:${cellOffset.left}px;top:${cellOffset.top}px;width:${cellWidth}px;height:${cellHeight}px;">`;
        statusOptions.forEach(opt => {
            selectHtml += `<option value="${opt.code}"${current === opt.code ? ' selected' : ''}>${opt.name}</option>`;
        });
        selectHtml += `</select>`;
        overlay = $(selectHtml);
        $('body').append(overlay);
        overlay.focus();
        overlay.on('blur change', function() {
            let val = this.value;
            let display = statusOptions.find(opt => opt.code === val)?.name || val;
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
    $(this).css('background', '#fff');
    const cell = $(this);
    const row = cell.closest('tr');
    let taskId = row.data('task-id');
    const data = {};
    let statusValue = null;
    let progressCell = null;
    row.find('td.editable-cell').each(function() {
        const field = $(this).data('field');
        let value;
        if (["Tester Name","PIC"].includes(field)) {
            value = $(this).find('.user-display').text().trim();
        } else if (field === 'Start Date' || field === 'End Date') {
            value = $(this).find('input[type="date"]').val() || $(this).text();
        } else if (field === 'Progress') {
            value = $(this).find('select.cell-overlay-editor').val() || $(this).text();
            if (value !== undefined && value !== null && value !== '') {
                value = Math.max(0, Math.min(100, parseInt(value, 10))) + '%';
            } else {
                value = $(this).text();
            }
            progressCell = $(this);
        } else if (field === 'Status') {
            value = $(this).find('select').val() || $(this).text();
            statusValue = value;
        } else {
            value = $(this).text();
        }
        data[field] = value;
    });
    // If status is completed/done, set progress to 100%
    if (statusValue === 'completed' && progressCell) {
        data['Progress'] = '100%';
        progressCell.text('100%');
    }
    // Restore cell display
    row.find('td.editable-cell').each(function() {
        const field = $(this).data('field');
        if (["Tester Name","PIC"].includes(field)) {
            $(this).find('.user-display').text(data[field]);
        } else if (field === 'Start Date' || field === 'End Date') {
            $(this).text(data[field]);
        } else if (field === 'Progress') {
            $(this).text(data[field]);
        } else if (field === 'Status') {
            const statusOptions = {
                'pending': 'To Do',
                'in_progress': 'In Progress',
                'review': 'Review',
                'completed': 'Done'
            };
            $(this).text(statusOptions[data[field]] || data[field]);
        }
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
    // --- Refactored: Use JS for dynamic field handling ---
    let rowHtml = '<tr class="task-row" style="transition:box-shadow 0.3s;">';
    // Get header IDs and mapping from PHP variables
    let headerIds = <?php echo json_encode($fields ?? []); ?>;
    let allHeaderOptions = <?php echo json_encode($all_headers ?? []); ?>;
    let headerMap = {};
    allHeaderOptions.forEach(h => { headerMap[h.id] = h.column_name; });
    // For each header, generate the correct cell type
    headerIds.forEach(function(headerId, idx) {
        let field = headerMap[headerId] || '';
        if (idx === 0) {
            rowHtml += '<td contenteditable="true" class="editable-cell" tabindex="0" data-field="'+field+'" style="transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;">'+firstColValue+'</td>';
        } else if (field === 'Last Modified') {
            rowHtml += '<td style="color:#6b7280; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        } else if (field === 'Image') {
            rowHtml += '<td class="image-cell" data-field="Image" style="padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0; min-width:110px; max-width:140px; text-align:center; vertical-align:middle;">';
            rowHtml += '<div class="task-image-list" data-task-id=""></div>';
            rowHtml += '<button class="btn btn-link p-0 upload-image-btn" title="Upload Image" data-task-id="" style="color:#667eea; font-size:1.5rem; display:inline-block;"><i class="fas fa-image"></i></button>';
            rowHtml += '<input type="file" accept="image/*" class="d-none image-upload-input" data-task-id="" multiple>';
            rowHtml += '</td>';
        } else if (["Start Date","End Date","Progress","Status"].includes(field)) {
            rowHtml += '<td class="editable-cell" tabindex="0" data-field="'+field+'" style="transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        } else if (["Tester Name","PIC"].includes(field)) {
            rowHtml += '<td class="editable-cell user-dropdown-cell" tabindex="0" data-field="'+field+'"><span class="user-display"></span></td>';
        } else {
            rowHtml += '<td contenteditable="true" class="editable-cell" tabindex="0" data-field="'+field+'" style="transition:background 0.2s; padding:1rem 1rem; font-size:1.05rem; border-bottom:1px solid #e2e8f0;"></td>';
        }
    });
    // Only the trash button in the row
    rowHtml += '<td class="text-center align-middle" style="width:72px;">';
    rowHtml += '<div style="display:flex;align-items:center;justify-content:center;gap:8px;">';
    rowHtml += '<button type="button" class="btn btn-sm btn-link text-danger delete-row-btn" title="Delete Row" style="padding:0;border-radius:50%;background:#f8d7da;width:32px;height:32px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-trash"></i></button>';
    rowHtml += '</div>';
    rowHtml += '</td>';
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
        // Send new task_order to backend
        const task_order = Array.from(document.querySelectorAll('#dynamicTaskTable tbody tr.task-row'))
            .map(row => row.getAttribute('data-task-id'))
            .filter(id => id);
        $.ajax({
            url: '<?= base_url('projects/update_task_order') ?>',
            method: 'POST',
            data: { task_order: task_order },
            success: function(res) {
                if (res.success) {
                    // Optionally show a success message
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'task order Save Failed',
                        text: res.message || 'Could not save task_order.'
                    });
                }
            }
        });
        setTimeout(colorRowsByModule, 50);
    }
});

function loadTaskImages(taskId, cell) {
    $.get('<?= base_url('task-images/list/') ?>' + taskId, function(res) {
        if (res.success) {
            const list = cell.find('.task-image-list');
            list.empty();
            res.images.forEach(img => {
                // Use controller route to serve images from writable using file_address (random filename)
                // file_address is like 'task_image/abc123.jpg', so we want only the filename part
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
    $.post('<?= base_url('task-images/delete/') ?>' + imageId, function(res) {
        if (res.success) {
            const modalEl = document.getElementById('imageModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            // Find the cell and reload images
            $('.task-thumb[data-image-id="'+imageId+'"]').closest('.image-cell').each(function() {
                const cell = $(this);
                const taskId = cell.closest('tr').data('task-id');
                loadTaskImages(taskId, cell);
            });
        } else {
            Swal.fire('Delete Failed', res.message || 'Could not delete image.', 'error');
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
            success: function(res) {
                if (res.success) {
                    loadTaskImages(taskId, cell);
                } else {
                    Swal.fire('Upload Failed', res.message || 'Could not upload image.', 'error');
                }
            }
        });
    }
    input.value = '';
});

// Click thumbnail to show modal
$('#dynamicTaskTable').on('click', '.task-thumb', function() {
    const img = $(this);
    $('#modalImage').attr('src', img.data('image-src'));
    $('#deleteImageBtn').attr('data-image-id', img.data('image-id'));
    $('#imageModal').modal('show');
});

// Delete image from modal
$('#deleteImageBtn').on('click', function() {
    const imageId = $(this).attr('data-image-id');
    if (!imageId) return;
    $.post('<?= base_url('task-images/delete/') ?>' + imageId, function(res) {
        if (res.success) {
            $('#imageModal').modal('hide');
            // Find the cell and reload images
            $('.task-thumb[data-image-id="'+imageId+'"]').closest('.image-cell').each(function() {
                const cell = $(this);
                const taskId = cell.closest('tr').data('task-id');
                loadTaskImages(taskId, cell);
            });
        } else {
            Swal.fire('Delete Failed', res.message || 'Could not delete image.', 'error');
        }
    });
});

// --- Table Settings Modal ---
const tableSettingsBtn = document.getElementById('tableSettingsBtn');
let headerIds = <?php echo json_encode($fields ?? []); ?>;
let allHeaderOptions = <?php echo json_encode($all_headers ?? []); ?>;
let headerMap = {};
allHeaderOptions.forEach(h => { headerMap[h.id] = h.column_name; });
let currentHeaders = headerIds.map(id => headerMap[id] || '');
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
        <div class="mb-3">
          <label class="form-label">Add Header:</label>
          <div class="input-group">
            <select id="headerDropdown" class="form-select" style="max-width:220px;"></select>
            <input id="customHeaderInput" type="text" class="form-control" placeholder="Custom header..." style="max-width:180px;">
            <button id="addHeaderBtn" class="btn btn-primary" type="button"><i class="fas fa-plus"></i></button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="saveHeadersBtn" class="btn btn-success" type="button">Save</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
      </div>
    </div>
  </div>
</div>`;
if (!document.getElementById('tableSettingsModal')) {
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}
tableSettingsBtn.addEventListener('click', function() {
    renderHeaderList();
    renderHeaderDropdown();
    document.getElementById('customHeaderInput').value = '';
    const modal = new bootstrap.Modal(document.getElementById('tableSettingsModal'));
    modal.show();
});
function renderHeaderList() {
    const list = document.getElementById('headerList');
    list.innerHTML = '';
    // Use CSS grid for compact multi-row layout
    list.style.display = 'grid';
    list.style.gridTemplateColumns = 'repeat(5, minmax(0, 1fr))';
    list.style.gap = '0.5rem';
    list.style.border = 'none';
    list.style.background = 'none';
    currentHeaders.forEach((header, idx) => {
        const item = document.createElement('div');
        item.className = 'header-grid-item d-flex align-items-center justify-content-between';
        item.setAttribute('draggable', 'true');
        item.setAttribute('data-idx', idx);
        item.style.background = '#f8fafc';
        item.style.borderRadius = '0.7rem';
        item.style.padding = '0.5rem 0.7rem';
        item.style.fontSize = '0.98rem';
        item.style.boxShadow = '0 2px 8px rgba(102,126,234,0.07)';
        item.style.margin = '0';
        item.style.border = '1px solid #e2e8f0';
        item.style.cursor = 'grab';
        item.innerHTML = `<span style="flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${header}</span><button class='btn btn-sm btn-link text-danger remove-header-btn' title='Remove' style='margin-left:8px;'><i class='fas fa-times'></i></button>`;
        item.querySelector('.remove-header-btn').onclick = function() {
            currentHeaders.splice(idx, 1);
            renderHeaderList();
        };
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
function renderHeaderDropdown() {
    const dropdown = document.getElementById('headerDropdown');
    dropdown.innerHTML = '';
    // Use header_lookup for dropdown options, allow duplicates
    let headerLookupOptions = <?php echo json_encode($header_lookup ?? []); ?>;
    headerLookupOptions.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.column_name;
        option.textContent = opt.column_name;
        dropdown.appendChild(option);
    });
}
document.getElementById('addHeaderBtn').onclick = function() {
    const dropdown = document.getElementById('headerDropdown');
    const customInput = document.getElementById('customHeaderInput');
    let val = customInput.value.trim();
    if (!val && dropdown.value) val = dropdown.value;
    if (val && !currentHeaders.includes(val)) {
        currentHeaders.push(val);
        renderHeaderList();
        renderHeaderDropdown();
        customInput.value = '';
    }
};
document.getElementById('saveHeadersBtn').onclick = function() {
    const templateId = window.templateId || <?php echo json_encode($template_id ?? null); ?>;
    $.ajax({
        url: '<?= base_url('projects/updateHeaders') ?>',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ fields: currentHeaders, template_id: templateId }),
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(data) {
            if (data.success) {
                Swal.fire('Saved!', 'Table headers updated.', 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('tableSettingsModal'));
                modal.hide();
                location.reload();
            } else {
                Swal.fire('Error', data.message || 'Failed to save headers.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Failed to save headers.', 'error');
        }
    });
};
</script>