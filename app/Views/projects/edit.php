<!-- Project Edit Form -->
<div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;">
        <ol style="display: flex; list-style: none; padding: 1rem 1.25rem; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2); border: none;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('projects') ?>" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 0.375rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                    <i class="fas fa-project-diagram" style="margin-right: 0.5rem; font-size: 0.9rem;"></i>
                    Projects
                </a>
                <span style="margin: 0 0.75rem; color: #e2e8f0; font-size: 1.1rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; padding: 0.25rem 0.5rem; background: rgba(255,255,255,0.1); border-radius: 0.375rem; backdrop-filter: blur(10px);">
                <i class="fas fa-edit" style="margin-right: 0.5rem; font-size: 0.85rem; opacity: 0.9;"></i>
                Edit Project
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 2.5rem 2rem; margin-bottom: 3rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
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
                <!-- Left Column -->
                <div>
                    <!-- Project Name -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Project Name *
                    </label>
                    <input type="text" name="name" required value="<?= esc($project['name'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter project name">

                    <!-- Project Code -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        Project Code
                    </label>
                    <input type="text" name="code" value="<?= esc($project['code'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="PRJ-2025-001">

                    <!-- Status -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        Status
                    </label>
                    <select name="status" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                            onmouseenter="this.style.borderColor='#4299e1'"
                            onmouseleave="this.style.borderColor='#e2e8f0'"
                            onfocus="this.style.borderColor='#4299e1'; this.style.background='white'"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">
                        <option value="planning" <?= (($project['status_name'] ?? '') == 'Planning') ? 'selected' : '' ?>>Planning</option>
                        <option value="active" <?= (($project['status_name'] ?? '') == 'Active') ? 'selected' : '' ?>>Active</option>
                        <option value="on_hold" <?= (($project['status_name'] ?? '') == 'On Hold') ? 'selected' : '' ?>>On Hold</option>
                        <option value="completed" <?= (($project['status_name'] ?? '') == 'Completed') ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Start Date -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Start Date
                    </label>
                    <input type="date" name="start_date" value="<?= esc($project['start_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">

                    <!-- End Date -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        End Date
                    </label>
                    <input type="date" name="end_date" value="<?= esc($project['end_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">

                    <!-- Client -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        Client
                    </label>
                    <input type="text" name="client" value="<?= esc($project['client'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter client name">
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                    Description
                </label>
                <textarea name="description" rows="4" 
                          style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc; resize: vertical;"
                          onmouseenter="this.style.borderColor='#4299e1'"
                          onmouseleave="this.style.borderColor='#e2e8f0'"
                          onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                          onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                          placeholder="Describe the project goals, objectives, and scope"><?= esc($project['description'] ?? '') ?></textarea>
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
                        style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; border: none; border-radius: 0.75rem; padding: 1rem 2rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(66,153,225,0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(66,153,225,0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(66,153,225,0.3)'">
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

    $.ajax({
        url: '<?= base_url('projects/edit/' . ($project['id'] ?? '')) ?>',
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
                    confirmButtonColor: '#4299e1'
                }).then(() => {
                    window.location.href = '<?= base_url('projects') ?>';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to update project',
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
