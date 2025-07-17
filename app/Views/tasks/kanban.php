<!-- Modern Kanban Board Page -->
<div class="min-vh-100 p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    
    <!-- Kanban Header -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header border-0 bg-white text-dark p-4" style="border-radius: 1rem 1rem 0 0;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="h2 mb-2 fw-bold d-flex align-items-center">
                        <i class="fas fa-columns me-3" style="color: #6366f1;"></i>
                        <?= esc($project['name']) ?> - Kanban Board
                    </h1>
                    <p class="mb-0 opacity-75 text-secondary">
                        <i class="fas fa-info-circle me-2"></i>
                        Drag and drop tasks between columns to update their status
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button onclick="openAddTaskModal()" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>
                        Add Task
                    </button>
                    <button onclick="refreshKanban()" class="btn btn-outline-secondary">
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
                <div class="card-header bg-white text-dark text-center py-3 border-0" style="border-left: 5px solid #6366f1; border-radius: 1rem 1rem 0 0; font-weight: 700; letter-spacing: 0.01em;">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center gap-2">
                        <i class="fas fa-clipboard-list" style="color: #6366f1;"></i>
                        To Do
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['pending']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="pending-tasks">
                    <?php if (isset($tasks['pending']) && is_array($tasks['pending']) && count($tasks['pending'])): ?>
                        <?php foreach ($tasks['pending'] as $task): ?>
                            <!-- Modern Kanban Task Card (inlined) -->
                            <div class="kanban-card shadow-sm bg-white border-0 mb-3" style="border-radius: 1rem; padding: 1.5rem 1.25rem 1.25rem 1.25rem; min-width: 280px; position: relative; transition: box-shadow 0.2s, transform 0.2s; cursor: grab; overflow: visible;"
                                 data-task-id="<?= isset($task['id']) ? (int)$task['id'] : 0 ?>"
                                 onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 8px 32px rgba(102,126,234,0.12)';"
                                 onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)';">
                                <!-- Priority Indicator -->
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; border-radius: 1rem 1rem 0 0; background: <?php
                                    $priority = !empty($task['priority']) ? strtolower($task['priority']) : 'low';
                                    echo $priority === 'high' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                                        ($priority === 'medium' ? 'linear-gradient(135deg, #f59e0b, #d97706)' :
                                        'linear-gradient(135deg, #10b981, #059669)');
                                ?>;"></div>
                                <!-- Task Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0 fw-semibold text-dark flex-grow-1 pe-2" style="font-size: 1.08rem; line-height: 1.4;">
                                        <?= esc(!empty($task['title']) ? $task['title'] : 'Untitled Task') ?>
                                    </h4>
                                    <span class="badge border-0" style="background: <?php
                                        echo $priority === 'high' ? 'linear-gradient(135deg, #fee2e2, #fecaca)' :
                                            ($priority === 'medium' ? 'linear-gradient(135deg, #fef3c7, #fde68a)' :
                                            'linear-gradient(135deg, #d1fae5, #a7f3d0)');
                                    ?>; color: <?php
                                        echo $priority === 'high' ? '#991b1b' :
                                            ($priority === 'medium' ? '#92400e' : '#065f46');
                                    ?>; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                        <?= ucfirst($priority) ?>
                                    </span>
                                </div>
                                <!-- Task Description -->
                                <?php if (!empty($task['description'])): ?>
                                    <div class="mb-2">
                                        <p class="mb-0 text-secondary" style="font-size: 0.93rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= esc(mb_substr($task['description'], 0, 100)) ?><?= mb_strlen($task['description']) > 100 ? '...' : '' ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Progress -->
                                <?php if (isset($task['progress']) && is_numeric($task['progress']) && $task['progress'] > 0): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-semibold text-muted">Progress</span>
                                            <span class="small fw-semibold text-primary"><?= (int)$task['progress'] ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 7px;">
                                            <div class="progress-bar" role="progressbar" style="width: <?= (int)$task['progress'] ?>%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" aria-valuenow="<?= (int)$task['progress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Meta Information -->
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 mt-2 border-top" style="border-color: #f1f5f9 !important;">
                                    <!-- Due Date -->
                                    <?php if (!empty($task['due_date'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="fas fa-calendar-alt text-muted" style="font-size: 0.85rem;"></i>
                                            <span class="text-secondary small fw-medium">
                                                <?php 
                                                $dueDate = strtotime($task['due_date']);
                                                echo $dueDate ? date('M d', $dueDate) : 'Invalid Date';
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Assignee Avatar -->
                                    <?php if (!empty($task['assigned_to'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <div style="width: 26px; height: 26px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 600;">
                                                <?= strtoupper(mb_substr(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'U', 0, 1)) ?>
                                            </div>
                                            <span class="text-secondary small fw-medium">
                                                <?= esc(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'Unassigned') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Task Actions -->
                                    <div class="d-flex align-items-center gap-1 ms-auto">
                                        <button onclick="typeof editTask === 'function' ? editTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('editTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #667eea; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#e0e7ff'; this.style.color='#4338ca'"
                                                onmouseout="this.style.background=''; this.style.color='#667eea'"
                                                title="Edit Task">
                                            <i class="fas fa-edit" style="font-size: 0.9rem;"></i>
                                        </button>
                                        <button onclick="typeof deleteTask === 'function' ? deleteTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('deleteTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #ef4444; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#fee2e2'; this.style.color='#b91c1c'"
                                                onmouseout="this.style.background=''; this.style.color='#ef4444'"
                                                title="Delete Task">
                                            <i class="fas fa-trash" style="font-size: 0.9rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Task Labels/Tags -->
                                <?php if (!empty($task['tags'])): ?>
                                    <div class="mt-2 d-flex flex-wrap gap-1">
                                        <?php foreach (explode(',', $task['tags']) as $tag): ?>
                                            <?php $tag = trim($tag); if ($tag !== ''): ?>
                                            <span class="badge" style="background: rgba(102,126,234,0.08); color: #667eea; font-size: 0.75rem; font-weight: 500; border-radius: 0.5rem;">
                                                <?= esc($tag) ?>
                                            </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

        <!-- In Progress Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s ease;" data-status="in_progress">
                <div class="card-header bg-white text-dark text-center py-3 border-0" style="border-left: 5px solid #fbbf24; border-radius: 1rem 1rem 0 0; font-weight: 700; letter-spacing: 0.01em;">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center gap-2">
                        <i class="fas fa-play-circle" style="color: #f59e42;"></i>
                        In Progress
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['in_progress']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="in_progress-tasks">
                    <?php if (isset($tasks['in_progress']) && is_array($tasks['in_progress']) && count($tasks['in_progress'])): ?>
                        <?php foreach ($tasks['in_progress'] as $task): ?>
                            <!-- Modern Kanban Task Card (inlined) -->
                            <div class="kanban-card shadow-sm bg-white border-0 mb-3" style="border-radius: 1rem; padding: 1.5rem 1.25rem 1.25rem 1.25rem; min-width: 280px; position: relative; transition: box-shadow 0.2s, transform 0.2s; cursor: grab; overflow: visible;"
                                 data-task-id="<?= isset($task['id']) ? (int)$task['id'] : 0 ?>"
                                 onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 8px 32px rgba(102,126,234,0.12)';"
                                 onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)';">
                                <!-- Priority Indicator -->
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; border-radius: 1rem 1rem 0 0; background: <?php
                                    $priority = !empty($task['priority_name']) ? strtolower($task['priority_name']) : (!empty($task['priority']) ? strtolower($task['priority']) : 'low');
                                    echo $priority === 'high' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                                        ($priority === 'medium' ? 'linear-gradient(135deg, #f59e0b, #d97706)' :
                                        'linear-gradient(135deg, #10b981, #059669)');
                                ?>;"></div>
                                <!-- Task Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0 fw-semibold text-dark flex-grow-1 pe-2" style="font-size: 1.08rem; line-height: 1.4;">
                                        <?= esc(!empty($task['title']) ? $task['title'] : 'Untitled Task') ?>
                                    </h4>
                                    <span class="badge border-0" style="background: <?php
                                        echo $priority === 'high' ? 'linear-gradient(135deg, #fee2e2, #fecaca)' :
                                            ($priority === 'medium' ? 'linear-gradient(135deg, #fef3c7, #fde68a)' :
                                            'linear-gradient(135deg, #d1fae5, #a7f3d0)');
                                    ?>; color: <?php
                                        echo $priority === 'high' ? '#991b1b' :
                                            ($priority === 'medium' ? '#92400e' : '#065f46');
                                    ?>; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                        <?= ucfirst($priority) ?>
                                    </span>
                                </div>
                                <!-- Task Description -->
                                <?php if (!empty($task['description'])): ?>
                                    <div class="mb-2">
                                        <p class="mb-0 text-secondary" style="font-size: 0.93rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= esc(mb_substr($task['description'], 0, 100)) ?><?= mb_strlen($task['description']) > 100 ? '...' : '' ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Progress -->
                                <?php if (isset($task['progress']) && is_numeric($task['progress']) && $task['progress'] > 0): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-semibold text-muted">Progress</span>
                                            <span class="small fw-semibold text-primary"><?= (int)$task['progress'] ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 7px;">
                                            <div class="progress-bar" role="progressbar" style="width: <?= (int)$task['progress'] ?>%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" aria-valuenow="<?= (int)$task['progress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Meta Information -->
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 mt-2 border-top" style="border-color: #f1f5f9 !important;">
                                    <!-- Due Date -->
                                    <?php if (!empty($task['due_date'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="fas fa-calendar-alt text-muted" style="font-size: 0.85rem;"></i>
                                            <span class="text-secondary small fw-medium">
                                                <?php 
                                                $dueDate = strtotime($task['due_date']);
                                                echo $dueDate ? date('M d', $dueDate) : 'Invalid Date';
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Assignee Avatar -->
                                    <?php if (!empty($task['assigned_to'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <div style="width: 26px; height: 26px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 600;">
                                                <?= strtoupper(mb_substr(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'U', 0, 1)) ?>
                                            </div>
                                            <span class="text-secondary small fw-medium">
                                                <?= esc(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'Unassigned') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Task Actions -->
                                    <div class="d-flex align-items-center gap-1 ms-auto">
                                        <button onclick="typeof editTask === 'function' ? editTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('editTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #667eea; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#e0e7ff'; this.style.color='#4338ca'"
                                                onmouseout="this.style.background=''; this.style.color='#667eea'"
                                                title="Edit Task">
                                            <i class="fas fa-edit" style="font-size: 0.9rem;"></i>
                                        </button>
                                        <button onclick="typeof deleteTask === 'function' ? deleteTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('deleteTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #ef4444; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#fee2e2'; this.style.color='#b91c1c'"
                                                onmouseout="this.style.background=''; this.style.color='#ef4444'"
                                                title="Delete Task">
                                            <i class="fas fa-trash" style="font-size: 0.9rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Task Labels/Tags -->
                                <?php if (isset($task['tags']) && !empty($task['tags'])): ?>
                                    <div class="mt-2 d-flex flex-wrap gap-1">
                                        <?php foreach (explode(',', $task['tags']) as $tag): ?>
                                            <?php $tag = trim($tag); if ($tag !== ''): ?>
                                            <span class="badge" style="background: rgba(102,126,234,0.08); color: #667eea; font-size: 0.75rem; font-weight: 500; border-radius: 0.5rem;">
                                                <?= esc($tag) ?>
                                            </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                <div class="card-header bg-white text-dark text-center py-3 border-0" style="border-left: 5px solid #38bdf8; border-radius: 1rem 1rem 0 0; font-weight: 700; letter-spacing: 0.01em;">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center gap-2">
                        <i class="fas fa-eye" style="color: #0ea5e9;"></i>
                        Review
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['review']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="review-tasks">
                    <?php if (isset($tasks['review']) && is_array($tasks['review'])): ?>
                        <?php foreach ($tasks['review'] as $task): ?>
                            <!-- Modern Kanban Task Card (inlined) -->
                            <div class="kanban-card shadow-sm bg-white border-0 mb-3" style="border-radius: 1rem; padding: 1.5rem 1.25rem 1.25rem 1.25rem; min-width: 280px; position: relative; transition: box-shadow 0.2s, transform 0.2s; cursor: grab; overflow: visible;"
                                 data-task-id="<?= isset($task['id']) ? (int)$task['id'] : 0 ?>"
                                 onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 8px 32px rgba(102,126,234,0.12)';"
                                 onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)';">
                                <!-- Priority Indicator -->
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; border-radius: 1rem 1rem 0 0; background: <?php
                                    $priority = !empty($task['priority_name']) ? strtolower($task['priority_name']) : (!empty($task['priority']) ? strtolower($task['priority']) : 'low');
                                    echo $priority === 'high' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                                        ($priority === 'medium' ? 'linear-gradient(135deg, #f59e0b, #d97706)' :
                                        'linear-gradient(135deg, #10b981, #059669)');
                                ?>;"></div>
                                <!-- Task Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0 fw-semibold text-dark flex-grow-1 pe-2" style="font-size: 1.08rem; line-height: 1.4;">
                                        <?= esc(!empty($task['title']) ? $task['title'] : 'Untitled Task') ?>
                                    </h4>
                                    <span class="badge border-0" style="background: <?php
                                        echo $priority === 'high' ? 'linear-gradient(135deg, #fee2e2, #fecaca)' :
                                            ($priority === 'medium' ? 'linear-gradient(135deg, #fef3c7, #fde68a)' :
                                            'linear-gradient(135deg, #d1fae5, #a7f3d0)');
                                    ?>; color: <?php
                                        echo $priority === 'high' ? '#991b1b' :
                                            ($priority === 'medium' ? '#92400e' : '#065f46');
                                    ?>; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                        <?= ucfirst($priority) ?>
                                    </span>
                                </div>
                                <!-- Task Description -->
                                <?php if (!empty($task['description'])): ?>
                                    <div class="mb-2">
                                        <p class="mb-0 text-secondary" style="font-size: 0.93rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= esc(mb_substr($task['description'], 0, 100)) ?><?= mb_strlen($task['description']) > 100 ? '...' : '' ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Progress -->
                                <?php if (isset($task['progress']) && is_numeric($task['progress']) && $task['progress'] > 0): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-semibold text-muted">Progress</span>
                                            <span class="small fw-semibold text-primary"><?= (int)$task['progress'] ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 7px;">
                                            <div class="progress-bar" role="progressbar" style="width: <?= (int)$task['progress'] ?>%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" aria-valuenow="<?= (int)$task['progress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Meta Information -->
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 mt-2 border-top" style="border-color: #f1f5f9 !important;">
                                    <!-- Due Date -->
                                    <?php if (!empty($task['due_date'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="fas fa-calendar-alt text-muted" style="font-size: 0.85rem;"></i>
                                            <span class="text-secondary small fw-medium">
                                                <?php 
                                                $dueDate = strtotime($task['due_date']);
                                                echo $dueDate ? date('M d', $dueDate) : 'Invalid Date';
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Assignee Avatar -->
                                    <?php if (!empty($task['assigned_to'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <div style="width: 26px; height: 26px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 600;">
                                                <?= strtoupper(mb_substr(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'U', 0, 1)) ?>
                                            </div>
                                            <span class="text-secondary small fw-medium">
                                                <?= esc(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'Unassigned') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Task Actions -->
                                    <div class="d-flex align-items-center gap-1 ms-auto">
                                        <button onclick="typeof editTask === 'function' ? editTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('editTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #667eea; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#e0e7ff'; this.style.color='#4338ca'"
                                                onmouseout="this.style.background=''; this.style.color='#667eea'"
                                                title="Edit Task">
                                            <i class="fas fa-edit" style="font-size: 0.9rem;"></i>
                                        </button>
                                        <button onclick="typeof deleteTask === 'function' ? deleteTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('deleteTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #ef4444; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#fee2e2'; this.style.color='#b91c1c'"
                                                onmouseout="this.style.background=''; this.style.color='#ef4444'"
                                                title="Delete Task">
                                            <i class="fas fa-trash" style="font-size: 0.9rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Task Labels/Tags -->
                                <?php if (isset($task['tags']) && !empty($task['tags'])): ?>
                                    <div class="mt-2 d-flex flex-wrap gap-1">
                                        <?php foreach (explode(',', $task['tags']) as $tag): ?>
                                            <?php $tag = trim($tag); if ($tag !== ''): ?>
                                            <span class="badge" style="background: rgba(102,126,234,0.08); color: #667eea; font-size: 0.75rem; font-weight: 500; border-radius: 0.5rem;">
                                                <?= esc($tag) ?>
                                            </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
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
                <div class="card-header bg-white text-dark text-center py-3 border-0" style="border-left: 5px solid #10b981; border-radius: 1rem 1rem 0 0; font-weight: 700; letter-spacing: 0.01em;">
                    <h5 class="mb-0 fw-semibold d-flex justify-content-center align-items-center gap-2">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        Done
                        <span class="badge bg-light text-dark ms-2"><?= count($tasks['completed']) ?></span>
                    </h5>
                </div>
                <div class="card-body p-3" style="background: #f8f9fa; min-height: 400px; max-height: 600px; overflow-y: auto;" id="completed-tasks">
                    <?php if (isset($tasks['completed']) && is_array($tasks['completed'])): ?>
                        <?php foreach ($tasks['completed'] as $task): ?>
                            <!-- Modern Kanban Task Card (inlined) -->
                            <div class="kanban-card shadow-sm bg-white border-0 mb-3" style="border-radius: 1rem; padding: 1.5rem 1.25rem 1.25rem 1.25rem; min-width: 280px; position: relative; transition: box-shadow 0.2s, transform 0.2s; cursor: grab; overflow: visible;"
                                 data-task-id="<?= isset($task['id']) ? (int)$task['id'] : 0 ?>"
                                 onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 8px 32px rgba(102,126,234,0.12)';"
                                 onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)';">
                                <!-- Priority Indicator -->
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; border-radius: 1rem 1rem 0 0; background: <?php
                                    $priority = !empty($task['priority_name']) ? strtolower($task['priority_name']) : (!empty($task['priority']) ? strtolower($task['priority']) : 'low');
                                    echo $priority === 'high' ? 'linear-gradient(135deg, #ef4444, #dc2626)' :
                                        ($priority === 'medium' ? 'linear-gradient(135deg, #f59e0b, #d97706)' :
                                        'linear-gradient(135deg, #10b981, #059669)');
                                ?>;"></div>
                                <!-- Task Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="mb-0 fw-semibold text-dark flex-grow-1 pe-2" style="font-size: 1.08rem; line-height: 1.4;">
                                        <?= esc(!empty($task['title']) ? $task['title'] : 'Untitled Task') ?>
                                    </h4>
                                    <span class="badge border-0" style="background: <?php
                                        echo $priority === 'high' ? 'linear-gradient(135deg, #fee2e2, #fecaca)' :
                                            ($priority === 'medium' ? 'linear-gradient(135deg, #fef3c7, #fde68a)' :
                                            'linear-gradient(135deg, #d1fae5, #a7f3d0)');
                                    ?>; color: <?php
                                        echo $priority === 'high' ? '#991b1b' :
                                            ($priority === 'medium' ? '#92400e' : '#065f46');
                                    ?>; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                        <?= ucfirst($priority) ?>
                                    </span>
                                </div>
                                <!-- Task Description -->
                                <?php if (!empty($task['description'])): ?>
                                    <div class="mb-2">
                                        <p class="mb-0 text-secondary" style="font-size: 0.93rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= esc(mb_substr($task['description'], 0, 100)) ?><?= mb_strlen($task['description']) > 100 ? '...' : '' ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Progress -->
                                <?php if (isset($task['progress']) && is_numeric($task['progress']) && $task['progress'] > 0): ?>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-semibold text-muted">Progress</span>
                                            <span class="small fw-semibold text-primary"><?= (int)$task['progress'] ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 7px;">
                                            <div class="progress-bar" role="progressbar" style="width: <?= (int)$task['progress'] ?>%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" aria-valuenow="<?= (int)$task['progress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- Task Meta Information -->
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 mt-2 border-top" style="border-color: #f1f5f9 !important;">
                                    <!-- Due Date -->
                                    <?php if (!empty($task['due_date'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="fas fa-calendar-alt text-muted" style="font-size: 0.85rem;"></i>
                                            <span class="text-secondary small fw-medium">
                                                <?php 
                                                $dueDate = strtotime($task['due_date']);
                                                echo $dueDate ? date('M d', $dueDate) : 'Invalid Date';
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Assignee Avatar -->
                                    <?php if (!empty($task['assigned_to'])): ?>
                                        <div class="d-flex align-items-center gap-1">
                                            <div style="width: 26px; height: 26px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; font-weight: 600;">
                                                <?= strtoupper(mb_substr(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'U', 0, 1)) ?>
                                            </div>
                                            <span class="text-secondary small fw-medium">
                                                <?= esc(isset($task['assignee_name']) && $task['assignee_name'] ? $task['assignee_name'] : 'Unassigned') ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Task Actions -->
                                    <div class="d-flex align-items-center gap-1 ms-auto">
                                        <button onclick="typeof editTask === 'function' ? editTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('editTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #667eea; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#e0e7ff'; this.style.color='#4338ca'"
                                                onmouseout="this.style.background=''; this.style.color='#667eea'"
                                                title="Edit Task">
                                            <i class="fas fa-edit" style="font-size: 0.9rem;"></i>
                                        </button>
                                        <button onclick="typeof deleteTask === 'function' ? deleteTask(<?= isset($task['id']) ? (int)$task['id'] : 0 ?>) : console.warn('deleteTask function not defined')"
                                                class="btn btn-light btn-sm px-2 py-1 border-0" style="color: #ef4444; border-radius: 0.35rem; transition: background 0.2s, color 0.2s;"
                                                onmouseover="this.style.background='#fee2e2'; this.style.color='#b91c1c'"
                                                onmouseout="this.style.background=''; this.style.color='#ef4444'"
                                                title="Delete Task">
                                            <i class="fas fa-trash" style="font-size: 0.9rem;"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Task Labels/Tags -->
                                <?php if (isset($task['tags']) && !empty($task['tags'])): ?>
                                    <div class="mt-2 d-flex flex-wrap gap-1">
                                        <?php foreach (explode(',', $task['tags']) as $tag): ?>
                                            <?php $tag = trim($tag); if ($tag !== ''): ?>
                                            <span class="badge" style="background: rgba(102,126,234,0.08); color: #667eea; font-size: 0.75rem; font-weight: 500; border-radius: 0.5rem;">
                                                <?= esc($tag) ?>
                                            </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
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
                evt.item.style.opacity = '0.5';
            },
            onEnd: function(evt) {
                evt.item.style.opacity = '';
                // Ensure taskId is a valid integer
                let taskId = evt.item.dataset.taskId;
                if (!taskId || isNaN(taskId)) {
                    console.error('Invalid or missing data-task-id on card:', evt.item);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Task ID missing on card. Please refresh.' });
                    return;
                }
                taskId = parseInt(taskId, 10);
                const newStatus = evt.to.id.replace('-tasks', '');
                const newPosition = evt.newIndex;
                // Get new order of all task IDs in this column, as integers
                const order = Array.from(evt.to.querySelectorAll('[data-task-id]'))
                    .map(card => parseInt(card.dataset.taskId, 10))
                    .filter(id => !isNaN(id));
                updateTaskStatus(taskId, newStatus, newPosition, order);
            },
            onAdd: function(evt) {
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

async function updateTaskStatus(taskId, newStatus, newPosition, order) {
    try {
        await $.ajax({
            url: '<?= base_url('tasks/updateStatus') ?>',
            method: 'POST',
            data: {
                task_id: taskId,
                status: newStatus,
                position: newPosition,
                order: order
            },
            traditional: true, // ensures array is sent as order[]=1&order[]=2
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    } catch (error) {
        console.error('Error updating task status:', error);
        if (error && error.responseText) {
            console.error('Raw responseText:', error.responseText);
        }
    }
    // Always reload after AJAX, regardless of result
    location.reload();
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
    // Fetch task data and show edit modal
    $.ajax({
        url: '<?= base_url('tasks/edit/') ?>' + taskId,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(html) {
            // Log the raw HTML response for debugging
            console.log('[editTask] Raw HTML response:', html);
            let modalDiv = document.getElementById('editTaskModalContainer');
            if (!modalDiv) {
                modalDiv = document.createElement('div');
                modalDiv.id = 'editTaskModalContainer';
                document.body.appendChild(modalDiv);
            }
            modalDiv.innerHTML = html;
            // Find the modal element
            const modalEl = modalDiv.querySelector('.modal');
            if (!modalEl) {
                // Show the raw response in a collapsible details for easier debugging
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: '<div>Edit modal markup not found in response.<br><br><details style="text-align:left;"><summary>Show raw response</summary><pre style="max-height:200px;overflow:auto;">' +
                        $('<div>').text(html).html() + '</pre></details></div>'
                });
                return;
            }
            // Remove any previously initialized modals
            if (modalEl.classList.contains('show')) {
                bootstrap.Modal.getInstance(modalEl)?.hide();
            }
            // Ensure modal is initialized only once
            let modal;
            try {
                modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            } catch (e) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();

            // Attach submit handler for edit form
            const form = modalDiv.querySelector('form');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    try {
                        const result = await $.ajax({
                            url: form.action,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (result.success) {
                            modal.hide();
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to update task.'
                            });
                        }
                    } catch (error) {
                        console.error('Error updating task:', error);
                        if (error && error.responseText) {
                            console.error('Raw responseText:', error.responseText);
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the task.'
                        });
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading edit task modal:', xhr);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load task for editing.'
            });
        }
    });
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
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await $.ajax({
                    url: '<?= base_url('tasks/delete/') ?>' + taskId,
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            } catch (error) {
                console.error('Error deleting task:', error);
                if (error && error.responseText) {
                    console.error('Raw responseText:', error.responseText);
                }
            }
            // Always reload after AJAX, regardless of result
            location.reload();
        }
    });
}
</script>
