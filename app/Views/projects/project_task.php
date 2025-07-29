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
            let templatesLoaded = 0;
            res.templates.forEach(function(tmpl) {
                // Use new URL pattern: /projects/get_tasks_by_template/{template_code}/{project_id}
                $.get('<?= base_url('projects/get_tasks_by_template/') ?>' + tmpl.code + '/' + encodeURIComponent(projectId), function(taskRes) {
                    let percent = 0;
                    if (taskRes.success && Array.isArray(taskRes.tasks) && taskRes.tasks.length > 0) {
                        // Calculate average progress from Progress field (e.g., "60%")
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
                    const progressSize = 48;
                    const radius = 20;
                    const strokeBg = 7;
                    const strokeFg = 7;
                    const circumference = 2 * Math.PI * radius;
                    const fgColor = percent === 100 ? '#22c55e' : '#667eea';
                    const textColor = percent === 100 ? '#22c55e' : '#4338ca';
                    const progressCircleStyle = [
                        `width: ${progressSize}px`,
                        `height: ${progressSize}px`,
                        'position: relative',
                        'display: block',
                        'background: linear-gradient(135deg, #f3f4f6 0%, #e0e7ff 100%)',
                        'border-radius: 50%'
                    ].join(';');
                    const progressTextStyle = [
                        'position: absolute',
                        'top: 50%',
                        'left: 50%',
                        'transform: translate(-50%, -50%)',
                        'font-size: 1.15rem',
                        'font-weight: 800',
                        `color: ${percent === 100 ? '#166534' : textColor}`,
                        'text-shadow: 0 0 2px #fff, 0 1px 4px rgba(0,0,0,0.08)'
                    ].join(';');
                    $('#taskTemplateList').append(`
                        <div class="task-template-card task-template-item" data-template-code="${tmpl.code}" style="${cardStyle}">
                            <div class="d-flex align-items-center gap-3">
                                <span style="font-family: 'Poppins', sans-serif; font-weight:700; font-size:1.15rem; color:#3b3b3b;">${tmpl.name}</span>
                                <div class="progress-circle" style="${progressCircleStyle}">
                                    <svg width="${progressSize}" height="${progressSize}" style="display:block;">
                                        <circle cx="${progressSize/2}" cy="${progressSize/2}" r="${radius}" stroke="#e2e8f0" stroke-width="${strokeBg}" fill="none"/>
                                        <circle cx="${progressSize/2}" cy="${progressSize/2}" r="${radius}" stroke="${fgColor}" stroke-width="${strokeFg}" fill="none"
                                            stroke-dasharray="${circumference}"
                                            stroke-dashoffset="${circumference - (circumference * percent / 100)}"
                                            style="transition: stroke-dashoffset 0.6s cubic-bezier(.4,2,.3,1); stroke-linecap:round; filter: drop-shadow(0 2px 6px rgba(102,126,234,0.10));"/>
                                    </svg>
                                    <span style="${progressTextStyle}">${percent}%</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right" style="font-size:1.3rem; color:#667eea;"></i>
                        </div>
                    `);
                    templatesLoaded++;
                    // Add hover effect after all templates loaded
                    if (templatesLoaded === res.templates.length) {
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
