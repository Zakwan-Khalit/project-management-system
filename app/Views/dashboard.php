<!-- Modern Dashboard Container -->
<div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">

<!-- Modern Dashboard Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 3rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
    <!-- Decorative Elements -->
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
        <div>
            <h1 style="color: white; font-size: 3rem; font-weight: 800; margin-bottom: 1rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-tachometer-alt" style="margin-right: 1rem; color: rgba(255,255,255,0.9);"></i>
                Dashboard
            </h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 1.2rem; margin-bottom: 0; font-weight: 400;">
                Welcome back, <strong><?= esc(user_name()) ?></strong>! Here's what's happening with your projects.
            </p>
        </div>
        <div>
            <button onclick="refreshDashboard()" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 1rem 2rem; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="fas fa-sync-alt" style="margin-right: 0.5rem;"></i>
                Refresh
            </button>
        </div>
    </div>
</div>

<!-- Simple demo content -->
<div style="background: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="color: #1a202c; margin-bottom: 1rem;">Welcome to Project Management System</h2>
    <p style="color: #6b7280;">This is your dashboard where you can manage projects and tasks efficiently.</p>
</div>

</div>

<!-- Basic JavaScript -->
<script>
function refreshDashboard() {
    Swal.fire({
        icon: 'success',
        title: 'Dashboard Refreshed!',
        text: 'Your dashboard has been updated.',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>

<!-- Modern Stats Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
    
    <!-- Total Projects Card -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; padding: 2.5rem 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(102,126,234,0.1); position: relative; overflow: hidden; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-12px) scale(1.02)'; this.style.boxShadow='0 30px 80px rgba(102,126,234,0.25)'; this.style.borderColor='rgba(102,126,234,0.3)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.08)'; this.style.borderColor='rgba(102,126,234,0.1)';">
        <!-- Gradient Border -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 1.5rem 1.5rem 0 0;"></div>
        
        <!-- Icon Background -->
        <div style="position: absolute; top: 1.5rem; right: 1.5rem; width: 80px; height: 80px; background: linear-gradient(135deg, #667eea20 0%, #764ba230 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-project-diagram" style="font-size: 2rem; color: #667eea; opacity: 0.7;"></i>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h3 style="font-size: 3.5rem; font-weight: 800; color: #1a202c; margin: 0; line-height: 1; font-family: 'Poppins', sans-serif;" data-count="<?= isset($stats['projects']['total_projects']) ? $stats['projects']['total_projects'] : 0 ?>">
                0
            </h3>
            <div>
                <p style="color: #4a5568; font-weight: 600; margin: 0; font-size: 1.1rem;">Total Projects</p>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-arrow-up" style="margin-right: 0.25rem;"></i>
                        <?= isset($stats['projects']['active_projects']) ? $stats['projects']['active_projects'] : 0 ?> active
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Tasks Card -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; padding: 2.5rem 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(72,187,120,0.1); position: relative; overflow: hidden; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-12px) scale(1.02)'; this.style.boxShadow='0 30px 80px rgba(72,187,120,0.25)'; this.style.borderColor='rgba(72,187,120,0.3)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.08)'; this.style.borderColor='rgba(72,187,120,0.1)';">
        <!-- Gradient Border -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 1.5rem 1.5rem 0 0;"></div>
        
        <!-- Icon Background -->
        <div style="position: absolute; top: 1.5rem; right: 1.5rem; width: 80px; height: 80px; background: linear-gradient(135deg, #48bb7820 0%, #38a16930 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-tasks" style="font-size: 2rem; color: #48bb78; opacity: 0.7;"></i>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h3 style="font-size: 3.5rem; font-weight: 800; color: #1a202c; margin: 0; line-height: 1; font-family: 'Poppins', sans-serif;" data-count="<?= isset($stats['tasks']['total_tasks']) ? $stats['tasks']['total_tasks'] : 0 ?>">
                0
            </h3>
            <div>
                <p style="color: #4a5568; font-weight: 600; margin: 0; font-size: 1.1rem;">Total Tasks</p>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                        <?= isset($stats['tasks']['pending_tasks']) ? $stats['tasks']['pending_tasks'] : 0 ?> pending
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Tasks Card -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; padding: 2.5rem 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(56,178,172,0.1); position: relative; overflow: hidden; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-12px) scale(1.02)'; this.style.boxShadow='0 30px 80px rgba(56,178,172,0.25)'; this.style.borderColor='rgba(56,178,172,0.3)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.08)'; this.style.borderColor='rgba(56,178,172,0.1)';">
        <!-- Gradient Border -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); border-radius: 1.5rem 1.5rem 0 0;"></div>
        
        <!-- Icon Background -->
        <div style="position: absolute; top: 1.5rem; right: 1.5rem; width: 80px; height: 80px; background: linear-gradient(135deg, #38b2ac20 0%, #31979530 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-check-circle" style="font-size: 2rem; color: #38b2ac; opacity: 0.7;"></i>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h3 style="font-size: 3.5rem; font-weight: 800; color: #1a202c; margin: 0; line-height: 1; font-family: 'Poppins', sans-serif;" data-count="<?= isset($stats['tasks']['completed_tasks']) ? $stats['tasks']['completed_tasks'] : 0 ?>">
                0
            </h3>
            <div>
                <p style="color: #4a5568; font-weight: 600; margin: 0; font-size: 1.1rem;">Completed</p>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <div style="background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-trophy" style="margin-right: 0.25rem;"></i>
                        This month
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Card -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; padding: 2.5rem 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(237,137,54,0.1); position: relative; overflow: hidden; cursor: pointer; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-12px) scale(1.02)'; this.style.boxShadow='0 30px 80px rgba(237,137,54,0.25)'; this.style.borderColor='rgba(237,137,54,0.3)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.08)'; this.style.borderColor='rgba(237,137,54,0.1)';">
        <!-- Gradient Border -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); border-radius: 1.5rem 1.5rem 0 0;"></div>
        
        <!-- Icon Background -->
        <div style="position: absolute; top: 1.5rem; right: 1.5rem; width: 80px; height: 80px; background: linear-gradient(135deg, #ed893620 0%, #dd6b2030 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-users" style="font-size: 2rem; color: #ed8936; opacity: 0.7;"></i>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h3 style="font-size: 3.5rem; font-weight: 800; color: #1a202c; margin: 0; line-height: 1; font-family: 'Poppins', sans-serif;" data-count="<?= isset($stats['team']['total_members']) ? $stats['team']['total_members'] : 0 ?>">
                0
            </h3>
            <div>
                <p style="color: #4a5568; font-weight: 600; margin: 0; font-size: 1.1rem;">Team Members</p>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;">
                        <i class="fas fa-user-plus" style="margin-right: 0.25rem;"></i>
                        Active
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Content Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
    
    <!-- Charts Section -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
            <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0; font-family: 'Poppins', sans-serif; position: relative; z-index: 2;">
                <i class="fas fa-chart-pie" style="margin-right: 0.75rem;"></i>
                Task Analytics
            </h3>
            <p style="color: rgba(255,255,255,0.9); margin: 0.5rem 0 0 0; position: relative; z-index: 2;">Project performance overview</p>
        </div>
        <div style="padding: 2rem;">
            <canvas id="taskStatusChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Progress Chart -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 2rem; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
            <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0; font-family: 'Poppins', sans-serif; position: relative; z-index: 2;">
                <i class="fas fa-chart-bar" style="margin-right: 0.75rem;"></i>
                Project Progress
            </h3>
            <p style="color: rgba(255,255,255,0.9); margin: 0.5rem 0 0 0; position: relative; z-index: 2;">Completion tracking</p>
        </div>
        <div style="padding: 2rem;">
            <canvas id="projectProgressChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Content Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
    
    <!-- Recent Projects Card -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 30px 80px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.08)';">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.2;"></div>
            <div style="position: relative; z-index: 2;">
                <h3 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-project-diagram" style="margin-right: 0.75rem;"></i>
                    Recent Projects
                </h3>
                <p style="color: rgba(255,255,255,0.9); margin: 0.5rem 0 0 0;">Latest project updates</p>
            </div>
            <a href="<?= base_url('projects') ?>" style="background: rgba(255,255,255,0.2); color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease; backdrop-filter: blur(10px); position: relative; z-index: 2;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.05)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)';">
                View All
            </a>
        </div>

        <!-- Projects List -->
        <div style="max-height: 400px; overflow-y: auto;">
            <?php if (!empty($projects)): ?>
                <?php foreach (array_slice($projects, 0, 5) as $index => $project): ?>
                    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: all 0.3s ease;" onclick="window.location.href='<?= base_url('projects/view/' . $project['id']) ?>'" onmouseover="this.style.background='linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)'; this.style.transform='translateX(8px)';" onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)';">
                        
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; font-weight: 600; color: #1a202c; font-family: 'Poppins', sans-serif;"><?= esc($project['name']) ?></h4>
                                <p style="margin: 0; color: #6b7280; font-size: 0.95rem; line-height: 1.5;"><?= esc(substr($project['description'] ?? '', 0, 100)) ?><?= strlen($project['description'] ?? '') > 100 ? '...' : '' ?></p>
                            </div>
                            <div style="margin-left: 1rem;">
                                <span style="background: linear-gradient(135deg, <?= $project['status'] === 'active' ? '#48bb78 0%, #38a169 100%' : ($project['status'] === 'completed' ? '#667eea 0%, #764ba2 100%' : '#ed8936 0%, #dd6b20 100%') ?>); color: white; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <?= esc(ucfirst($project['status'])) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div style="margin-bottom: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="font-size: 0.85rem; color: #4a5568; font-weight: 500;">Progress</span>
                                <span style="font-size: 0.85rem; color: #4a5568; font-weight: 600;"><?= $project['progress'] ?>%</span>
                            </div>
                            <div style="background: #e2e8f0; height: 8px; border-radius: 1rem; overflow: hidden;">
                                <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); height: 100%; width: <?= $project['progress'] ?>%; border-radius: 1rem; transition: all 0.6s ease;"></div>
                            </div>
                        </div>

                        <!-- Meta Info -->
                        <div style="display: flex; justify-content: space-between; align-items: center; color: #9ca3af; font-size: 0.8rem;">
                            <span><i class="fas fa-calendar" style="margin-right: 0.5rem;"></i>Due: <?= date('M j, Y', strtotime($project['end_date'] ?? 'now')) ?></span>
                            <span><i class="fas fa-users" style="margin-right: 0.5rem;"></i>Team: <?= $project['team_count'] ?? 0 ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem auto;">
                        <i class="fas fa-project-diagram" style="font-size: 3rem; color: #cbd5e0;"></i>
                    </div>
                    <h4 style="margin: 0 0 1rem 0; color: #4a5568; font-family: 'Poppins', sans-serif;">No Projects Yet</h4>
                    <p style="margin: 0 0 2rem 0; color: #9ca3af;">Create your first project to get started with your workflow!</p>
                    <a href="<?= base_url('projects/create') ?>" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 25px rgba(102,126,234,0.3)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                        <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                        Create First Project
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- My Tasks Card -->
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 30px 80px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.08)';">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 2rem; display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden;">
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
                </h5>
                <a href="<?= base_url('tasks') ?>" style="border: 2px solid #198754; color: #198754; background: transparent; border-radius: 0.5rem; padding: 0.375rem 0.75rem; font-weight: 500; text-decoration: none; font-size: 0.875rem;" onmouseover="this.style.background='#198754'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#198754';">
                    View All
                </a>
            </div>
            <div style="padding: 0;">
                <?php if (!empty($myTasks)): ?>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach (array_slice($myTasks, 0, 5) as $task): ?>
                            <div style="border: none; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; transition: all 0.2s ease; cursor: pointer;" onclick="window.location.href='<?= base_url('tasks/view/' . $task['id']) ?>'" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateX(4px)';" onmouseout="this.style.background='white'; this.style.transform='translateX(0)';">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 style="margin-bottom: 0.25rem; font-weight: 600;"><?= esc($task['title']) ?></h6>
                                        <p style="margin-bottom: 0.5rem; color: #6c757d; font-size: 0.875rem;"><?= esc($task['project_name'] ?? 'No Project') ?></p>
                                        <?php if (!empty($task['due_date'])): ?>
                                            <small style="color: #6c757d;">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Due: <?= date('M j, Y', strtotime($task['due_date'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end ms-3">
                                        <span style="padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500; background: linear-gradient(135deg, <?= $task['priority'] === 'high' ? '#ef4444 0%, #dc2626 100%' : ($task['priority'] === 'medium' ? '#f59e0b 0%, #d97706 100%' : '#10b981 0%, #059669 100%') ?>); color: white;" class="mb-1">
                                            <?= esc($task['priority']) ?>
                                        </span>
                                        <div>
                                            <span style="padding: 0.5rem 0.875rem; border-radius: 0.75rem; font-size: 0.775rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; background: linear-gradient(135deg, <?= $task['status'] === 'completed' ? '#10b981 0%, #059669 100%' : ($task['status'] === 'in_progress' ? '#3b82f6 0%, #2563eb 100%' : '#6b7280 0%, #4b5563 100%') ?>); color: white;">
                                                <?= esc(ucfirst(str_replace('_', ' ', $task['status']))) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center" style="padding: 3rem;">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Tasks Assigned</h5>
                        <p class="text-muted mb-0">You don't have any tasks assigned yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    initializeCounters();
    initializeCharts();
    
    // Auto-refresh every 5 minutes
    setInterval(refreshActivityFeed, 300000);
});

// Animated counters
function initializeCounters() {
    $('.stat-number').each(function() {
        const $counter = $(this);
        const target = parseInt($counter.attr('data-count') || $counter.text());
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            $counter.text(Math.floor(current));
        }, 16);
    });
}

