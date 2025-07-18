<!-- Project Reports Page -->

<div class="container-fluid">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h2 mb-2">
                    <i class="fas fa-project-diagram me-2"></i>
                    Project Reports
                </h1>
                <p class="mb-0 opacity-75">Comprehensive analysis of project performance and team productivity</p>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="<?= base_url('reports') ?>" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
                    <a href="<?= base_url('reports/export/projects') ?>" class="btn btn-light">
                        <i class="fas fa-download me-2"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 700; color: #667eea; margin-bottom: 0.25rem;"><?= count($detailedProjectStats) ?></div>
            <div style="color: #6b7280; font-size: 0.875rem; font-weight: 500;">Total Projects</div>
        </div>
        <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin-bottom: 0.25rem;">
                <?= count(array_filter($detailedProjectStats, function($stat) { return $stat['project']['status'] === 'active'; })) ?>
            </div>
            <div style="color: #6b7280; font-size: 0.875rem; font-weight: 500;">Active Projects</div>
        </div>
        <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 700; color: #3b82f6; margin-bottom: 0.25rem;">
                <?= count(array_filter($detailedProjectStats, function($stat) { return $stat['project']['status'] === 'completed'; })) ?>
            </div>
            <div style="color: #6b7280; font-size: 0.875rem; font-weight: 500;">Completed</div>
        </div>
        <div style="background: white; border-radius: 0.75rem; padding: 1.25rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 1.75rem; font-weight: 700; color: #ef4444; margin-bottom: 0.25rem;">
                <?= count(array_filter($detailedProjectStats, function($stat) { return $stat['is_overdue']; })) ?>
            </div>
            <div style="color: #6b7280; font-size: 0.875rem; font-weight: 500;">Overdue</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="margin-bottom: 2rem;">
        <h5 style="margin-bottom: 1rem; color: #374151; font-weight: 600;">
            <i class="fas fa-filter me-2"></i>
            Filter Projects
        </h5>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            <button onclick="filterProjects('all')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: 2px solid #667eea; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; cursor: pointer;" class="filter-active">All Projects</button>
            <button onclick="filterProjects('active')" style="background: #f8f9fa; color: #495057; border: 2px solid #e9ecef; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; cursor: pointer;" onmouseover="if(!this.classList.contains('filter-active')){this.style.background='#e9ecef'}" onmouseout="if(!this.classList.contains('filter-active')){this.style.background='#f8f9fa'}">Active</button>
            <button onclick="filterProjects('completed')" style="background: #f8f9fa; color: #495057; border: 2px solid #e9ecef; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; cursor: pointer;" onmouseover="if(!this.classList.contains('filter-active')){this.style.background='#e9ecef'}" onmouseout="if(!this.classList.contains('filter-active')){this.style.background='#f8f9fa'}">Completed</button>
            <button onclick="filterProjects('on_hold')" style="background: #f8f9fa; color: #495057; border: 2px solid #e9ecef; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; cursor: pointer;" onmouseover="if(!this.classList.contains('filter-active')){this.style.background='#e9ecef'}" onmouseout="if(!this.classList.contains('filter-active')){this.style.background='#f8f9fa'}">On Hold</button>
            <button onclick="filterProjects('overdue')" style="background: #f8f9fa; color: #495057; border: 2px solid #e9ecef; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; cursor: pointer;" onmouseover="if(!this.classList.contains('filter-active')){this.style.background='#e9ecef'}" onmouseout="if(!this.classList.contains('filter-active')){this.style.background='#f8f9fa'}">Overdue</button>
        </div>
    </div>

    <!-- Project Cards -->
    <div class="row" id="projectsContainer">
        <?php foreach ($detailedProjectStats as $stat): ?>
            <div class="col-lg-6 project-item" data-status="<?= esc($stat['project']['status']) ?>" data-overdue="<?= $stat['is_overdue'] ? 'true' : 'false' ?>">
                <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 1.5rem; transition: all 0.2s ease; border-left: 4px solid <?= $stat['project']['status'] === 'active' ? '#10b981' : ($stat['project']['status'] === 'completed' ? '#3b82f6' : ($stat['project']['status'] === 'on_hold' ? '#f59e0b' : '#ef4444')) ?>;"
                     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)'">
                    
                    <!-- Project Header -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <h6 style="color: #1f2937; font-weight: 600; font-size: 1.1rem; margin-bottom: 0.25rem;"><?= esc($stat['project']['name']) ?></h6>
                            <div style="color: #6b7280; font-size: 0.85rem;">
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('M j, Y', strtotime($stat['project']['start_date'])) ?> - 
                                <?= date('M j, Y', strtotime($stat['project']['end_date'])) ?>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="padding: 0.375rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; background: <?= $stat['project']['status'] === 'active' ? '#d1fae5' : ($stat['project']['status'] === 'completed' ? '#dbeafe' : ($stat['project']['status'] === 'on_hold' ? '#fef3c7' : '#fee2e2')) ?>; color: <?= $stat['project']['status'] === 'active' ? '#065f46' : ($stat['project']['status'] === 'completed' ? '#1e40af' : ($stat['project']['status'] === 'on_hold' ? '#92400e' : '#991b1b')) ?>;">
                                <?= ucfirst(str_replace('_', ' ', esc($stat['project']['status']))) ?>
                            </span>
                            <?php if ($stat['is_overdue']): ?>
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Overdue
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <?php if (!empty($stat['project']['description'])): ?>
                        <p style="color: #6b7280; margin-bottom: 1rem; font-size: 0.875rem;">
                            <?= esc(substr($stat['project']['description'], 0, 150)) ?><?= strlen($stat['project']['description']) > 150 ? '...' : '' ?>
                        </p>
                    <?php endif; ?>

                    <!-- Task Completion Progress -->
                    <div style="margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.875rem; color: #374151;">Task Completion</span>
                            <span style="font-weight: 600; color: #374151;"><?= $stat['completion_rate'] ?>%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; background: <?= $stat['completion_rate'] >= 75 ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : ($stat['completion_rate'] >= 50 ? 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)') ?>; border-radius: 4px; width: <?= $stat['completion_rate'] ?>%; transition: width 0.5s ease;"></div>
                        </div>
                    </div>

                    <!-- Time Progress -->
                    <div style="margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.875rem; color: #374151;">Time Progress</span>
                            <span style="font-weight: 600; color: #374151;"><?= $stat['time_progress'] ?>%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; background: <?= $stat['time_progress'] <= 100 ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' ?>; border-radius: 4px; width: <?= min(100, $stat['time_progress']) ?>%; transition: width 0.5s ease;"></div>
                        </div>
                    </div>

                    <!-- Project Statistics -->
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin: 1rem 0;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.5rem;">
                            <div style="width: 32px; height: 32px; background: #e0f2fe; color: #0277bd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-tasks" style="font-size: 0.875rem;"></i>
                            </div>
                            <span style="font-size: 0.875rem; color: #374151;"><?= $stat['total_tasks'] ?> total tasks</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.5rem;">
                            <div style="width: 32px; height: 32px; background: #d1fae5; color: #065f46; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check" style="font-size: 0.875rem;"></i>
                            </div>
                            <span style="font-size: 0.875rem; color: #374151;"><?= $stat['completed_tasks'] ?> completed</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.5rem;">
                            <div style="width: 32px; height: 32px; background: #fef3c7; color: #92400e; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock" style="font-size: 0.875rem;"></i>
                            </div>
                            <span style="font-size: 0.875rem; color: #374151;"><?= $stat['in_progress_tasks'] ?> in progress</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.5rem;">
                            <div style="width: 32px; height: 32px; background: #fee2e2; color: #991b1b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-pause" style="font-size: 0.875rem;"></i>
                            </div>
                            <span style="font-size: 0.875rem; color: #374151;"><?= $stat['pending_tasks'] ?> pending</span>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                        <div class="row text-center">
                            <div class="col-4">
                                <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Days Remaining</div>
                                <div style="font-weight: 600; color: <?= $stat['is_overdue'] ? '#dc2626' : '#3b82f6' ?>;">
                                    <?php if ($stat['is_overdue']): ?>
                                        <?= $stat['days_remaining'] ?> overdue
                                    <?php else: ?>
                                        <?= $stat['days_remaining'] ?> days
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Priority</div>
                                <div style="font-weight: 600; color: #374151;">
                                    <?= ucfirst(esc($stat['project']['priority'] ?? 'Medium')) ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem;">Created</div>
                                <div style="font-weight: 600; color: #374151;">
                                    <?= date('M j', strtotime($stat['project']['date_created'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($detailedProjectStats)): ?>
        <div style="text-align: center; padding: 3rem 0;">
            <i class="fas fa-project-diagram" style="font-size: 4rem; color: #6b7280; margin-bottom: 1rem;"></i>
            <h4 style="color: #6b7280; margin-bottom: 1rem;">No Projects Found</h4>
            <p style="color: #6b7280; margin-bottom: 2rem;">There are no projects to display in the reports.</p>
            <a href="<?= base_url('projects/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Create First Project
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function filterProjects(filter) {
    const projectItems = document.querySelectorAll('.project-item');
    const filterButtons = document.querySelectorAll('button[onclick*="filterProjects"]');
    
    // Update active button
    filterButtons.forEach(btn => {
        btn.classList.remove('filter-active');
        btn.style.background = '#f8f9fa';
        btn.style.color = '#495057';
        btn.style.borderColor = '#e9ecef';
    });
    
    event.target.classList.add('filter-active');
    event.target.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    event.target.style.color = 'white';
    event.target.style.borderColor = '#667eea';
    
    // Filter projects
    projectItems.forEach(item => {
        const status = item.dataset.status;
        const isOverdue = item.dataset.overdue === 'true';
        
        let shouldShow = false;
        
        switch(filter) {
            case 'all':
                shouldShow = true;
                break;
            case 'active':
                shouldShow = status === 'active';
                break;
            case 'completed':
                shouldShow = status === 'completed';
                break;
            case 'on_hold':
                shouldShow = status === 'on_hold';
                break;
            case 'overdue':
                shouldShow = isOverdue;
                break;
        }
        
        if (shouldShow) {
            item.style.display = 'block';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            }, 50);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.95)';
            setTimeout(() => {
                item.style.display = 'none';
            }, 200);
        }
    });
}

// Animate progress bars on load
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('[style*="width:"][style*="transition: width"]');
    progressBars.forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 500);
    });
});
</script>


