<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Pending</h5>
                        <h2 class="mb-0" style="color:#222; background:transparent;"><?php echo isset($pending_count) ? $pending_count : 0; ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">In Progress</h5>
                        <h2 class="mb-0" style="color:#222; background:transparent;"><?php echo isset($in_progress_count) ? $in_progress_count : 0; ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-spinner fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">In Review</h5>
                        <h2 class="mb-0" style="color:#222; background:transparent;"><?php echo isset($review_count) ? $review_count : 0; ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Completed</h5>
                        <h2 class="mb-0" style="color:#222; background:transparent;"><?php echo isset($completed_count) ? $completed_count : 0; ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Sort Options -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <!-- Status Dropdown -->
                    <select id="statusFilter" class="form-select" style="width: auto;">
                        <option value="">All Status</option>
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= esc($status['code']) ?>">
                                <?= esc($status['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select id="priorityFilter" class="form-select" style="width: auto;">
                        <option value="">All Priority</option>
                        <?php foreach ($priority_options as $priority): ?>
                            <option value="<?= esc($priority['code']) ?>">
                                <?= esc($priority['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end gap-2">
                    <select id="sortBy" class="form-select" style="width: auto;">
                        <option value="due_date">Sort by Due Date</option>
                        <option value="priority">Sort by Priority</option>
                        <option value="status">Sort by Status</option>
                        <option value="date_created">Sort by Created</option>
                    </select>
                    <button class="btn btn-outline-secondary" onclick="toggleView()">
                        <i id="viewIcon" class="fas fa-th"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tasks List/Grid -->
<div id="tasksContainer">
    <?php if (empty($tasks)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No tasks assigned to you yet</h4>
                <p class="text-muted">Tasks assigned to you will appear here.</p>
                <a href="<?= base_url('tasks') ?>" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>View All Tasks
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- List View (Default) -->
        <div id="listView">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tasksTable">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Project</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr class="task-row" 
                                    data-status="<?= getTaskStatus($task) ?>" 
                                    data-priority="<?= getTaskPriority($task) ?>"
                                    data-due="<?= $task['due_date'] ?? '' ?>"
                                    data-created="<?= $task['date_created'] ?>">
                                    <td>
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="<?= base_url('tasks/view/' . $task['id']) ?>" 
                                                   class="text-decoration-none">
                                                    <?= esc($task['title']) ?>
                                                </a>
                                            </h6>
                                            <?php if (!empty($task['description'])): ?>
                                                <small class="text-muted">
                                                    <?= esc(substr($task['description'], 0, 50)) ?>
                                                    <?= strlen($task['description']) > 50 ? '...' : '' ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('projects/view/' . $task['project_id']) ?>" 
                                           class="text-decoration-none">
                                            <?= esc($task['project_name'] ?? 'Unknown Project') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $priority_colors[getTaskPriority($task)] ?? 'secondary' ?>">
                                            <?= $task['priority_name'] ?? $priority_options[getTaskPriority($task)] ?? 'Medium' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $status_colors[getTaskStatus($task)] ?? 'secondary' ?>">
                                            <?= $task['status_name'] ?? $status_options[getTaskStatus($task)] ?? 'Pending' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($task['due_date']): ?>
                                            <span class="<?= strtotime($task['due_date']) < time() && getTaskStatus($task) !== 'completed' ? 'text-danger' : '' ?>">
                                                <?= date('M d, Y', strtotime($task['due_date'])) ?>
                                            </span>
                                            <?php if (strtotime($task['due_date']) < time() && getTaskStatus($task) !== 'completed'): ?>
                                                <br><small class="badge bg-danger">Overdue</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">No due date</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('tasks/view/' . $task['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('tasks/edit/' . $task['id']) ?>" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if (getTaskStatus($task) !== 'completed'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        title="Mark Complete"
                                                        onclick="updateTaskStatus(<?= $task['id'] ?>, 'completed')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Grid View (Hidden by default) -->
        <div id="gridView" style="display: none;">
            <div class="row" id="tasksGrid">
                <?php foreach ($tasks as $task): ?>
                    <div class="col-md-6 col-lg-4 mb-3 task-card" 
                         data-status="<?= getTaskStatus($task) ?>" 
                         data-priority="<?= getTaskPriority($task) ?>"
                         data-due="<?= $task['due_date'] ?? '' ?>"
                         data-created="<?= $task['date_created'] ?>">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="badge bg-<?= $priority_colors[getTaskPriority($task)] ?? 'secondary' ?>">
                                    <?= $task['priority_name'] ?? $priority_options[getTaskPriority($task)] ?? 'Medium' ?>
                                </span>
                                <span class="badge bg-<?= $status_colors[getTaskStatus($task)] ?? 'secondary' ?>">
                                    <?= $task['status_name'] ?? $status_options[getTaskStatus($task)] ?? 'Pending' ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">
                                    <a href="<?= base_url('tasks/view/' . $task['id']) ?>" 
                                       class="text-decoration-none">
                                        <?= esc($task['title']) ?>
                                    </a>
                                </h6>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="card-text text-muted small">
                                        <?= esc(substr($task['description'], 0, 100)) ?>
                                        <?= strlen($task['description']) > 100 ? '...' : '' ?>
                                    </p>
                                <?php endif; ?>
                                <div class="mb-2">
                                    <strong>Project:</strong>
                                    <a href="<?= base_url('projects/view/' . $task['project_id']) ?>" 
                                       class="text-decoration-none">
                                        <?= esc($task['project_name'] ?? 'Unknown Project') ?>
                                    </a>
                                </div>
                                <?php if ($task['due_date']): ?>
                                    <div class="mb-2">
                                        <strong>Due:</strong>
                                        <span class="<?= strtotime($task['due_date']) < time() && getTaskStatus($task) !== 'completed' ? 'text-danger' : '' ?>">
                                            <?= date('M d, Y', strtotime($task['due_date'])) ?>
                                        </span>
                                        <?php if (strtotime($task['due_date']) < time() && getTaskStatus($task) !== 'completed'): ?>
                                            <span class="badge bg-danger ms-2">Overdue</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('tasks/view/' . $task['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('tasks/edit/' . $task['id']) ?>" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                    <?php if (getTaskStatus($task) !== 'completed'): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-success" 
                                                onclick="updateTaskStatus(<?= $task['id'] ?>, 'completed')">
                                            <i class="fas fa-check me-1"></i>Complete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
let isGridView = false;

// Filter and sort functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const sortBy = document.getElementById('sortBy');

    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    if (priorityFilter) {
        priorityFilter.addEventListener('change', applyFilters);
    }
    if (sortBy) {
        sortBy.addEventListener('change', applySorting);
    }
});

function applyFilters() {
    const statusFilter = document.getElementById('statusFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    
    const rows = document.querySelectorAll('.task-row, .task-card');
    
    rows.forEach(row => {
        const status = row.dataset.status;
        const priority = row.dataset.priority;
        
        const statusMatch = !statusFilter || status === statusFilter;
        const priorityMatch = !priorityFilter || priority === priorityFilter;
        
        if (statusMatch && priorityMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function applySorting() {
    const sortBy = document.getElementById('sortBy').value;
    const container = isGridView ? document.getElementById('tasksGrid') : document.querySelector('#tasksTable tbody');
    const items = Array.from(container.children);
    
    items.sort((a, b) => {
        let aValue, bValue;
        
        switch (sortBy) {
            case 'due_date':
                aValue = a.dataset.due || '9999-12-31';
                bValue = b.dataset.due || '9999-12-31';
                return new Date(aValue) - new Date(bValue);
            case 'priority':
                const priorityOrder = { 'urgent': 0, 'high': 1, 'medium': 2, 'low': 3 };
                aValue = priorityOrder[a.dataset.priority] || 4;
                bValue = priorityOrder[b.dataset.priority] || 4;
                return aValue - bValue;
            case 'status':
                const statusOrder = { 'pending': 0, 'in_progress': 1, 'review': 2, 'completed': 3 };
                aValue = statusOrder[a.dataset.status] || 4;
                bValue = statusOrder[b.dataset.status] || 4;
                return aValue - bValue;
            case 'date_created':
                aValue = new Date(a.dataset.created);
                bValue = new Date(b.dataset.created);
                return bValue - aValue; // Newest first
            default:
                return 0;
        }
    });
    
    items.forEach(item => container.appendChild(item));
}

function toggleView() {
    const listView = document.getElementById('listView');
    const gridView = document.getElementById('gridView');
    const viewIcon = document.getElementById('viewIcon');
    
    if (isGridView) {
        listView.style.display = 'block';
        gridView.style.display = 'none';
        viewIcon.className = 'fas fa-th';
        isGridView = false;
    } else {
        listView.style.display = 'none';
        gridView.style.display = 'block';
        viewIcon.className = 'fas fa-list';
        isGridView = true;
    }
}

function updateTaskStatus(taskId, status) {
    if (confirm('Are you sure you want to update the task status?')) {
        fetch(`<?= base_url('tasks/updateStatus') ?>/${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'Task status updated successfully', 'success')
                .then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to update task status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'An error occurred while updating task status', 'error');
        });
    }
}
</script>