// Initialize charts using AJAX
function initializeCharts() {
    // Task Status Pie Chart
    const taskStatusCtx = document.getElementById('taskStatusChart');
    if (taskStatusCtx) {
        $.ajax({
            url: '<?= base_url('home/getChartData') ?>',
            type: 'GET',
            data: { type: 'task_status' },
            dataType: 'json',
            success: function(data) {
                new Chart(taskStatusCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: data.labels || ['No Data'],
                        datasets: [{
                            data: data.data || [1],
                            backgroundColor: data.colors || ['#e9ecef'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            },
            error: function() {
                createEmptyChart(taskStatusCtx, 'doughnut', 'No Tasks Yet');
            }
        });
    }

    // Project Progress Bar Chart
    const projectProgressCtx = document.getElementById('projectProgressChart');
    if (projectProgressCtx) {
        $.ajax({
            url: '<?= base_url('home/getChartData') ?>',
            type: 'GET',
            data: { type: 'project_progress' },
            dataType: 'json',
            success: function(data) {
                new Chart(projectProgressCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.labels || ['No Projects'],
                        datasets: [{
                            label: 'Progress %',
                            data: data.data || [0],
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            },
            error: function() {
                createEmptyChart(projectProgressCtx, 'bar', 'No Projects');
            }
        });
    }
}

// Create empty chart helper
function createEmptyChart(ctx, type, label) {
    new Chart(ctx.getContext('2d'), {
        type: type,
        data: {
            labels: [label],
            datasets: [{
                data: type === 'bar' ? [0] : [1],
                backgroundColor: ['rgba(233, 236, 239, 0.8)'],
                borderColor: ['rgba(233, 236, 239, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Refresh functions using AJAX
function refreshDashboard() {
    Swal.fire({
        title: 'Refreshing Dashboard...',
        html: 'Updating your data, please wait...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '<?= base_url('home/refresh') ?>',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dashboard Updated!',
                    text: 'Your dashboard has been refreshed with the latest data.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: data.message || 'Unable to refresh dashboard'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Unable to connect to server. Refreshing page...'
            }).then(() => {
                window.location.reload();
            });
        }
    });
}

function refreshActivityFeed() {
    $.ajax({
        url: '<?= base_url('home/activityFeed') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success && data.activities) {
                updateActivityFeed(data.activities);
            }
        },
        error: function() {
            console.log('Auto-refresh activity feed failed');
        }
    });
}

function updateActivityFeed(activities) {
    const $feed = $('#activityFeed');
    if ($feed.length && activities.length > 0) {
        let html = '';
        activities.forEach(activity => {
            html += `
                <div class="activity-item">
                    <div class="activity-avatar">
                        ${activity.avatar ? 
                            `<img src="${activity.avatar}" alt="User">` : 
                            `<div class="avatar-placeholder">${activity.first_name.charAt(0).toUpperCase()}</div>`
                        }
                    </div>
                    <div class="activity-content">
                        <div class="activity-text">
                            <strong>${activity.first_name} ${activity.last_name}</strong>
                            ${activity.description}
                        </div>
                        <div class="activity-time">
                            <i class="fas fa-clock me-1"></i>
                            ${formatTime(activity.created_at)}
                        </div>
                    </div>
                </div>
            `;
        });
        $feed.html(html);
    }
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        hour: 'numeric', 
        minute: '2-digit' 
    });
}

function filterDashboard(period) {
    $.ajax({
        url: '<?= base_url('home/filter') ?>',
        type: 'POST',
        data: { period: period },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update dashboard with filtered data
                console.log('Dashboard filtered for:', period);
                // Implement UI updates here
            }
        },
        error: function() {
            console.error('Failed to filter dashboard');
        }
    });
}
</script>
</div> <!-- Close container-fluid -->
