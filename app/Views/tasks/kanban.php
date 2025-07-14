<!-- Modern Kanban Board Page -->
<div class="min-vh-100 p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    
    <!-- Kanban Header -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header border-0 text-white p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 1rem 1rem 0 0;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="h2 mb-2 fw-bold d-flex align-items-center">
                        <i class="fas fa-columns me-3"></i>
                        <?= esc($project['name']) ?> - Kanban Board
                    </h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-info-circle me-2"></i>
                        Drag and drop tasks between columns to update their status
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button onclick="openAddTaskModal()" class="btn btn-outline-light">
                        <i class="fas fa-plus me-2"></i>
                        Add Task
                    </button>
                    <button onclick="refreshKanban()" class="btn btn-outline-light">
                        <i class="fas fa-sync-alt me-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Stats -->
    <div class="row g-3 mb-4">
        
        <!-- To Do Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 0.5rem 1rem rgba(0, 0, 0, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                <div class="card-body p-3">
                    <div class="border-start border-5 border-secondary ps-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="h2 fw-bold text-dark mb-1"><?= count($tasks['pending']) ?></h3>
                                <p class="text-muted mb-0 fw-medium">To Do</p>
                            </div>
                            <i class="fas fa-clipboard-list text-muted opacity-25" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 0.5rem 1rem rgba(0, 0, 0, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                <div class="card-body p-3">
                    <div class="border-start border-5 border-warning ps-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="h2 fw-bold text-dark mb-1"><?= count($tasks['in_progress']) ?></h3>
                                <p class="text-muted mb-0 fw-medium">In Progress</p>
                            </div>
                            <i class="fas fa-play-circle text-muted opacity-25" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 0.5rem 1rem rgba(0, 0, 0, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                <div class="card-body p-3">
                    <div class="border-start border-5 border-info ps-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="h2 fw-bold text-dark mb-1"><?= count($tasks['review']) ?></h3>
                                <p class="text-muted mb-0 fw-medium">Review</p>
                            </div>
                            <i class="fas fa-eye text-muted opacity-25" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Done Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 0.5rem 1rem rgba(0, 0, 0, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                <div class="card-body p-3">
                    <div class="border-start border-5 border-success ps-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="h2 fw-bold text-dark mb-1"><?= count($tasks['completed']) ?></h3>
                                <p class="text-muted mb-0 fw-medium">Done</p>
                            </div>
                            <i class="fas fa-check-circle text-muted opacity-25" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="row g-3">
        
        <!-- To Do Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" data-status="pending">
                <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center">
                        <i class="fas fa-clipboard-list me-2"></i>
                        To Do
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['pending']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="pending-tasks">
                    <?php foreach ($tasks['pending'] as $task): ?>
                        <?= $this->include('tasks/kanban_card', ['task' => $task]) ?>
                    <?php endforeach; ?>
                    
                    <?php if (empty($tasks['pending'])): ?>
                        <div class="text-center py-5" style="border: 2px dashed #dee2e6; border-radius: 0.5rem; background: white;">
                            <i class="fas fa-plus-circle text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-3">No tasks yet</p>
                            <button onclick="openAddTaskModal()" class="btn btn-outline-secondary btn-sm">
                                Add Task
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" data-status="in_progress">
                <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center">
                        <i class="fas fa-play-circle me-2"></i>
                        In Progress
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['in_progress']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="in_progress-tasks">
                    <?php foreach ($tasks['in_progress'] as $task): ?>
                        <?= $this->include('tasks/kanban_card', ['task' => $task]) ?>
                    <?php endforeach; ?>
                    
                    <?php if (empty($tasks['in_progress'])): ?>
                        <div class="text-center py-5" style="border: 2px dashed #dee2e6; border-radius: 0.5rem; background: white;">
                            <i class="fas fa-play-circle text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No tasks in progress</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Review Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" data-status="review">
                <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center">
                        <i class="fas fa-eye me-2"></i>
                        Review
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['review']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="review-tasks">
                    <?php foreach ($tasks['review'] as $task): ?>
                        <?= $this->include('tasks/kanban_card', ['task' => $task]) ?>
                    <?php endforeach; ?>
                    
                    <?php if (empty($tasks['review'])): ?>
                        <div class="text-center py-5" style="border: 2px dashed #dee2e6; border-radius: 0.5rem; background: white;">
                            <i class="fas fa-eye text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No tasks under review</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Done Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" data-status="completed">
                <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        Done
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['completed']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="completed-tasks">
                    <?php foreach ($tasks['completed'] as $task): ?>
                        <?= $this->include('tasks/kanban_card', ['task' => $task]) ?>
                    <?php endforeach; ?>
                    
                    <?php if (empty($tasks['completed'])): ?>
                        <div class="text-center py-5" style="border: 2px dashed #dee2e6; border-radius: 0.5rem; background: white;">
                            <i class="fas fa-check-circle text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No completed tasks</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Add New Task
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeAddTaskModal()"></button>
            </div>
            <form id="addTaskForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Task Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="3" class="form-control"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Assign To</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">Select Team Member</option>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?= $member['id'] ?>">
                                        <?= esc($member['first_name'] . ' ' . $member['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estimated Hours</label>
                            <input type="number" name="estimated_hours" step="0.5" min="0" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Initial Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" selected>To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">Review</option>
                                <option value="completed">Done</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddTaskModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeKanban();
    initializeTaskForm();
});

function openAddTaskModal() {
    const modal = new bootstrap.Modal(document.getElementById('addTaskModal'));
    modal.show();
}

function closeAddTaskModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
    if (modal) {
        modal.hide();
    }
}

function initializeKanban() {
    const columns = document.querySelectorAll('[id$="-tasks"]');
    
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            onStart: function(evt) {
                // Apply ghost effect
                evt.item.style.opacity = '0.5';
            },
            onEnd: function(evt) {
                // Remove ghost effect
                evt.item.style.opacity = '';
                
                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.id.replace('-tasks', '');
                const newPosition = evt.newIndex;
                
                updateTaskStatus(taskId, newStatus, newPosition);
            },
            onAdd: function(evt) {
                // Apply drag-over effect
                evt.to.style.background = '#e3f2fd';
                evt.to.style.border = '2px dashed #2196f3';
                setTimeout(() => {
                    evt.to.style.background = '#f8f9fa';
                    evt.to.style.border = '';
                }, 300);
            }
        });
    });
}

