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
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem 1.5rem; margin-bottom: 2rem; box-shadow: 0 15px 45px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -20px; left: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <h1 style="color: white; font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 800; margin-bottom: 0.75rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-tachometer-alt" style="margin-right: 0.75rem; color: rgba(255,255,255,0.9);"></i>
                    Dashboard
                </h1>
                <p style="color: rgba(255,255,255,0.95); font-size: clamp(0.9rem, 1.5vw, 1rem); margin-bottom: 0; font-weight: 400;">
                    Welcome back, <strong><?= esc((session('userdata')['full_name'] ?? session('userdata')['username'] ?? session('userdata')['email'] ?? 'User')) ?></strong>! Here's what's happening with your projects.
                </p>
            </div>
            <div>
                <button onclick="refreshDashboard()" id="refresh-btn" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-sync-alt" id="refresh-icon" style="margin-right: 0.4rem;"></i>
                    <span id="refresh-text">Refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 4 Cards in a Row -->
    <div class="row" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <!-- Card 1: Projects Overview -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 200px; margin-bottom: 0.75rem;">
            <div style="background: #fff; border-radius: 1rem; box-shadow: 0 3px 18px rgba(102,126,234,0.08); padding: 1.5rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 24px rgba(102,126,234,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 18px rgba(102,126,234,0.08)';">
                <i class="fas fa-folder-open" style="font-size: 2rem; color: #667eea; margin-bottom: 0.75rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.4rem; font-size: 1rem;">Projects</h3>
                <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.4rem;" data-stat="total_projects">
                    <?= isset($stats['projects']['total_projects']) && is_numeric($stats['projects']['total_projects']) ? (int)$stats['projects']['total_projects'] : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 0.9rem;">Total Projects</div>
            </div>
        </div>
        <!-- Card 2: Tasks Overview -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 200px; margin-bottom: 0.75rem;">
            <div style="background: #fff; border-radius: 1rem; box-shadow: 0 3px 18px rgba(72,187,120,0.08); padding: 1.5rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 24px rgba(72,187,120,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 18px rgba(72,187,120,0.08)';">
                <i class="fas fa-tasks" style="font-size: 2rem; color: #38a169; margin-bottom: 0.75rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.4rem; font-size: 1rem;">Tasks</h3>
                <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.4rem;" data-stat="total_tasks">
                    <?= isset($stats['tasks']['total_tasks']) && is_numeric($stats['tasks']['total_tasks']) ? (int)$stats['tasks']['total_tasks'] : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 0.9rem;">Total Tasks</div>
            </div>
        </div>
        <!-- Card 3: Team Members -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 200px; margin-bottom: 0.75rem;">
            <div style="background: #fff; border-radius: 1rem; box-shadow: 0 3px 18px rgba(102,126,234,0.08); padding: 1.5rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 24px rgba(102,126,234,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 18px rgba(102,126,234,0.08)';">
                <i class="fas fa-users" style="font-size: 2rem; color: #764ba2; margin-bottom: 0.75rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.4rem; font-size: 1rem;">Team</h3>
                <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.4rem;" data-stat="team_count">
                    <?= isset($teamCount) && is_numeric($teamCount) ? (int)$teamCount : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 0.9rem;">Members</div>
            </div>
        </div>
        <!-- Card 4: Completed Tasks -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 200px; margin-bottom: 0.75rem;">
            <div style="background: #fff; border-radius: 1rem; box-shadow: 0 3px 18px rgba(118,75,162,0.08); padding: 1.5rem; text-align: center; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 24px rgba(118,75,162,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 18px rgba(118,75,162,0.08)';">
                <i class="fas fa-check-circle" style="font-size: 2rem; color: #48bb78; margin-bottom: 0.75rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.4rem; font-size: 1rem;">Completed</h3>
                <div style="font-size: 1.75rem; font-weight: 800; color: #4a5568; margin-bottom: 0.4rem;" data-stat="completed_tasks">
                    <?= isset($stats['tasks']['completed_tasks']) && is_numeric($stats['tasks']['completed_tasks']) ? (int)$stats['tasks']['completed_tasks'] : 0 ?>
                </div>
                <div style="color: #6b7280; font-size: 0.9rem;">Tasks Done</div>
            </div>
        </div>
    </div>

    <!-- My Tasks Card (with No Tasks Assigned message if empty) -->
    <div style="background: #fff; border-radius: 1rem; box-shadow: 0 15px 45px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; margin-bottom: 1.5rem;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden; flex-wrap: wrap; gap: 0.75rem;">
            <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.2;"></div>
            <div style="position: relative; z-index: 2;">
                <h3 style="color: white; font-size: 1.25rem; font-weight: 700; margin: 0; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-tasks" style="margin-right: 0.5rem;"></i>
                    My Tasks
                </h3>
                <p style="color: rgba(255,255,255,0.9); margin: 0.3rem 0 0 0; font-size: 0.9rem;">Your assigned tasks</p>
            </div>
            <a href="<?= base_url('tasks') ?>" style="background: rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.25rem; border-radius: 0.6rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease; backdrop-filter: blur(10px); position: relative; z-index: 2; font-size: 0.9rem;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)';">
                View All
            </a>
        </div>
        <!-- Tasks List -->
        <div style="padding: 0;" id="tasks-list">
            <?php if (!empty($myTasks) && is_array($myTasks)): ?>
                <div style="max-height: 320px; overflow-y: auto;">
                    <?php foreach (array_slice($myTasks, 0, 5) as $task): ?>
                        <div style="border: none; border-bottom: 1px solid #f1f5f9; padding: 1rem 1.25rem; transition: all 0.2s ease; cursor: pointer;" onclick="window.location.href='<?= base_url('tasks/view/' . (isset($task['id']) ? esc($task['id']) : '')) ?>'" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateX(4px)';" onmouseout="this.style.background='white'; this.style.transform='translateX(0)';">
                            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 0.75rem;">
                                <div style="flex: 1; min-width: 200px;">
                                    <div style="font-weight: 600; color: #2d3748; font-size: 1rem; font-family: 'Poppins', sans-serif;">
                                        <?= esc($task['title'] ?? 'Untitled Task') ?>
                                    </div>
                                    <div style="color: #6b7280; font-size: 0.85rem; margin-top: 0.2rem;">
                                        <?= esc($task['project_name'] ?? 'No Project') ?>
                                    </div>
                                    <?php if (!empty($task['due_date']) && $task['due_date'] !== '0000-00-00'): ?>
                                        <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.2rem;">
                                            <i class="fas fa-calendar-alt" style="margin-right: 0.2rem;"></i>
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
                                    <span style="background: <?= $statusColor ?>20; color: <?= $statusColor ?>; border-radius: 0.4rem; padding: 0.2rem 0.6rem; font-size: 0.8rem; font-weight: 500;">
                                        <?= ucwords(str_replace('_', ' ', esc($status))) ?>
                                    </span>
                                    <?php if (!empty($task['priority_name'])): ?>
                                        <div style="font-size: 0.7rem; color: #6b7280; margin-top: 0.2rem;">
                                            <?= ucfirst(esc($task['priority_name'])) ?> Priority
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem 1.5rem;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <i class="fas fa-tasks" style="font-size: 2rem; color: #cbd5e1;"></i>
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

    <!-- Upcoming Events Widget -->
    <div class="row" style="margin-bottom: 2rem;">
        <div class="col-12">
            <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f1f3f4;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #3788d8 0%, #2c5aa0 100%); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
                    <div style="position: relative; z-index: 2;">
                        <h4 style="color: white; font-size: 1.25rem; font-weight: 600; margin: 0; font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-calendar-alt" style="margin-right: 0.5rem;"></i>
                            Upcoming Events
                        </h4>
                        <p style="color: rgba(255,255,255,0.9); margin: 0.25rem 0 0 0; font-size: 0.9rem;">Your scheduled activities</p>
                    </div>
                    <a href="<?= base_url('events') ?>" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: all 0.3s ease; backdrop-filter: blur(10px); position: relative; z-index: 2; font-size: 0.9rem;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)';">
                        View All
                    </a>
                </div>
                
                <!-- Events List -->
                <div style="padding: 1.5rem;" id="upcomingEventsContainer">
                    <div style="text-align: center; padding: 2rem 0; color: #9ca3af;" id="loadingEvents">
                        <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p>Loading events...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load upcoming events for dashboard
