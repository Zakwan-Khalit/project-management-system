<!-- Edit Profile Page -->

<div class="container-fluid">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h2 mb-2 fw-bold">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit Profile
                </h1>
                <p class="mb-0 opacity-75">Update your personal information and account settings</p>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('profile') ?>" class="btn btn-light" style="border-radius: 0.5rem; font-weight: 500;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

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

                <form id="profileForm" action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label fw-medium text-dark">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label fw-medium text-dark">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label fw-medium text-dark">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-medium text-dark">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= esc($user['phone'] ?? '') ?>" placeholder="e.g., +1 (555) 123-4567" style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                        </div>
                        <div class="col-md-6">
                            <label for="avatar" class="form-label fw-medium text-dark">Profile Picture</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem;">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Supported formats: JPG, PNG, GIF, WebP (Max: 5MB)
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="bio" class="form-label fw-medium text-dark">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Tell us about yourself, your role, or interests..." style="border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 0.75rem; font-size: 0.95rem; resize: vertical;"><?= esc($user['bio'] ?? '') ?></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Maximum 500 characters
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4 pt-3" style="border-top: 1px solid #f3f4f6;">
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
            <!-- Current Avatar -->
            <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 1.5rem;">
                <div class="d-flex align-items-center mb-3">
                    <div style="width: 4px; height: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2px; margin-right: 0.75rem;"></div>
                    <h6 class="mb-0 fw-semibold text-dark">Current Avatar</h6>
                </div>
                <div class="text-center">
                    <div style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #e5e7eb; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); font-size: 2.5rem; color: #6b7280; margin: 0 auto 1rem; position: relative; overflow: hidden;">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <?= strtoupper(substr($user['first_name'] ?? $user['email'] ?? 'U', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted small mb-0" style="font-size: 0.875rem;">
                        <i class="fas fa-camera me-1"></i>
                        Choose a new image above to update
                    </p>
                </div>
            </div>
            
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
            return response.text();
        })
        .then(data => {
            // Check if response is JSON
            try {
                const jsonData = JSON.parse(data);
                if (jsonData.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: jsonData.message || 'Profile updated successfully!',
                        confirmButtonColor: '#667eea',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = '<?= base_url('profile') ?>';
                    });
                } else {
                    throw new Error(jsonData.message || 'Update failed');
                }
            } catch (e) {
                // If not JSON, check if it's a redirect (success)
                if (data.includes('profile') || data.includes('success')) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Profile updated successfully!',
                        confirmButtonColor: '#667eea',
                        timer: 2000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.href = '<?= base_url('profile') ?>';
                    });
                } else {
                    throw new Error('Unexpected response format');
                }
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
    
    // File input validation
    const avatarInput = document.getElementById('avatar');
    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Too Large',
                    text: 'Please select an image smaller than 5MB.',
                    confirmButtonColor: '#667eea'
                });
                this.value = '';
                return;
            }
            
            // Check file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid File Type',
                    text: 'Please select a valid image file (JPEG, PNG, GIF, or WebP).',
                    confirmButtonColor: '#667eea'
                });
                this.value = '';
                return;
            }
        }
    });
    
    // Bio character counter
    const bioTextarea = document.getElementById('bio');
    const maxLength = 500;
    
    bioTextarea.addEventListener('input', function() {
        const remaining = maxLength - this.value.length;
        const helpText = this.nextElementSibling;
        
        if (remaining < 50) {
            helpText.innerHTML = `<i class="fas fa-info-circle me-1"></i>Characters remaining: ${remaining}`;
            helpText.className = remaining < 0 ? 'form-text text-danger' : 'form-text text-warning';
        } else {
            helpText.innerHTML = '<i class="fas fa-info-circle me-1"></i>Maximum 500 characters';
            helpText.className = 'form-text';
        }
    });
});
</script>


