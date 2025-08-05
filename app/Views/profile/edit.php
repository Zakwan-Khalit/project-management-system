<!-- Edit Profile Page -->

<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('profile') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-user" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                    Profile
                </a>
                <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
            </li>
            <li style="color: #f7fafc; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem;">
                <i class="fas fa-edit" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Edit Profile
            </li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Profile Form -->
            <div style="background: white; border-radius: 1rem; padding: 2.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
                <div class="d-flex align-items-center mb-4">
                    <div style="width: 4px; height: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2px; margin-right: 1rem;"></div>
                    <h5 class="mb-0 fw-semibold text-dark">Personal Information</h5>
                </div>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" style="border-radius: 0.75rem; border: none;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation) && $validation->getErrors()): ?>
                    <div class="alert alert-danger" style="border-radius: 0.75rem; border: none;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($validation->getErrors() as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="profileForm" action="<?= base_url('profile/update') ?>" method="post">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label fw-medium text-dark">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label for="full_name" class="form-label fw-medium text-dark">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= esc($user['full_name'] ?? '') ?>" style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label for="phone" class="form-label fw-medium text-dark">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>" placeholder="e.g., +1 (555) 123-4567" style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4 pt-3" style="border-top: 1px solid #f3f4f6; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary px-4 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 0.5rem; font-weight: 500;">
                            <i class="fas fa-save me-2"></i>
                            Save Changes
                        </button>
                        <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 0.5rem; font-weight: 500;">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05);">
                <div class="d-flex align-items-center mb-3">
                    <div style="width: 4px; height: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2px; margin-right: 0.75rem;"></div>
                    <h6 class="mb-0 fw-semibold text-dark">Quick Actions</h6>
                </div>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('profile/change-password') ?>" class="btn btn-outline-primary" style="border-radius: 0.5rem; padding: 0.75rem; font-weight: 500; border-color: #667eea; color: #667eea;">
                        <i class="fas fa-lock me-2"></i>
                        Change Password
                    </a>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary" style="border-radius: 0.5rem; padding: 0.75rem; font-weight: 500;">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Profile updated successfully!',
                    confirmButtonColor: '#667eea',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = '<?= base_url('profile') ?>';
                });
            } else {
                let errorMessage = data.message || 'Update failed';
                if (data.errors) {
                    errorMessage += '\n\nErrors:\n';
                    Object.values(data.errors).forEach(error => {
                        errorMessage += '• ' + error + '\n';
                    });
                }
                throw new Error(errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'An unexpected error occurred. Please try again.',
                confirmButtonColor: '#667eea'
            });
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
});
</script>


