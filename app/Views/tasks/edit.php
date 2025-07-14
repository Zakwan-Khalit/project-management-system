<!-- Task Edit Form -->
<div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">

    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 2.5rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 2;">
            <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-edit" style="margin-right: 0.75rem; color: rgba(255,255,255,0.9);"></i>
                Edit Task
            </h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 1.1rem; margin-bottom: 0; font-weight: 400;">
                Update task details and assignments
            </p>
        </div>
    </div>

    <!-- Task Form -->
    <div style="background: white; border-radius: 1.5rem; padding: 3rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto;">
        <form id="taskForm" onsubmit="submitTask(event)">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Task Title -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Task Title *
                    </label>
                    <input type="text" name="title" required value="<?= esc($task['title'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#667eea'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter task title">
                </div>

                <!-- Assigned To -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Assigned To
                    </label>
                    <select name="assigned_to" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseenter="this.style.borderColor='#667eea'"
                            onmouseleave="this.style.borderColor='#e2e8f0'"
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                        <?php if (isset($users) && is_array($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= esc($user['id']) ?>" 
                                    <?= (isset($task['owner_id']) && $task['owner_id'] == $user['id']) ? 'selected' : '' ?>>
                                    <?= esc(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?> 
                                    (<?= esc($user['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Priority
                    </label>
                    <select name="priority" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseenter="this.style.borderColor='#667eea'"
                            onmouseleave="this.style.borderColor='#e2e8f0'"
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                        <option value="low" <?= (isset($task['priority_name']) && $task['priority_name'] == 'Low') ? 'selected' : '' ?>>Low Priority</option>
                        <option value="medium" <?= (isset($task['priority_name']) && $task['priority_name'] == 'Medium') ? 'selected' : '' ?>>Medium Priority</option>
                        <option value="high" <?= (isset($task['priority_name']) && $task['priority_name'] == 'High') ? 'selected' : '' ?>>High Priority</option>
                        <option value="urgent" <?= (isset($task['priority_name']) && $task['priority_name'] == 'Urgent') ? 'selected' : '' ?>>Urgent Priority</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Status
                    </label>
                    <select name="status" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseenter="this.style.borderColor='#667eea'"
                            onmouseleave="this.style.borderColor='#e2e8f0'"
                            onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                        <option value="todo" <?= (isset($task['status_code']) && $task['status_code'] == 'todo') ? 'selected' : '' ?>>To Do</option>
                        <option value="in_progress" <?= (isset($task['status_code']) && $task['status_code'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="review" <?= (isset($task['status_code']) && $task['status_code'] == 'review') ? 'selected' : '' ?>>In Review</option>
                        <option value="completed" <?= (isset($task['status_code']) && $task['status_code'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>

                <!-- Due Date -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Due Date
                    </label>
                    <input type="date" name="due_date" value="<?= esc($task['due_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#667eea'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                </div>

                <!-- Estimated Hours -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Estimated Hours
                    </label>
                    <input type="number" name="estimated_hours" step="0.5" value="<?= esc($task['estimated_hours'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#667eea'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'"
                           placeholder="0.0">
                </div>

                <!-- Actual Hours -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Actual Hours
                    </label>
                    <input type="number" name="actual_hours" step="0.5" value="<?= esc($task['actual_hours'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#667eea'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'"
                           placeholder="0.0">
                </div>

                <!-- Progress -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Progress (%)
                    </label>
                    <input type="number" name="progress" min="0" max="100" value="<?= esc($task['progress'] ?? '0') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#667eea'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#667eea'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'"
                           placeholder="0">
                </div>

                <!-- Description -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Description
                    </label>
                    <textarea name="description" rows="4" 
                              style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc; resize: vertical;"
                              onmouseenter="this.style.borderColor='#667eea'"
                              onmouseleave="this.style.borderColor='#e2e8f0'"
                              onfocus="this.style.borderColor='#667eea'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                              onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                              placeholder="Describe the task requirements and objectives"><?= esc($task['description'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 3rem;">
                <button type="button" onclick="window.location.href='<?= base_url('tasks') ?>'" 
                        style="background: #e2e8f0; color: #4a5568; border: none; border-radius: 0.75rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#cbd5e0'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='#e2e8f0'; this.style.transform='translateY(0)'">
                    Cancel
                </button>
                <button type="submit" 
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.75rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(102,126,234,0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(102,126,234,0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(102,126,234,0.3)'">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                    Update Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function submitTask(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;

    $.ajax({
        url: '<?= base_url('tasks/edit/' . ($task['id'] ?? '')) ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    confirmButtonColor: '#667eea'
                }).then(() => {
                    window.location.href = '<?= base_url('tasks') ?>';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to update task',
                    confirmButtonColor: '#e53e3e'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred',
                confirmButtonColor: '#e53e3e'
            });
        },
        complete: function() {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
}
</script>
