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

<!-- 4 Cards in a Row -->
<div class="row" style="display: flex; gap: 2rem; margin-bottom: 2.5rem;">
    <!-- Card 1: Projects Overview -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(102,126,234,0.08); padding: 2rem; text-align: center;">
            <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #667eea; margin-bottom: 1rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Projects</h3>
            <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                <?= $stats['projects']['total_projects'] ?? 0 ?>
            </div>
            <div style="color: #6b7280; font-size: 1rem;">Total Projects</div>
        </div>
    </div>
    <!-- Card 2: Tasks Overview -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(72,187,120,0.08); padding: 2rem; text-align: center;">
            <i class="fas fa-tasks" style="font-size: 2.5rem; color: #38a169; margin-bottom: 1rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Tasks</h3>
            <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                <?= $stats['tasks']['total_tasks'] ?? 0 ?>
            </div>
            <div style="color: #6b7280; font-size: 1rem;">Total Tasks</div>
        </div>
    </div>
    <!-- Card 3: Team Members -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(102,126,234,0.08); padding: 2rem; text-align: center;">
            <i class="fas fa-users" style="font-size: 2.5rem; color: #764ba2; margin-bottom: 1rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Team</h3>
            <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                <?= $teamCount ?? 0 ?>
            </div>
            <div style="color: #6b7280; font-size: 1rem;">Members</div>
        </div>
    </div>
    <!-- Card 4: Completed Tasks -->
    <div class="col" style="flex: 1; min-width: 0;">
        <div style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(118,75,162,0.08); padding: 2rem; text-align: center;">
            <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #48bb78; margin-bottom: 1rem;"></i>
            <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Completed</h3>
            <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                <?= $stats['tasks']['completed_tasks'] ?? 0 ?>
            </div>
            <div style="color: #6b7280; font-size: 1rem;">Tasks Done</div>
        </div>
    </div>
</div>

<!-- My Tasks Card (with No Tasks Assigned message if empty) -->
<div style="background: #fff; border-radius: 1.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden; margin-bottom: 2rem;">
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
    <!-- Tasks List -->
    <div style="padding: 0;">
        <?php if (!empty($myTasks)): ?>
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach (array_slice($myTasks, 0, 5) as $task): ?>
                    <div style="border: none; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; transition: all 0.2s ease; cursor: pointer;" onclick="window.location.href='<?= base_url('tasks/view/' . $task['id']) ?>'" onmouseover="this.style.background='#f8fafc'; this.style.transform='translateX(4px)';" onmouseout="this.style.background='white'; this.style.transform='translateX(0)';">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: #2d3748; font-size: 1.1rem; font-family: 'Poppins', sans-serif;">
                                    <?= esc($task['title']) ?>
                                </div>
                                <div style="color: #6b7280; font-size: 0.95rem; margin-top: 0.25rem;">
                                    <?= esc($task['project_name'] ?? '') ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <span style="background: #f1f5f9; color: #4a5568; border-radius: 0.5rem; padding: 0.25rem 0.75rem; font-size: 0.95rem; font-weight: 500; margin-left: 0.5rem;">
                                    <?= ucfirst($task['status']) ?>
                                </span>
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
                <a href="<?= base_url('tasks/create') ?>" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 10px 25px rgba(102,126,234,0.3)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                    <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                    Create Task
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

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