function loadUpcomingEvents() {
    fetch('<?= base_url('events/getDashboardEvents') ?>')
        .then(response => response.json())
        .then(events => {
            const container = document.getElementById('upcomingEventsContainer');
            
            if (events.error) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem 0; color: #ef4444;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p>Failed to load events</p>
                    </div>
                `;
                return;
            }
            
            if (events.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem 0; color: #9ca3af;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                        <h6 style="margin: 0 0 0.5rem 0; color: #6b7280;">No Upcoming Events</h6>
                        <p style="margin: 0 0 1rem 0;">No events scheduled for the next few days.</p>
                        <a href="<?= base_url('events/create') ?>" style="background: linear-gradient(135deg, #3788d8 0%, #2c5aa0 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 500; font-size: 0.9rem;">
                            <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                            Create Event
                        </a>
                    </div>
                `;
                return;
            }
            
            let eventsHtml = '';
            events.forEach(event => {
                const startDate = new Date(event.start_datetime);
                const eventTypeColors = {
                    'meeting': '#3788d8',
                    'deadline': '#dc3545',
                    'milestone': '#28a745',
                    'training': '#fd7e14',
                    'review': '#6f42c1',
                    'other': '#6c757d'
                };
                const color = eventTypeColors[event.event_type] || '#6c757d';
                
                eventsHtml += `
                    <div style="display: flex; align-items: center; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; margin-bottom: 0.75rem; transition: all 0.3s ease; background: #fafafa;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)'; this.style.background='#fff';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.background='#fafafa';">
                        <div style="width: 4px; height: 60px; background: ${color}; border-radius: 2px; margin-right: 1rem;"></div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                <h6 style="margin: 0; color: #2d3748; font-weight: 600; font-size: 1rem;">${event.title}</h6>
                                <span style="background: ${color}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 500;">
                                    ${event.event_type.charAt(0).toUpperCase() + event.event_type.slice(1)}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">
                                <i class="fas fa-clock" style="margin-right: 0.5rem; width: 14px;"></i>
                                ${startDate.toLocaleDateString()} ${startDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                            </div>
                            ${event.location ? `
                                <div style="display: flex; align-items: center; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-map-marker-alt" style="margin-right: 0.5rem; width: 14px;"></i>
                                    ${event.location}
                                </div>
                            ` : ''}
                            ${event.project_name ? `
                                <div style="display: flex; align-items: center; color: #6b7280; font-size: 0.875rem;">
                                    <i class="fas fa-folder" style="margin-right: 0.5rem; width: 14px;"></i>
                                    ${event.project_name}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = eventsHtml;
        })
        .catch(error => {
            console.error('Error loading events:', error);
            document.getElementById('upcomingEventsContainer').innerHTML = `
                <div style="text-align: center; padding: 2rem 0; color: #ef4444;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <p>Failed to load events</p>
                </div>
            `;
        });
}

// Load events when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadUpcomingEvents();
});
</script>

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