function initializeTaskForm() {
    document.getElementById('addTaskForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const result = await $.ajax({
                url: '<?= base_url('tasks/create') ?>',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (result.success) {
                closeAddTaskModal();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Failed to create task'
                });
            }
        } catch (error) {
            console.error('Error creating task:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while creating the task.'
            });
        }
    });
}

async function updateTaskStatus(taskId, newStatus, newPosition) {
    try {
        const result = await $.ajax({
            url: '<?= base_url('tasks/updateStatus') ?>',
            method: 'POST',
            data: {
                task_id: taskId,
                status: newStatus,
                position: newPosition
            },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (result.success) {
            updateColumnCounters();
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: 'Task status updated'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update task status'
            });
            
            location.reload();
        }
    } catch (error) {
        console.error('Error updating task status:', error);
        location.reload();
    }
}

function updateColumnCounters() {
    document.querySelectorAll('[data-status]').forEach(column => {
        const status = column.dataset.status;
        const tasksContainer = column.querySelector('[id$="-tasks"]');
        const taskCards = tasksContainer.querySelectorAll('[data-task-id]');
        const counter = column.querySelector('.badge');
        
        if (counter) {
            counter.textContent = taskCards.length;
        }
    });
}

function refreshKanban() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Updating kanban board',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function editTask(taskId) {
    console.log('Edit task:', taskId);
}

function deleteTask(taskId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Delete task:', taskId);
        }
    });
}
</script>
