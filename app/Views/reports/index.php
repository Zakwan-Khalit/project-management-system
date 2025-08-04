<!-- Reports & Analytics Dashboard -->
<div class="container-fluid">
    <!-- Error Display -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= esc($error_message) ?>
        </div>
    <?php endif; ?>

<!-- Reports & Analytics Dashboard -->
<div class="container-fluid">
    <!-- Error Display -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= esc($error_message) ?>
        </div>
    <?php endif; ?>

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                </a>
                <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; padding: 0.2rem 0.4rem; background: rgba(255,255,255,0.1); border-radius: 0.3rem; backdrop-filter: blur(10px);">
                <i class="fas fa-chart-bar" style="margin-right: 0.4rem; font-size: 0.75rem; opacity: 0.9;"></i>
                Reports & Analytics
            </li>
        </ol>
    </nav>

    <!-- Modern Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
            <div>
                <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <i class="fas fa-chart-bar" style="margin-right: 1rem; color: rgba(255,255,255,0.9);"></i>
                    Reports & Analytics
                </h1>
                <p style="color: rgba(255,255,255,0.95); font-size: 1.1rem; margin-bottom: 0; font-weight: 400;">
                    Comprehensive insights into project performance, task completion, and team productivity
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Projects -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= esc($totalProjects ?? 0) ?></h3>
                    <p class="stats-label">Total Projects</p>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= esc($activeProjects ?? 0) ?></h3>
                    <p class="stats-label">Active Projects</p>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= esc($completedProjects ?? 0) ?></h3>
                    <p class="stats-label">Completed Projects</p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number"><?= esc($totalUsers ?? 0) ?></h3>
                    <p class="stats-label">Total Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Task Status Chart -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-tasks me-2"></i>
                        Task Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="taskStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Project Status Chart -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-project-diagram me-2"></i>
                        Project Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="projectStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link to external CSS file -->
<link rel="stylesheet" href="<?= base_url('assets/css/reports.css') ?>"?>

<!-- Chart.js Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Task Status Chart
    const taskStatusData = <?= json_encode(array_values($taskStatusData ?? [])) ?>;
    const taskStatusLabels = <?= json_encode(array_keys($taskStatusData ?? [])) ?>;
    
    if (taskStatusLabels.length > 0) {
        const taskCtx = document.getElementById('taskStatusChart').getContext('2d');
        new Chart(taskCtx, {
            type: 'doughnut',
            data: {
                labels: taskStatusLabels,
                datasets: [{
                    data: taskStatusData,
                    backgroundColor: [
                        '#667eea',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b',
                        '#38f9d7'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Project Status Chart
    const projectStatusData = <?= json_encode(array_values($projectStatusData ?? [])) ?>;
    const projectStatusLabels = <?= json_encode(array_keys($projectStatusData ?? [])) ?>;
    
    if (projectStatusLabels.length > 0) {
        const projectCtx = document.getElementById('projectStatusChart').getContext('2d');
        new Chart(projectCtx, {
            type: 'pie',
            data: {
                labels: projectStatusLabels,
                datasets: [{
                    data: projectStatusData,
                    backgroundColor: [
                        '#764ba2',
                        '#667eea',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
