<!-- Modern Activity List View -->

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                </a>
                <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #ffffff; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem; background: rgba(255,255,255,0.1); border-radius: 0.2rem;">
                <i class="fas fa-tasks" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Activities
            </li>
        </ol>
    </nav>

    <!-- Enhanced Filters -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; margin-bottom: 2rem; padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; width: 100%; justify-content: space-between;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end; width: 100%; gap: 1rem;">
                <div style="min-width: 260px; max-width: 350px; flex: 0 0 260px;">
                    <select id="projectSearchDropdown" class="form-select" style="width: 100%;">
                        <option value="">Search project...</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= esc($project['id']) ?>"><?= esc($project['title'] ?? $project['name'] ?? 'Untitled') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <select id="statusFilter" class="form-select" style="min-width: 180px;">
                        <option value="">All Status</option>
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= esc($status['code']) ?>"><?= esc($status['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button onclick="clearFilters()" style="padding: 0.75rem 1rem; background: #f3f4f6; border: 2px solid #e5e7eb; color: #6b7280; border-radius: 0.75rem; font-size: 0.9rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='#e5e7eb'; this.style.color='#374151'" onmouseout="this.style.background='#f3f4f6'; this.style.color='#6b7280'">
                        <i class="fas fa-times" style="font-size: 0.8rem;"></i>
                        Clear
                    </button>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 2rem;">
        <?php foreach ($projects as $project): ?>
            <div data-status-code="<?= esc($project['status_code'] ?? '') ?>" style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                <div style="padding: 2rem; display: flex; flex-direction: column; gap: 1.2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span class="badge bg-<?= esc($status_colors[$project['status_code'] ?? 'pending'] ?? 'secondary') ?>" style="font-size: 1rem; min-width: 80px; padding: 0.5rem 1rem;"><?= esc($project['status_name'] ?? 'Pending') ?></span>
                    </div>
                    <h4 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #1f2937; margin: 0; font-size: 1.4rem;"><?= esc($project['title'] ?? $project['name'] ?? 'Untitled') ?></h4>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="font-size: 1rem; color: #4a5568;">
                            <i class="fas fa-tasks me-1"></i> <?= esc($project['task_count'] ?? 0) ?> tasks
                        </div>
                        <div style="font-size: 1rem; color: #4a5568;">
                            <i class="fas fa-user-friends me-1"></i> <?= is_array($project['team_members']) ? count($project['team_members']) : 0 ?> members
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                            <div style="height: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: <?= isset($project['avg_progress']) ? round($project['avg_progress']) : 0 ?>%; transition: width 0.5s;"></div>
                        </div>
                        <div style="font-size: 0.95rem; color: #667eea; font-weight: 600; margin-top: 0.5rem;">Progress: <?= isset($project['avg_progress']) ? round($project['avg_progress']) : 0 ?>%</div>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <a href="<?= base_url('activity/activity_scope/' . $project['id']) ?>" class="btn btn-outline-primary btn-sm" title="View Project Activities" style="font-weight: 600; border-radius: 0.5rem;">
                            <i class="fas fa-eye"></i> View Activities
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<script>

$(document).ready(function() {
    // Initialize Select2 for project search dropdown (single select, allow search)
    $('#projectSearchDropdown').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search project...',
        allowClear: true,
        width: 'resolve',
        dropdownParent: $('#projectSearchDropdown').parent(),
        minimumResultsForSearch: 0
    });
    // Always apply Bootstrap 5 form-select class to Select2 selection for border
    function fixSelect2Border() {
        $('.select2-container--bootstrap-5 .select2-selection').addClass('form-select').css({
            'border': '2px solid #e2e8f0',
            'min-height': '38px',
            'background': '#fff',
            'box-shadow': 'none'
        });
    }
    fixSelect2Border();
    $('#projectSearchDropdown').on('select2:open', fixSelect2Border);
    $('#projectSearchDropdown').on('select2:close', fixSelect2Border);
    $('#projectSearchDropdown').on('change', fixSelect2Border);

    const statusFilter = document.getElementById('statusFilter');
    const projectSearchDropdown = document.getElementById('projectSearchDropdown');
    const projectCards = Array.from(document.querySelectorAll('[data-status-code]')).map(card => {
        const titleEl = card.querySelector('h4');
        const title = titleEl ? titleEl.textContent.trim().toLowerCase() : '';
        const id = card.getAttribute('data-project-id') || card.querySelector('a.btn')?.href.split('/').pop();
        return {
            el: card,
            status: card.getAttribute('data-status-code'),
            title: title,
            id: id
        };
    });

    function filterProjects() {
        const statusVal = statusFilter.value;
        const selectedProjectId = projectSearchDropdown.value;
        projectCards.forEach(card => {
            const statusMatch = !statusVal || card.status === statusVal;
            const projectMatch = !selectedProjectId || card.id == selectedProjectId;
            card.el.style.display = (statusMatch && projectMatch) ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', filterProjects);
    $('#projectSearchDropdown').on('change', filterProjects);
});

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    $('#projectSearchDropdown').val('').trigger('change');
    const projectCards = Array.from(document.querySelectorAll('[data-status-code]'));
    projectCards.forEach(card => {
        card.style.display = '';
    });
}
</script>