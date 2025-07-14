<!-- Modern Kanban Project Selection -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-columns" style="font-size: 1.8rem;"></i>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700; font-family: 'Poppins', sans-serif;">Kanban Board</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 1rem;">Select a project to view its Kanban board</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div style="max-width: 1200px; margin: 0 auto;">
        <?php if (!empty($projects)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
                <?php foreach ($projects as $project): ?>
                    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.12)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.08)'">
                        
                        <!-- Status Indicator -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, <?= getStatusColor($project['status_name']) == 'success' ? '#10b981, #059669' : (getStatusColor($project['status_name']) == 'warning' ? '#f59e0b, #d97706' : (getStatusColor($project['status_name']) == 'danger' ? '#ef4444, #dc2626' : '#3b82f6, #2563eb')) ?>);"></div>
                        
                        <!-- Card Content -->
                        <div style="padding: 2rem;">
                            <!-- Project Icon and Name -->
                            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-project-diagram" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; color: #1f2937; font-family: 'Poppins', sans-serif; line-height: 1.3;"><?= esc($project['name']) ?></h3>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <span style="background: <?= getStatusColor($project['status_name']) == 'success' ? 'linear-gradient(135deg, #d1fae5, #a7f3d0)' : (getStatusColor($project['status_name']) == 'warning' ? 'linear-gradient(135deg, #fef3c7, #fde68a)' : (getStatusColor($project['status_name']) == 'danger' ? 'linear-gradient(135deg, #fee2e2, #fecaca)' : 'linear-gradient(135deg, #dbeafe, #bfdbfe)')) ?>; color: <?= getStatusColor($project['status_name']) == 'success' ? '#065f46' : (getStatusColor($project['status_name']) == 'warning' ? '#92400e' : (getStatusColor($project['status_name']) == 'danger' ? '#991b1b' : '#1e40af')) ?>; padding: 0.375rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                            <?= ucfirst($project['status_name']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div style="margin-bottom: 2rem;">
                                <p style="color: #6b7280; line-height: 1.6; margin: 0; font-size: 0.95rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?= esc($project['description']) ?></p>
                            </div>

                            <!-- Project Stats -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                                <div style="text-align: center;">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #667eea; margin-bottom: 0.25rem;">
                                        <i class="fas fa-tasks" style="font-size: 1rem; margin-right: 0.5rem;"></i>
                                        <?= rand(3, 15) ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #6b7280; font-weight: 500;">Tasks</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #10b981; margin-bottom: 0.25rem;">
                                        <i class="fas fa-users" style="font-size: 1rem; margin-right: 0.5rem;"></i>
                                        <?= rand(2, 8) ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #6b7280; font-weight: 500;">Members</div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="<?= base_url('tasks/kanban/' . $project['id']) ?>" 
                               style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 1rem 1.5rem; border-radius: 0.75rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102,126,234,0.3);"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.3)'">
                                <i class="fas fa-columns" style="font-size: 1.1rem;"></i>
                                <span>View Kanban Board</span>
                                <i class="fas fa-arrow-right" style="font-size: 0.9rem;"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; padding: 4rem 2rem; text-align: center; max-width: 600px; margin: 0 auto;">
                <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto;">
                    <i class="fas fa-project-diagram" style="font-size: 3rem; color: #9ca3af;"></i>
                </div>
                <h3 style="margin: 0 0 1rem 0; font-size: 1.5rem; font-weight: 600; color: #374151; font-family: 'Poppins', sans-serif;">No Projects Found</h3>
                <p style="color: #6b7280; line-height: 1.6; margin: 0 0 2rem 0; font-size: 1rem;">You need to create or be assigned to a project to view Kanban boards.</p>
                <a href="<?= base_url('projects') ?>" 
                   style="display: inline-flex; align-items: center; gap: 0.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102,126,234,0.3);"
                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.3)'">
                    <i class="fas fa-plus" style="font-size: 1rem;"></i>
                    <span>Go to Projects</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>



<?php
function getStatusColor($status_name) {
    switch(strtolower($status_name)) {
        case 'active':
        case 'in_progress':
            return 'success';
        case 'planning':
            return 'info';
        case 'on_hold':
            return 'warning';
        case 'completed':
            return 'primary';
        default:
            return 'secondary';
    }
}
?>