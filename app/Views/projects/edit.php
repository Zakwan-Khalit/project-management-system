<!-- Project Edit Form -->
<div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">

    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border-radius: 1.5rem; padding: 2.5rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(237,137,54,0.2); position: relative; overflow: hidden;">
        <!-- Decorative Elements -->
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
        <div style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 2;">
            <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.75rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-edit" style="margin-right: 0.75rem; color: rgba(255,255,255,0.9);"></i>
                Edit Project
            </h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 1.1rem; margin-bottom: 0; font-weight: 400;">
                Update project details and settings
            </p>
        </div>
    </div>

    <!-- Project Form -->
    <div style="background: white; border-radius: 1.5rem; padding: 3rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto;">
        <form id="projectForm" onsubmit="submitProject(event)">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Project Name -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Project Name *
                    </label>
                    <input type="text" name="name" required value="<?= esc($project['name'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#ed8936'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#ed8936'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(237,137,54,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter project name">
                </div>

                <!-- Project Code -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Project Code
                    </label>
                    <input type="text" name="code" value="<?= esc($project['code'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#ed8936'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#ed8936'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(237,137,54,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="PRJ-2025-001">
                </div>

                <!-- Priority -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Priority
                    </label>
                    <select name="priority" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseenter="this.style.borderColor='#ed8936'"
                            onmouseleave="this.style.borderColor='#e2e8f0'"
                            onfocus="this.style.borderColor='#ed8936'; this.style.background='white'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                        <option value="low" <?= (($project['priority_name'] ?? '') == 'Low') ? 'selected' : '' ?>>Low Priority</option>
                        <option value="medium" <?= (($project['priority_name'] ?? '') == 'Medium') ? 'selected' : '' ?>>Medium Priority</option>
                        <option value="high" <?= (($project['priority_name'] ?? '') == 'High') ? 'selected' : '' ?>>High Priority</option>
                        <option value="urgent" <?= (($project['priority_name'] ?? '') == 'Urgent') ? 'selected' : '' ?>>Urgent Priority</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Start Date
                    </label>
                    <input type="date" name="start_date" value="<?= esc($project['start_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#ed8936'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#ed8936'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                </div>

                <!-- End Date -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        End Date
                    </label>
                    <input type="date" name="end_date" value="<?= esc($project['end_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#ed8936'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#ed8936'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                </div>

                <!-- Budget -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Budget
                    </label>
                    <input type="number" name="budget" step="0.01" value="<?= esc($project['budget'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#ed8936'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#ed8936'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'"
                           placeholder="0.00">
                </div>

                <!-- Status -->
                <div>
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Status
                    </label>
                    <select name="status" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseenter="this.style.borderColor='#ed8936'"
                            onmouseleave="this.style.borderColor='#e2e8f0'"
                            onfocus="this.style.borderColor='#ed8936'; this.style.background='white'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                        <option value="planning" <?= (($project['status_name'] ?? '') == 'Planning') ? 'selected' : '' ?>>Planning</option>
                        <option value="active" <?= (($project['status_name'] ?? '') == 'Active') ? 'selected' : '' ?>>Active</option>
                        <option value="on_hold" <?= (($project['status_name'] ?? '') == 'On Hold') ? 'selected' : '' ?>>On Hold</option>
                        <option value="completed" <?= (($project['status_name'] ?? '') == 'Completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>

                <!-- Description -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Description
                    </label>
                    <textarea name="description" rows="4" 
                              style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc; resize: vertical;"
                              onmouseenter="this.style.borderColor='#ed8936'"
                              onmouseleave="this.style.borderColor='#e2e8f0'"
                              onfocus="this.style.borderColor='#ed8936'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(237,137,54,0.1)'"
                              onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                              placeholder="Describe the project goals, objectives, and scope"><?= esc($project['description'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 3rem;">
                <button type="button" onclick="window.location.href='<?= base_url('projects') ?>'" 
                        style="background: #e2e8f0; color: #4a5568; border: none; border-radius: 0.75rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#cbd5e0'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='#e2e8f0'; this.style.transform='translateY(0)'">
                    Cancel
                </button>
                <button type="submit" 
                        style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; border: none; border-radius: 0.75rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(237,137,54,0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(237,137,54,0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(237,137,54,0.3)'">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                    Update Project
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function submitProject(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    submitBtn.disabled = true;
    
    fetch('<?= base_url('projects/edit/' . ($project['id'] ?? '')) ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#ed8936'
            }).then(() => {
                window.location.href = '<?= base_url('projects') ?>';
            });
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Failed to update project',
                confirmButtonColor: '#e53e3e'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An unexpected error occurred',
            confirmButtonColor: '#e53e3e'
        });
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
</script>
