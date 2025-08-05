<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
    <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
        <li style="display: flex; align-items: center;">
            <a href="<?= base_url('projects') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                <i class="fas fa-project-diagram" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Projects
            </a>
            <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
        </li>
        <li style="display: flex; align-items: center;">
            <a href="<?= base_url('projects/view/' . ($project['id'] ?? '')) ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                <i class="fas fa-eye" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Project Details
            </a>
            <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
        </li>
        <li style="color: #ffffff; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem; background: rgba(255,255,255,0.1); border-radius: 0.2rem;">
            <i class="fas fa-edit" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
            Edit Project
        </li>
    </ol>
</nav>

<!-- Project Edit Form -->
<div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">

    <!-- Project Form -->
    <div style="background: white; border-radius: 1.5rem; padding: 3rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto;">
        <form id="projectForm" onsubmit="submitProject(event)">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Left Column: 1. Project Name, 3. Start Date, 5. Cost -->
                <div>
                    <!-- Project Name -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Project Name <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="name" required value="<?= esc($project['name'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter project name">

                    <!-- Start Date -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        Start Date <span style="color: red;">*</span>
                    </label>
                    <input type="date" name="start_date" required value="<?= esc($project['start_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">

                    <!-- Cost -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        Project Cost <span style="color: red;">*</span>
                    </label>
                    <input type="number" name="cost" required value="<?= esc($project['cost'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter project cost">
                </div>

                <!-- Right Column: 2. Status, 4. End Date, 6. Client -->
                <div>
                    <!-- Status -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem;">
                        Status <span style="color: red;">*</span>
                    </label>
                    <select name="status" required
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

                    <!-- End Date -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        End Date <span style="color: red;">*</span>
                    </label>
                    <input type="date" name="end_date" required value="<?= esc($project['end_date'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'">

                    <!-- Client -->
                    <label style="display: block; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem; font-size: 1rem; margin-top: 2rem;">
                        Client <span style="color: red;">*</span>
                    </label>
                    <input type="text" name="client" required value="<?= esc($project['client'] ?? '') ?>"
                           style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onmouseenter="this.style.borderColor='#4299e1'"
                           onmouseleave="this.style.borderColor='#e2e8f0'"
                           onfocus="this.style.borderColor='#4299e1'; this.style.background='white'; this.style.boxShadow='0 0 0 3px rgba(66,153,225,0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="Enter client name">
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 3rem;">
                <button type="submit" 
                        style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center;"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102,126,234,0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                    Update Project
                </button>
                <button type="button" onclick="window.location.href='<?= base_url('projects/view/' . ($project['id'] ?? '')) ?>'" 
                        style="padding: 0.75rem 1.5rem; border: 2px solid #6b7280; color: #6b7280; background: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: all 0.2s ease; display: inline-flex; align-items: center;"
                        onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#374151'; this.style.color='#374151'"
                        onmouseout="this.style.background='white'; this.style.borderColor='#6b7280'; this.style.color='#6b7280'"
                        href="<?= base_url('projects/view/' . ($project['id'] ?? '')) ?>"
                        type="button">
                    <i class="fas fa-times" style="margin-right: 0.5rem;"></i>
                    Cancel
                </a>
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
