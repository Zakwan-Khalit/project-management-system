<?php
$status_options = [
    'pending' => 'Pending',
    'in_progress' => 'In Progress',
    'review' => 'In Review',
    'completed' => 'Completed'
];

$priority_options = [
    'low' => 'Low',
    'medium' => 'Medium',
    'high' => 'High',
    'urgent' => 'Urgent'
];

$status_colors = [
    'pending' => 'warning',
    'in_progress' => 'primary',
    'review' => 'info',
    'completed' => 'success'
];

$priority_colors = [
    'low' => 'secondary',
    'medium' => 'info',
    'high' => 'warning',
    'urgent' => 'danger'
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tasks me-2"></i>Task Details</h2>
    <div>
        <a href="<?= base_url('tasks') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Tasks
        </a>
        <a href="<?= base_url('tasks/edit/' . $task['id']) ?>" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Edit Task
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Task Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?= esc($task['title']) ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <span class="badge bg-<?= $status_colors[$task['status_code'] ?? 'pending'] ?? 'secondary' ?> ms-2">
                            <?= $status_options[$task['status_code'] ?? 'pending'] ?? esc($task['status_name'] ?? 'Pending') ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Priority:</strong>
                        <span class="badge bg-<?= $priority_colors[strtolower($task['priority_name'] ?? 'medium')] ?? 'secondary' ?> ms-2">
                            <?= $priority_options[strtolower($task['priority_name'] ?? 'medium')] ?? esc($task['priority_name'] ?? 'Medium') ?>
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Project:</strong>
                        <a href="<?= base_url('projects/view/' . $task['project_id']) ?>" class="text-decoration-none ms-2">
                            <?= esc($task['project_name'] ?? 'N/A') ?>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <strong>Assigned To:</strong>
                        <span class="ms-2"><?= esc($task['assigned_to_name'] ?? 'Unassigned') ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Due Date:</strong>
                        <span class="ms-2">
                            <?php if ($task['due_date']): ?>
                                <?= date('M d, Y', strtotime($task['due_date'])) ?>
                                <?php if (strtotime($task['due_date']) < time() && ($task['status_code'] ?? 'pending') !== 'completed'): ?>
                                    <span class="badge bg-danger ms-2">Overdue</span>
                                <?php endif; ?>
                            <?php else: ?>
                                Not set
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created:</strong>
                        <span class="ms-2"><?= date('M d, Y g:i A', strtotime($task['created_at'])) ?></span>
                    </div>
                </div>

                <?php if (!empty($task['description'])): ?>
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        <?= nl2br(esc($task['description'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Status Update Actions -->
                <div class="mt-4">
                    <strong>Update Status:</strong>
                    <div class="btn-group ms-2" role="group">
                        <?php foreach ($status_options as $status_key => $status_label): ?>
                            <?php if ($status_key !== ($task['status_code'] ?? 'pending')): ?>
                                <button type="button" 
                                        class="btn btn-outline-<?= $status_colors[$status_key] ?> btn-sm"
                                        onclick="updateTaskStatus(<?= $task['id'] ?>, '<?= $status_key ?>')">
                                    <?= $status_label ?>
                                </button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comments me-2"></i>Comments
                    <span class="badge bg-secondary ms-2"><?= count($comments) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <!-- Add Comment Form -->
                <form id="commentForm" class="mb-4">
                    <div class="input-group">
                        <textarea class="form-control" 
                                  id="commentText" 
                                  name="comment" 
                                  rows="2" 
                                  placeholder="Add a comment..."
                                  required></textarea>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>

                <!-- Comments List -->
                <div id="commentsList">
                    <?php if (empty($comments)): ?>
                        <div class="text-muted text-center py-3">
                            <i class="fas fa-comment-slash fa-2x mb-2"></i>
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex">
                                        <div class="avatar-circle me-3">
                                            <?= strtoupper(substr($comment['user_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= esc($comment['user_name'] ?? 'Unknown User') ?></h6>
                                            <small class="text-muted">
                                                <?= date('M d, Y g:i A', strtotime($comment['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="comment-content mt-2 ms-5">
                                    <?= nl2br(esc($comment['comment'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Task Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('tasks/edit/' . $task['id']) ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Task
                    </a>
                    <?php if (($task['status_code'] ?? 'pending') !== 'completed'): ?>
                        <button type="button" 
                                class="btn btn-outline-success"
                                onclick="updateTaskStatus(<?= $task['id'] ?>, 'completed')">
                            <i class="fas fa-check me-2"></i>Mark Complete
                        </button>
                    <?php endif; ?>
                    <button type="button" 
                            class="btn btn-outline-danger"
                            onclick="deleteTask(<?= $task['id'] ?>)">
                        <i class="fas fa-trash me-2"></i>Delete Task
                    </button>
                </div>
            </div>
        </div>

        <!-- Task Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Task Created</h6>
                            <small class="text-muted">
                                <?= date('M d, Y g:i A', strtotime($task['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                    <?php if ($task['updated_at'] !== $task['created_at']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <small class="text-muted">
                                    <?= date('M d, Y g:i A', strtotime($task['updated_at'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (($task['status_code'] ?? 'pending') === 'completed'): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Task Completed</h6>
                                <small class="text-muted">
                                    Current status
                                </small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update task status
function updateTaskStatus(taskId, status) {
    Swal.fire({
        title: 'Update Status',
        text: 'Are you sure you want to update the task status?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#38a169',
        cancelButtonColor: '#e53e3e',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
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
    });
}

// Delete task
function deleteTask(taskId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?= base_url('tasks/delete') ?>/${taskId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Task has been deleted successfully', 'success')
                    .then(() => {
                        window.location.href = '<?= base_url('tasks') ?>';
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete task', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while deleting task', 'error');
            });
        }
    });
}

// Add comment
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const commentText = document.getElementById('commentText').value.trim();
    if (!commentText) return;

    fetch(`<?= base_url('tasks/addComment') ?>/<?= $task['id'] ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ comment: commentText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentText').value = '';
            location.reload(); // Reload to show new comment
        } else {
            Swal.fire('Error', data.message || 'Failed to add comment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'An error occurred while adding comment', 'error');
    });
});
</script>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    background: var(--bs-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 20px;
    bottom: -20px;
    width: 2px;
    background: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e9ecef;
}

.comment-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
