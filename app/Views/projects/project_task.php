<!-- Project Task View (Dynamic) -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    <div style="max-width: 900px; margin: 0 auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #1f2937;">Project Tasks</h2>
            <button class="btn btn-primary" id="addTaskBtn">
                <i class="fas fa-plus"></i> Add Task
            </button>
        </div>
        <div class="card mb-4">
            <div class="card-body p-0">
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
    // Get project_id from PHP (should be passed to view)
    var projectId = <?= isset($project['id']) ? json_encode($project['id']) : 'null' ?>;
    $.get('<?= base_url('projects/get_task_templates') ?>', function(res) {
        if (res.success && Array.isArray(res.templates)) {
            $('#taskTemplateList').empty();
            res.templates.forEach(function(tmpl) {
                // Inline styles for card and progress
                const cardStyle = [
                    'background: #fff',
                    'border-radius: 1rem',
                    'box-shadow: 0 4px 24px rgba(102,126,234,0.08)',
                    'border: 1px solid #e2e8f0',
                    'margin-bottom: 1.2rem',
                    'padding: 1.5rem 2rem',
                    'display: flex',
                    'align-items: center',
                    'justify-content: space-between',
                    'cursor: pointer',
                    'transition: box-shadow 0.3s, transform 0.3s, background 0.3s'
                ].join(';');
                const progressCircleStyle = [
                    'width: 40px',
                    'height: 40px',
                    'position: relative',
                    'display: block'
                ].join(';');
                const progressTextStyle = [
                    'position: absolute',
                    'top: 50%',
                    'left: 50%',
                    'transform: translate(-50%, -50%)',
                    'font-size: 0.95rem',
                    'font-weight: 700',
                    'color: #667eea'
                ].join(';');
                $('#taskTemplateList').append(`
                    <div class="task-template-card task-template-item" data-template-code="${tmpl.code}" style="${cardStyle}">
                        <div class="d-flex align-items-center gap-3">
                            <span style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:1.15rem; color:#3b3b3b;">${tmpl.name}</span>
                            <div class="progress-circle" style="${progressCircleStyle}">
                                <svg width="40" height="40" style="display:block;">
                                    <circle cx="20" cy="20" r="16" stroke="#e2e8f0" stroke-width="6" fill="none"/>
                                    <circle cx="20" cy="20" r="16" stroke="#667eea" stroke-width="6" fill="none" stroke-dasharray="100.48" stroke-dashoffset="${100.48 - (100.48 * (tmpl.progress ?? 0) / 100)}" style="transition: stroke-dashoffset 0.5s;"/>
                                </svg>
                                <span style="${progressTextStyle}">${Math.round(tmpl.progress ?? 0)}%</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right" style="font-size:1.3rem; color:#667eea;"></i>
                    </div>
                `);
            });
            // Add hover effect via JS
            $('#taskTemplateList').on('mouseenter', '.task-template-card', function() {
                $(this).css('box-shadow', '0 12px 32px rgba(102,126,234,0.18)');
                $(this).css('background', 'linear-gradient(135deg, #e9ecef 0%, #f8fafc 100%)');
                $(this).css('transform', 'translateY(-6px) scale(1.03)');
            });
            $('#taskTemplateList').on('mouseleave', '.task-template-card', function() {
                $(this).css('box-shadow', '0 4px 24px rgba(102,126,234,0.08)');
                $(this).css('background', '#fff');
                $(this).css('transform', 'none');
            });
        }
    });

    // Event delegation for template item click
    $('#taskTemplateList').on('click', '.task-template-item', function() {
        var templateCode = $(this).data('template-code');
        window.location.href = '<?= base_url('projects/task_page/') ?>' + templateCode + '?project_id=' + projectId;
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
