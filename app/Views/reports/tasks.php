<!-- Task Reports Page -->

<div class="container-fluid">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem;">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h2 mb-2">
                    <i class="fas fa-tasks me-2"></i>
                    Task Reports
                </h1>
                <p class="mb-0 opacity-75">Comprehensive analysis of task performance and team productivity</p>
            </div>
            <div class="col-auto">
                <div class="d-flex gap-2">
                    <a href="<?= base_url('reports') ?>" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Reports
                    </a>
                    <a href="<?= base_url('reports/export/tasks') ?>" class="btn btn-light">
                        <i class="fas fa-download me-2"></i>
                        Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Metrics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.2s ease; position: relative; overflow: hidden; height: 100%; border-top: 4px solid; border-image: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) 1;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;"><?= number_format($highPriorityTasks) ?></div>
                        <div style="color: #6b7280; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem;">High Priority</div>
                        <div style="font-size: 0.8rem; font-weight: 500;" class="text-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Urgent Tasks
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-exclamation-circle text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.2s ease; position: relative; overflow: hidden; height: 100%; border-top: 4px solid; border-image: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) 1;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;"><?= number_format($mediumPriorityTasks) ?></div>
                        <div style="color: #6b7280; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem;">Medium Priority</div>
                        <div style="font-size: 0.8rem; font-weight: 500;" class="text-warning">
                            <i class="fas fa-minus-circle me-1"></i>
                            Standard Tasks
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-circle text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.2s ease; position: relative; overflow: hidden; height: 100%; border-top: 4px solid; border-image: linear-gradient(135deg, #10b981 0%, #059669 100%) 1;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;"><?= number_format($lowPriorityTasks) ?></div>
                        <div style="color: #6b7280; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem;">Low Priority</div>
                        <div style="font-size: 0.8rem; font-weight: 500;" class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Routine Tasks
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-minus text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.2s ease; position: relative; overflow: hidden; height: 100%; border-top: 4px solid; border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) 1;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; line-height: 1;"><?= count($overdueTasks) ?></div>
                        <div style="color: #6b7280; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem;">Overdue Tasks</div>
                        <div style="font-size: 0.8rem; font-weight: 500;" class="text-danger">
                            <i class="fas fa-clock me-1"></i>
                            Past Due Date
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-calendar-times text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Charts Section -->
        <div class="col-lg-8">
            <!-- Daily Completion Trend -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 2rem;">
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h5 style="color: #374151; font-weight: 600; font-size: 1.1rem; margin: 0;">
                        <i class="fas fa-chart-line me-2"></i>
                        Daily Task Completion Trend (Last 30 Days)
                    </h5>
                </div>
                <div style="padding: 1.5rem; height: 350px;">
                    <canvas id="dailyTrendChart"></canvas>
                </div>
            </div>

            <!-- Tasks This Week -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 2rem;">
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h5 style="color: #374151; font-weight: 600; font-size: 1.1rem; margin: 0;">
                        <i class="fas fa-calendar-week me-2"></i>
                        Tasks Due This Week (<?= count($tasksThisWeek) ?> tasks)
                    </h5>
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($tasksThisWeek)): ?>
                        <?php foreach ($tasksThisWeek as $task): ?>
                            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor=''">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div style="color: #1f2937; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem;"><?= esc($task['title']) ?></div>
                                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.8rem; color: #6b7280;">
                                            <span style="padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; background: <?= $task['priority'] === 'high' ? '#fee2e2' : ($task['priority'] === 'medium' ? '#fef3c7' : '#d1fae5') ?>; color: <?= $task['priority'] === 'high' ? '#991b1b' : ($task['priority'] === 'medium' ? '#92400e' : '#065f46') ?>;">
                                                <?= ucfirst(esc($task['priority'])) ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-calendar me-1"></i>
                                                Due: <?= date('M j, Y', strtotime($task['due_date'])) ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-project-diagram me-1"></i>
                                                <?= esc($task['project_name'] ?? 'No Project') ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $task['status'] === 'completed' ? 'success' : ($task['status'] === 'in_progress' ? 'warning' : 'secondary') ?>">
                                            <?= ucfirst(str_replace('_', ' ', esc($task['status']))) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No tasks due this week</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Overdue Tasks -->
            <?php if (!empty($overdueTasks)): ?>
                <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 2rem;">
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                        <h5 style="color: #dc3545; font-weight: 600; font-size: 1.1rem; margin: 0;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Overdue Tasks (<?= count($overdueTasks) ?> tasks)
                        </h5>
                    </div>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach (array_slice($overdueTasks, 0, 10) as $task): ?>
                            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor=''">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div style="color: #1f2937; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.95rem;"><?= esc($task['title']) ?></div>
                                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.8rem; color: #6b7280;">
                                            <span style="padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; background: <?= $task['priority'] === 'high' ? '#fee2e2' : ($task['priority'] === 'medium' ? '#fef3c7' : '#d1fae5') ?>; color: <?= $task['priority'] === 'high' ? '#991b1b' : ($task['priority'] === 'medium' ? '#92400e' : '#065f46') ?>;">
                                                <?= ucfirst(esc($task['priority'])) ?>
                                            </span>
                                            <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.7rem; font-weight: 600;">
                                                <i class="fas fa-calendar-times me-1"></i>
                                                <?php
                                                $overdueDays = (new DateTime())->diff(new DateTime($task['due_date']))->days;
                                                echo $overdueDays . ' day' . ($overdueDays != 1 ? 's' : '') . ' overdue';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $task['status'] === 'in_progress' ? 'warning' : 'secondary' ?>">
                                            <?= ucfirst(str_replace('_', ' ', esc($task['status']))) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Team Productivity -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h5 style="color: #374151; font-weight: 600; font-size: 1.1rem; margin: 0;">
                        <i class="fas fa-users me-2"></i>
                        Team Productivity (Top 10)
                    </h5>
                </div>
                <div style="max-height: 350px; overflow-y: auto;">
                    <?php if (!empty($userProductivity)): ?>
                        <?php foreach ($userProductivity as $index => $user): ?>
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div class="text-muted small" style="width: 20px;">
                                        #<?= $index + 1 ?>
                                    </div>
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600;">
                                        <?php 
                                        $displayName = $user['user_name'] ?: $user['email'];
                                        echo strtoupper(substr($displayName, 0, 1)); 
                                        ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="font-size: 0.9rem;">
                                            <?= esc($user['user_name'] ?: $user['email']) ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?= $user['completed_tasks'] ?> task<?= $user['completed_tasks'] != 1 ? 's' : '' ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-success me-2">
                                        <?= $user['completed_tasks'] ?>
                                    </span>
                                    <div style="width: 60px; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; margin-left: 1rem;">
                                        <div style="height: 100%; background: linear-gradient(90deg, #10b981 0%, #059669 100%); border-radius: 3px; transition: width 0.5s ease; width: <?= min(100, ($user['completed_tasks'] / max(1, $userProductivity[0]['completed_tasks'])) * 100) ?>%;"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No productivity data available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-3 p-3 bg-light rounded">
                <h6 class="mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Quick Insights
                </h6>
                <div class="small">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Most productive day:</span>
                        <span class="fw-bold">
                            <?php
                            $maxCompletions = max(array_column($dailyCompletions, 'completed'));
                            $bestDay = 'No data';
                            foreach ($dailyCompletions as $day) {
                                if ($day['completed'] == $maxCompletions && $maxCompletions > 0) {
                                    $bestDay = $day['date'];
                                    break;
                                }
                            }
                            echo $bestDay;
                            ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Avg. daily completions:</span>
                        <span class="fw-bold">
                            <?= round(array_sum(array_column($dailyCompletions, 'completed')) / count($dailyCompletions), 1) ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>Total high priority:</span>
                        <span class="fw-bold text-danger"><?= $highPriorityTasks ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Team members active:</span>
                        <span class="fw-bold text-success"><?= count($userProductivity) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Completion Trend Chart
    const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: [<?= implode(',', array_map(function($item) { return '"' . $item['date'] . '"'; }, $dailyCompletions)) ?>],
            datasets: [{
                label: 'Tasks Completed',
                data: [<?= implode(',', array_column($dailyCompletions, 'completed')) ?>],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#667eea',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // Animate completion bars
    const completionFills = document.querySelectorAll('[style*="width: "][style*="%; height: 100%"]');
    setTimeout(() => {
        completionFills.forEach(fill => {
            const width = fill.style.width;
            fill.style.width = '0%';
            setTimeout(() => {
                fill.style.width = width;
            }, 100);
        });
    }, 500);
});
</script>


