<!-- Project Task View (Dynamic) -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #1f2937;">Project Activity</h2>
            <button class="btn btn-primary" id="addTaskBtn">
                <i class="fas fa-plus"></i> Add Task
            </button>
        </div>
        <div class="card mb-4">
            <div class="card-body p-0" style="box-shadow:none;transition:none;background:none;">
                <div class="task-template-list" id="taskTemplateList">
                    <!-- Dynamic template items will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load templates dynamically via AJAX
$(document).ready(function() {
    var projectId = <?= isset($project['id']) ? json_encode($project['id']) : 'null' ?>;
    $.get('<?= base_url('projects/get_task_templates') ?>?project_id=' + encodeURIComponent(projectId), function(res) {
        $('#taskTemplateList').empty();
        if (res.success && Array.isArray(res.templates) && res.templates.length > 0) {
            let templatesLoaded = 0;
            res.templates.forEach(function(tmpl) {
                $.get('<?= base_url('projects/get_tasks_by_template/') ?>' + tmpl.id + '/' + encodeURIComponent(projectId), function(taskRes) {
                    let percent = 0;
                    if (taskRes.success && Array.isArray(taskRes.tasks) && taskRes.tasks.length > 0) {
                        let sum = 0;
                        let count = 0;
                        taskRes.tasks.forEach(t => {
                            let progressRaw = t.Progress || t.progress || '';
                            if (typeof progressRaw === 'string') {
                                progressRaw = progressRaw.replace(/[^\d.]/g, '');
                            }
                            let progressNum = parseFloat(progressRaw);
                            if (!isNaN(progressNum)) {
                                sum += progressNum;
                                count++;
                            }
                        });
                        if (count > 0) {
                            percent = Math.round(sum / count);
                        }
                    }
                    const cardStyle = [
                        'background: #fff',
                        'border-radius: 1rem',
                        'border: 1px solid #e2e8f0',
                        'margin-bottom: 1.2rem',
                        'padding: 1.5rem 2rem',
                        'display: flex',
                        'justify-content: space-between',
                        'align-items: center'
                    ].join(';');
                    $('#taskTemplateList').append(`
                        <div class="task-template-card task-template-item" data-template-id="${tmpl.id}" style="${cardStyle}; box-shadow: 0 4px 24px rgba(102,126,234,0.08); transition: box-shadow 0.3s, transform 0.3s, background 0.3s;" onmouseover="this.style.boxShadow='0 8px 32px rgba(102,126,234,0.15)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.boxShadow='0 4px 24px rgba(102,126,234,0.08)'; this.style.transform='none';">
                            <div style="flex:1; display:flex; align-items:center; gap:1.2rem;">
                                <div style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:1.08rem; color:#23272b;">${tmpl.name}</div>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.7rem; min-width:54px;">
                                <div style="display:flex; flex-direction:column; align-items:center;">
                                    <div style="width:32px; height:32px; position:relative;">
                                        <svg width="32" height="32" viewBox="0 0 32 32">
                                            <circle cx="16" cy="16" r="12" stroke="#e2e8f0" stroke-width="4" fill="none" />
                                            <circle cx="16" cy="16" r="12" stroke="#06b6d4" stroke-width="4" fill="none" stroke-dasharray="${Math.PI * 2 * 12}" stroke-dashoffset="${Math.PI * 2 * 12 * (1 - percent / 100)}" stroke-linecap="round" transform="rotate(-90 16 16)" />
                                        </svg>
                                        <span style="position:absolute; left:0; right:0; top:0; bottom:0; display:flex; align-items:center; justify-content:center; font-size:0.95rem; font-weight:800; color:#4a5568;">${percent}%</span>
                                    </div>
                                    <div style="color:#6b7280; font-size:0.75rem; margin-bottom:2px;">Progress</div>
                                </div>
                                <i class="fas fa-chevron-right" style="font-size:1.1rem; color:#667eea; margin-left:0.5rem;"></i>
                            </div>
                        </div>
                    `);
                    templatesLoaded++;
                });
            });
        } else {
            // No templates: show message and Add Task button
            $('#taskTemplateList').html('<div class="text-center text-muted" style="padding:2rem;">No templates for this project.<br><button class="btn btn-primary mt-3" id="createTemplateBtn"><i class="fas fa-plus"></i> Create Template</button></div>');
        }
    });

    $('#taskTemplateList').on('click', '.task-template-item', function() {
        var templateId = $(this).data('template-id');
        window.location.href = '<?= base_url('projects/task_page/') ?>' + templateId + '?project_id=' + projectId;
    });

    // Create Template button
    $('#taskTemplateList').on('click', '#createTemplateBtn', function() {
        Swal.fire({
            title: 'Create New Template',
            input: 'text',
            inputLabel: 'Template Name',
            inputPlaceholder: 'Enter template name',
            showCancelButton: true,
            confirmButtonText: 'Create',
            preConfirm: (name) => {
                if (!name) return false;
                return name;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                $.ajax({
                    url: '<?= base_url('projects/create_template') ?>',
                    method: 'POST',
                    data: { name: result.value, project_id: projectId },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Created!', 'Template created.', 'success');
                            location.reload();
                        } else {
                            Swal.fire('Error', res.message || 'Failed to create template.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to create template.', 'error');
                    }
                });
            }
        });
    });

    // Add Task button
    $('#addTaskBtn').on('click', function() {
        $.get('<?= base_url('projects/get_task_templates') ?>', function(res) {
            if (res.success && Array.isArray(res.templates)) {
                let options = res.templates.map(t => `<option value='${t.code}'>${t.name}</option>`).join('');
                Swal.fire({
                    title: 'Add New Task',
                    html: `<select id='templateSelect' class='form-select'>${options}</select>`,
                    showCancelButton: true,
                    confirmButtonText: 'Add',
                    preConfirm: () => {
                        const template = document.getElementById('templateSelect').value;
                        window.location.href = '<?= base_url('projects/add_task?template=') ?>' + template + '&project_id=' + projectId;
                    }
                });
            }
        });
    });
});
</script>
