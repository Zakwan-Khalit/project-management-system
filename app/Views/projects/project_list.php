
<!-- Modern Project List View -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
            <div style="background: #f8fafc; padding: 1rem 2rem; border-bottom: 1px solid #e2e8f0;">
                <nav style="font-size: 0.9rem;">
                    <a href="<?= base_url('dashboard') ?>" style="color: #667eea; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Dashboard</a>
                    <span style="margin: 0 0.5rem; color: #9ca3af;">/</span>
                    <span style="color: #6b7280; font-weight: 500;">Projects</span>
                </nav>
            </div>
            <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #3b3b3b; margin: 0; font-size: 2.2rem;"><i class="fas fa-folder-open me-2"></i>Project List</h2>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <input type="text" id="projectSearch" class="form-control" placeholder="Search projects..." style="min-width: 220px;">
                    <select id="statusFilter" class="form-select" style="min-width: 160px;">
                        <option value="">All Status</option>
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= esc($status['code']) ?>"><?= esc($status['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="priorityFilter" class="form-select" style="min-width: 160px;">
                        <option value="">All Priority</option>
                        <?php foreach ($priority_options as $priority): ?>
                            <option value="<?= esc($priority['code']) ?>"><?= esc($priority['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 2rem;">
            <?php foreach ($projects as $project): ?>
                <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    <div style="padding: 2rem; display: flex; flex-direction: column; gap: 1.2rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span class="badge bg-<?= esc($status_colors[$project['status_code'] ?? 'pending'] ?? 'secondary') ?>" style="font-size: 1rem; min-width: 80px; padding: 0.5rem 1rem;"><?= esc($project['status_name'] ?? 'Pending') ?></span>
                            <span class="badge bg-<?= esc($priority_colors[strtolower($project['priority_name'] ?? 'medium')] ?? 'secondary') ?>" style="font-size: 1rem; min-width: 80px; padding: 0.5rem 1rem;"><?= esc($project['priority_name'] ?? 'Medium') ?></span>
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
                                <div style="height: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: <?= round($project['progress'] ?? 0) ?>%; transition: width 0.5s;"></div>
                            </div>
                            <div style="font-size: 0.95rem; color: #667eea; font-weight: 600; margin-top: 0.5rem;">Progress: <?= round($project['progress'] ?? 0) ?>%</div>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <a href="<?= base_url('projects/project_task/' . $project['id']) ?>" class="btn btn-outline-primary btn-sm" title="View Project Tasks" style="font-weight: 600; border-radius: 0.5rem;">
                                <i class="fas fa-eye"></i> View Tasks
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
