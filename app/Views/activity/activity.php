<!-- Modern Activity List View -->

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;">
        <ol style="display: flex; list-style: none; padding: 1rem 1.25rem; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2); border: none;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 0.375rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                    <i class="fas fa-tachometer-alt" style="margin-right: 0.5rem; font-size: 0.9rem;"></i>
                    Dashboard
                </a>
                <span style="margin: 0 0.75rem; color: #e2e8f0; font-size: 1.1rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; padding: 0.25rem 0.5rem; background: rgba(255,255,255,0.1); border-radius: 0.375rem; backdrop-filter: blur(10px);">
                <i class="fas fa-tasks" style="margin-right: 0.5rem; font-size: 0.85rem; opacity: 0.9;"></i>
                Activities
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
            <h1 style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #1f2937; margin: 0; font-size: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-tasks" style="color: #667eea; font-size: 1.8rem;"></i>
                Activity Management
            </h1>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="text" id="projectSearch" class="form-control" placeholder="Search projects..." style="min-width: 220px; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                <select id="statusFilter" class="form-select" style="min-width: 160px; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                    <option value="">All Status</option>
                    <?php foreach ($status_options as $status): ?>
                        <option value="<?= esc($status['code']) ?>"><?= esc($status['name']) ?></option>
                    <?php endforeach; ?>
                </select>
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
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const projectSearch = document.getElementById('projectSearch');
    const projectCards = Array.from(document.querySelectorAll('[data-status-code]')).map(card => {
        // Find the project title/name text for search
        const titleEl = card.querySelector('h4');
        const title = titleEl ? titleEl.textContent.trim().toLowerCase() : '';
        return {
            el: card,
            status: card.getAttribute('data-status-code'),
            title: title
        };
    });

    function filterProjects() {
        const statusVal = statusFilter.value;
        const searchVal = projectSearch.value.trim().toLowerCase();
        projectCards.forEach(card => {
            const statusMatch = !statusVal || card.status === statusVal;
            const searchMatch = !searchVal || card.title.includes(searchVal);
            card.el.style.display = (statusMatch && searchMatch) ? '' : 'none';
        });
    }

    statusFilter.addEventListener('change', filterProjects);
    projectSearch.addEventListener('input', filterProjects);
});
</script>