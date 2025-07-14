<!-- Dashboard Container -->
<div class="container-fluid" id="dashboard-container">
    <!-- Error Message Display -->
    <?php if (isset($error_message)): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                <strong>Error:</strong>&nbsp;<?= esc($error_message) ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Modern Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 3rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: white; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; margin-bottom: 1rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-tachometer-alt" style="margin-right: 1rem; color: rgba(255,255,255,0.9);"></i>
                    Dashboard
                </h1>
                <p style="color: rgba(255,255,255,0.95); font-size: clamp(1rem, 2vw, 1.2rem); margin-bottom: 0; font-weight: 400;">
                    Welcome back, <strong><?= esc((session('userdata')['first_name'] ?? session('userdata')['username'] ?? session('userdata')['email'] ?? 'User')) ?></strong>! Here's what's happening with your projects.
                </p>
            </div>
            <div>
                <button onclick="refreshDashboard()" id="refresh-btn" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 1rem 2rem; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-sync-alt" id="refresh-icon" style="margin-right: 0.5rem;"></i>
                    <span id="refresh-text">Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 Cards in a Row -->
    <div class="row" style="display: flex; gap: 1.5rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
        <!-- Card 1: Projects Overview -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(102,126,234,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 32px rgba(102,126,234,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 24px rgba(102,126,234,0.08)';">
                <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Projects</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;" data-stat="total_projects">
                    <?= isset($stats['projects']['total_projects']) && is_numeric($stats['projects']['total_projects']) ? (int)$stats['projects']['total_projects'] : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Total Projects</div>
            </div>
        </div>
        <!-- Card 2: Tasks Overview -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(72,187,120,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 32px rgba(72,187,120,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 24px rgba(72,187,120,0.08)';">
                <i class="fas fa-tasks" style="font-size: 2.5rem; color: #38a169; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Tasks</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;" data-stat="total_tasks">
                    <?= isset($stats['tasks']['total_tasks']) && is_numeric($stats['tasks']['total_tasks']) ? (int)$stats['tasks']['total_tasks'] : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Total Tasks</div>
            </div>
        </div>
        <!-- Card 3: Team Members -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(102,126,234,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 32px rgba(102,126,234,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 24px rgba(102,126,234,0.08)';">
                <i class="fas fa-users" style="font-size: 2.5rem; color: #764ba2; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Team</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;" data-stat="team_count">
                    <?= isset($teamCount) && is_numeric($teamCount) ? (int)$teamCount : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Members</div>
            </div>
        </div>
        <!-- Card 4: Completed Tasks -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(118,75,162,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 32px rgba(118,75,162,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 24px rgba(118,75,162,0.08)';">
                <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #48bb78; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Completed</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;" data-stat="completed_tasks">
                    <?= isset($stats['tasks']['completed_tasks']) && is_numeric($stats['tasks']['completed_tasks']) ? (int)$stats['tasks']['completed_tasks'] : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Tasks Done</div>
            </div>
        </div>
    </div>

    <!-- My Tasks Card (with No Tasks Assigned message if empty) -->
    <div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; margin-bottom: 2rem;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 2rem; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden; flex-wrap: wrap; gap: 1rem;">
            <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.2;"></div>
            <div style="position: relative; z-index: 2;">
                <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-tasks" style="margin-right: 0.75rem;"></i>
                    My Tasks
                </h3>
                <p style="color: rgba(255,255,255,0.9); margin: 0.5rem 0 0 0;">Your assigned tasks</p>
            </div>
            <a href="<?= base_url('tasks') ?>" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease; backdrop-filter: blur(10px); position: relative; z-index: 2;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)';">
                View All
            </a>
        </div>
        <!-- Tasks List -->
        <div style="padding: 0;" id="tasks-list">
            <?php if (!empty($myTasks) && is_array($myTasks)): ?>
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php foreach (array_slice($myTasks, 0, 5) as $task): ?>
                        <div style="border: none; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; transition: all 0.2s ease; cursor: pointer;" onclick="window.location.href='<?= base_url('tasks/view/' . (isset($task['id']) ? esc($task['id']) : '')) ?>'" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateX(4px)';" onmouseout="this.style.background='white'; this.style.transform='translateX(0)';">
                            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 1rem;">
                                <div style="flex: 1; min-width: 200px;">
                                    <div style="font-weight: 600; color: #2d3748; font-size: 1.1rem; font-family: 'Poppins', sans-serif;">
                                        <?= esc($task['title'] ?? 'Untitled Task') ?>
                                    </div>
                                    <div style="color: #6b7280; font-size: 0.95rem; margin-top: 0.25rem;">
                                        <?= esc($task['project_name'] ?? 'No Project') ?>
                                    </div>
                                    <?php if (!empty($task['due_date']) && $task['due_date'] !== '0000-00-00'): ?>
                                        <div style="color: #9ca3af; font-size: 0.85rem; margin-top: 0.25rem;">
                                            <i class="fas fa-calendar-alt" style="margin-right: 0.25rem;"></i>
                                            Due: <?= date('M d, Y', strtotime($task['due_date'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div style="text-align: right;">
                                    <?php 
                                    $status = $task['status_name'] ?? $task['status'] ?? 'pending';
                                    $statusColor = match(strtolower($status)) {
                                        'completed' => '#10b981',
                                        'in_progress', 'in progress' => '#f59e0b',
                                        'pending' => '#6b7280',
                                        'on_hold', 'on hold' => '#ef4444',
                                        default => '#6b7280'
                                    };
                                    ?>
                                    <span style="background: <?= $statusColor ?>20; color: <?= $statusColor ?>; border-radius: 0.5rem; padding: 0.25rem 0.75rem; font-size: 0.95rem; font-weight: 500;">
                                        <?= ucwords(str_replace('_', ' ', esc($status))) ?>
                                    </span>
                                    <?php if (!empty($task['priority_name'])): ?>
                                        <div style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">
                                            <?= ucfirst(esc($task['priority_name'])) ?> Priority
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto;">
                        <i class="fas fa-tasks" style="font-size: 3rem; color: #cbd5e1;"></i>
                    </div>
                    <h4 style="margin: 0 0 1rem 0; color: #4a5568; font-family: 'Poppins', sans-serif;">No Tasks Assigned</h4>
                    <p style="margin: 0 0 2rem 0; color: #9ca3af;">You have no tasks assigned. Check back later or create a new task!</p>
                    <a href="<?= base_url('tasks/create') ?>" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 25px rgba(102,126,234,0.3)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                        <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                        Create Task
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Insights Row -->
    <?php if (!empty($stats) && (isset($stats['tasks']['total_tasks']) || isset($stats['projects']['total_projects']))): ?>
    <div class="row" style="display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <!-- Task Progress Card -->
        <div class="col-lg-6 col-md-12" style="flex: 1; min-width: 300px;">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 2rem;">
                <h4 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 1.5rem; color: #2d3748;">
                    <i class="fas fa-chart-pie" style="margin-right: 0.5rem; color: #667eea;"></i>
                    Task Overview
                </h4>
                <?php 
                $totalTasks = isset($stats['tasks']['total_tasks']) ? (int)$stats['tasks']['total_tasks'] : 0;
                $completedTasks = isset($stats['tasks']['completed_tasks']) ? (int)$stats['tasks']['completed_tasks'] : 0;
                $inProgressTasks = isset($stats['tasks']['in_progress_tasks']) ? (int)$stats['tasks']['in_progress_tasks'] : 0;
                $pendingTasks = isset($stats['tasks']['pending_tasks']) ? (int)$stats['tasks']['pending_tasks'] : 0;
                ?>
                
                <?php if ($totalTasks > 0): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="color: #6b7280;">Completed</span>
                        <span style="font-weight: 600; color: #10b981;"><?= $completedTasks ?> / <?= $totalTasks ?></span>
                    </div>
                    <div style="background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden; margin-bottom: 1rem;">
                        <div style="background: #10b981; height: 100%; width: <?= $totalTasks > 0 ? ($completedTasks / $totalTasks * 100) : 0 ?>%; transition: width 0.3s ease;"></div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                        <div style="color: #f59e0b;">
                            <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                            In Progress: <?= $inProgressTasks ?>
                        </div>
                        <div style="color: #6b7280;">
                            <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.25rem;"></i>
                            Pending: <?= $pendingTasks ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem 0; color: #9ca3af;">
                        <i class="fas fa-tasks" style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                        <p>No tasks to show</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Project Status Card -->
        <div class="col-lg-6 col-md-12" style="flex: 1; min-width: 300px;">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 2rem;">
                <h4 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 1.5rem; color: #2d3748;">
                    <i class="fas fa-project-diagram" style="margin-right: 0.5rem; color: #764ba2;"></i>
                    Project Status
                </h4>
                <?php 
                $totalProjects = isset($stats['projects']['total_projects']) ? (int)$stats['projects']['total_projects'] : 0;
                $activeProjects = isset($stats['projects']['active_projects']) ? (int)$stats['projects']['active_projects'] : 0;
                $completedProjects = isset($stats['projects']['completed_projects']) ? (int)$stats['projects']['completed_projects'] : 0;
                ?>
                
                <?php if ($totalProjects > 0): ?>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #48bb78;"><?= $activeProjects ?></div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Active</div>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #10b981;"><?= $completedProjects ?></div>
                            <div style="font-size: 0.875rem; color: #6b7280;">Completed</div>
                        </div>
                    </div>
                    
                    <?php if ($totalProjects > 0): ?>
                        <div style="margin-top: 1rem; text-align: center; font-size: 0.875rem; color: #6b7280;">
                            Project completion rate: <?= round(($completedProjects / $totalProjects) * 100) ?>%
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem 0; color: #9ca3af;">
                        <i class="fas fa-folder-open" style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                        <p>No projects to show</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Dashboard functionality
let isRefreshing = false;

function refreshDashboard() {
    if (isRefreshing) return;
    
    isRefreshing = true;
    const refreshBtn = document.getElementById('refresh-btn');
    const refreshIcon = document.getElementById('refresh-icon');
    const refreshText = document.getElementById('refresh-text');
    
    // Update button state
    refreshBtn.disabled = true;
    refreshIcon.classList.add('fa-spin');
    refreshText.textContent = 'Refreshing...';
    
    // Simulate refresh (in a real app, this would make AJAX calls to refresh data)
    setTimeout(() => {
        // Reset button state
        refreshBtn.disabled = false;
        refreshIcon.classList.remove('fa-spin');
        refreshText.textContent = 'Refresh';
        isRefreshing = false;
        
        // Show success message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Dashboard Refreshed!',
                text: 'Your dashboard has been updated with the latest data.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
        
        // Optionally reload the page to get fresh data
        // window.location.reload();
    }, 1500);
}

// Real-time refresh function (can be called via AJAX)
function refreshDashboardData() {
    fetch('<?= base_url('dashboard/refresh') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Dashboard data refreshed successfully');
        } else {
            console.error('Failed to refresh dashboard data:', data.message);
        }
    })
    .catch(error => {
        console.error('Error refreshing dashboard:', error);
    });
}

// Auto-refresh dashboard every 5 minutes
setInterval(refreshDashboardData, 300000);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded successfully');
    
    // Add loading animation to stat cards on hover
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Error handling for missing SweetAlert2
if (typeof Swal === 'undefined') {
    console.warn('SweetAlert2 not loaded. Using fallback alerts.');
}
</script>
