
<!-- Reports & Analytics Dashboard -->
<div class="container-fluid" id="reports-container">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 3rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 style="color: white; font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; margin-bottom: 1rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-chart-bar" style="margin-right: 1rem; color: rgba(255,255,255,0.9);"></i>
                    Reports & Analytics
                </h1>
                <p style="color: rgba(255,255,255,0.95); font-size: clamp(1rem, 2vw, 1.2rem); margin-bottom: 0; font-weight: 400;">
                    Project and task statistics, completion rates, and recent activity.
                </p>
            </div>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row" style="display: flex; gap: 1.5rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div class="stat-card" style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(102,126,234,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;">
                <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #667eea; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Projects</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                    <?= esc($totalProjects) ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Total Projects</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div class="stat-card" style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(72,187,120,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;">
                <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #48bb78; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Completed</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                    <?= esc($completedProjects) ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Completed Projects</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div class="stat-card" style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(13,202,240,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;">
                <i class="fas fa-play-circle" style="font-size: 2.5rem; color: #0dcaf0; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Active</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                    <?= esc($activeProjects) ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Active Projects</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12" style="flex: 1; min-width: 250px; margin-bottom: 1rem;">
            <div class="stat-card" style="background: #fff; border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(255,193,7,0.08); padding: 2rem; text-align: center; transition: transform 0.3s ease;">
                <i class="fas fa-users" style="font-size: 2.5rem; color: #ffc107; margin-bottom: 1rem;"></i>
                <h3 style="font-family: 'Poppins', sans-serif; font-weight: 700; margin-bottom: 0.5rem;">Users</h3>
                <div style="font-size: 2rem; font-weight: 800; color: #4a5568; margin-bottom: 0.5rem;">
                    <?= esc($totalUsers) ?>
                </div>
                <div style="color: #6b7280; font-size: 1rem;">Total Users</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card chart-card" style="border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(13,202,240,0.08);">
                <div class="card-header bg-white border-0" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <span class="card-title" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.1rem;"><i class="fas fa-tasks me-2"></i>Task Status Distribution</span>
                </div>
                <div class="card-body">
                    <canvas id="taskStatusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card chart-card" style="border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(25,135,84,0.08);">
                <div class="card-header bg-white border-0" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <span class="card-title" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.1rem;"><i class="fas fa-project-diagram me-2"></i>Project Status Distribution</span>
                </div>
                <div class="card-body">
                    <canvas id="projectStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Card -->
    <div class="row g-4 mt-4">
        <div class="col-md-12">
            <div class="card" style="border-radius: 1.25rem; box-shadow: 0 4px 24px rgba(118,75,162,0.08);">
                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between" style="border-radius: 1.25rem 1.25rem 0 0;">
                    <span class="card-title" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.1rem;"><i class="fas fa-history me-2"></i>Recent Activity</span>
                    <button class="btn btn-outline-primary btn-sm" id="refresh-activity-btn" title="Refresh Activity"><i class="fas fa-sync-alt"></i></button>
                </div>
                <div class="card-body" style="padding: 0;">
                    <ul class="list-group list-group-flush" id="activity-list">
                        <?php if (!empty($recentActivity)): ?>
                            <?php foreach ($recentActivity as $activity): ?>
                                <li class="list-group-item" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border: none; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                    <div style="width: 40px; height: 40px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #764ba2;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <strong><?= esc($activity['user_name'] ?? ($activity['first_name'] ?? '')) ?></strong> <?= esc($activity['action']) ?>
                                        <span class="text-muted">on <span class="badge bg-light text-dark"><?= esc($activity['table_name']) ?></span> <span style="font-size:0.95em;">(<?= esc($activity['created_at']) ?>)</span></span>
                                    </div>
                                    <div>
                                        <span class="badge bg-info text-dark" style="font-size:0.9em;">#<?= esc($activity['id'] ?? '') ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center" style="padding: 2rem; color: #9ca3af;">
                                <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"></i><br>
                                No recent activity found.
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<script>
// Refresh activity list via AJAX (example, needs endpoint)
document.getElementById('refresh-activity-btn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    $.ajax({
        url: '<?= base_url('reports/recentActivityAjax') ?>',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const list = document.getElementById('activity-list');
            list.innerHTML = '';
            if (data && Array.isArray(data)) {
                data.forEach(activity => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.style = 'display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem; border: none; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;';
                    li.innerHTML = `
                        <div style="width: 40px; height: 40px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="color: #764ba2;"></i>
                        </div>
                        <div style="flex: 1;">
                            <strong>${activity.user_name ?? activity.first_name ?? ''}</strong> ${activity.action}
                            <span class="text-muted">on <span class="badge bg-light text-dark">${activity.table_name}</span> <span style="font-size:0.95em;">(${activity.created_at})</span></span>
                        </div>
                        <div>
                            <span class="badge bg-info text-dark" style="font-size:0.9em;">#${activity.id ?? ''}</span>
                        </div>
                    `;
                    list.appendChild(li);
                });
            } else {
                list.innerHTML = `<li class="list-group-item text-center" style="padding: 2rem; color: #9ca3af;"><i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"></i><br>No recent activity found.</li>`;
            }
        },
        error: function() {
            Swal && Swal.fire({icon:'error',title:'Failed to refresh activity',text:'Could not load recent activity.'});
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
        }
    });
});
</script>
</div>

<script>
// Chart.js for Task Status
const taskStatusData = <?= json_encode(array_values($taskStatusData)) ?>;
const taskStatusLabels = <?= json_encode(array_keys($taskStatusData)) ?>;
const ctxTask = document.getElementById('taskStatusChart').getContext('2d');
new Chart(ctxTask, {
    type: 'doughnut',
    data: {
        labels: taskStatusLabels,
        datasets: [{
            data: taskStatusData,
            backgroundColor: ['#0dcaf0', '#ffc107', '#198754', '#dc3545', '#6c757d'],
        }]
    },
    options: {responsive: true, plugins: {legend: {position: 'bottom'}}}
});
// Chart.js for Project Status
const projectStatusData = <?= json_encode(array_values($projectStatusData)) ?>;
const projectStatusLabels = <?= json_encode(array_keys($projectStatusData)) ?>;
const ctxProject = document.getElementById('projectStatusChart').getContext('2d');
new Chart(ctxProject, {
    type: 'pie',
    data: {
        labels: projectStatusLabels,
        datasets: [{
            data: projectStatusData,
            backgroundColor: ['#198754', '#0dcaf0', '#ffc107', '#dc3545', '#6c757d'],
        }]
    },
    options: {responsive: true, plugins: {legend: {position: 'bottom'}}}
});
</script>
