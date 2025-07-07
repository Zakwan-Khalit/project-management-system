<!-- Modern Kanban Task Card -->
<div style="background: white; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; cursor: grab; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; min-width: 280px;" 
     data-task-id="<?= $task['id'] ?>"
     onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.1)'"
     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'">>
    
    <!-- Priority Indicator -->
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: <?= 
        $task['priority'] === 'high' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
        ($task['priority'] === 'medium' ? 'linear-gradient(135deg, #f59e0b, #d97706)' : 
        'linear-gradient(135deg, #10b981, #059669)') 
    ?>;"></div>
    
    <!-- Task Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
        <h4 style="margin: 0; font-size: 1rem; font-weight: 600; color: #1f2937; line-height: 1.4; flex: 1; padding-right: 0.5rem;"><?= esc($task['title']) ?></h4>
        <span style="background: <?= 
            $task['priority'] === 'high' ? 'linear-gradient(135deg, #fee2e2, #fecaca)' : 
            ($task['priority'] === 'medium' ? 'linear-gradient(135deg, #fef3c7, #fde68a)' : 
            'linear-gradient(135deg, #d1fae5, #a7f3d0)') 
        ?>; color: <?= 
            $task['priority'] === 'high' ? '#991b1b' : 
            ($task['priority'] === 'medium' ? '#92400e' : '#065f46') 
        ?>; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em; flex-shrink: 0;">
            <?= ucfirst($task['priority']) ?>
        </span>
    </div>
    
    <!-- Task Description -->
    <?php if (!empty($task['description'])): ?>
        <div style="margin-bottom: 1rem;">
            <p style="margin: 0; color: #6b7280; font-size: 0.875rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                <?= esc(substr($task['description'], 0, 100)) ?><?= strlen($task['description']) > 100 ? '...' : '' ?>
            </p>
        </div>
    <?php endif; ?>
    
    <!-- Task Progress -->
    <?php if (isset($task['progress']) && $task['progress'] > 0): ?>
        <div style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-size: 0.75rem; font-weight: 600; color: #374151;">Progress</span>
                <span style="font-size: 0.75rem; font-weight: 600; color: #667eea;"><?= $task['progress'] ?>%</span>
            </div>
            <div style="height: 6px; background: #f1f5f9; border-radius: 1rem; overflow: hidden;">
                <div style="height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 1rem; width: <?= $task['progress'] ?>%; transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);"></div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Task Meta Information -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; flex-wrap: wrap; gap: 0.5rem;">
        
        <!-- Due Date -->
        <?php if (!empty($task['due_date'])): ?>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-calendar-alt" style="color: #9ca3af; font-size: 0.75rem;"></i>
                <span style="color: #6b7280; font-size: 0.75rem; font-weight: 500;">
                    <?php 
                    $dueDate = strtotime($task['due_date']);
                    echo $dueDate ? date('M d', $dueDate) : 'Invalid Date';
                    ?>
                </span>
            </div>
        <?php endif; ?>
        
        <!-- Assignee Avatar -->
        <?php if (!empty($task['assigned_to'])): ?>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 24px; height: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.7rem; font-weight: 600;">
                    <?= strtoupper(substr($task['assignee_name'] ?? 'U', 0, 1)) ?>
                </div>
                <span style="color: #6b7280; font-size: 0.75rem; font-weight: 500;">
                    <?= esc($task['assignee_name'] ?? 'Unassigned') ?>
                </span>
            </div>
        <?php endif; ?>
        
        <!-- Task Actions -->
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-left: auto;">
            <button onclick="typeof editTask === 'function' ? editTask(<?= $task['id'] ?>) : console.warn('editTask function not defined')" 
                    style="background: none; border: none; color: #9ca3af; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem; transition: color 0.2s ease;"
                    onmouseover="this.style.color='#667eea'"
                    onmouseout="this.style.color='#9ca3af'"
                    title="Edit Task">
                <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
            </button>
            <button onclick="typeof deleteTask === 'function' ? deleteTask(<?= $task['id'] ?>) : console.warn('deleteTask function not defined')" 
                    style="background: none; border: none; color: #9ca3af; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem; transition: color 0.2s ease;"
                    onmouseover="this.style.color='#ef4444'"
                    onmouseout="this.style.color='#9ca3af'"
                    title="Delete Task">
                <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
            </button>
        </div>
    </div>
    
    <!-- Task Labels/Tags -->
    <?php if (!empty($task['tags'])): ?>
        <div style="margin-top: 0.75rem; display: flex; flex-wrap: wrap; gap: 0.25rem;">
            <?php foreach (explode(',', $task['tags']) as $tag): ?>
                <span style="background: rgba(102,126,234,0.1); color: #667eea; padding: 0.125rem 0.5rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 500;">
                    <?= esc(trim($tag)) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
