<!-- Change Password Page -->

<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                </a>
                <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
            </li>
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('profile') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-user" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                    Profile
                </a>
                <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #ffffff; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem; background: rgba(255,255,255,0.1); border-radius: 0.2rem;">
                <i class="fas fa-lock" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Change Password
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Password Change Form -->
            <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <form id="passwordForm" action="<?= base_url('profile/update-password') ?>" method="post">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye" id="new_password_icon"></i>
                            </button>
                        </div>
                        <div class="form-text">Password must be at least 6 characters long</div>
                        <div id="password_requirements" class="mt-2">
                            <small class="text-muted">Password requirements:</small>
                            <div class="mt-1">
                                <small id="req_length" class="d-block text-muted">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    At least 6 characters
                                </small>
                                <small id="req_lowercase" class="d-block text-muted">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    At least one lowercase letter
                                </small>
                                <small id="req_uppercase" class="d-block text-muted">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    At least one uppercase letter
                                </small>
                                <small id="req_number" class="d-block text-muted">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    At least one number
                                </small>
                                <small id="req_special" class="d-block text-muted">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                    At least one special character
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye" id="confirm_password_icon"></i>
                            </button>
                        </div>
                        <div id="password_match_feedback" class="form-text" style="display: none;">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Passwords match
                        </div>
                        <div id="password_mismatch_feedback" class="form-text text-danger" style="display: none;">
                            <i class="fas fa-times-circle me-1"></i>
                            Passwords do not match
                        </div>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mb-3">
                        <label class="form-label">Password Strength</label>
                        <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                            <div id="passwordStrength" style="height: 100%; width: 0%; transition: all 0.3s ease; border-radius: 3px;"></div>
                        </div>
                        <small id="passwordStrengthText" class="text-muted">Enter a password to see strength</small>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Password
                        </button>
                        <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordStrengthText');
    
    // Check individual requirements
    const requirements = {
        length: password.length >= 6,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };
    
    // Update requirement indicators
    updateRequirementIndicator('req_length', requirements.length);
    updateRequirementIndicator('req_lowercase', requirements.lowercase);
    updateRequirementIndicator('req_uppercase', requirements.uppercase);
    updateRequirementIndicator('req_number', requirements.number);
    updateRequirementIndicator('req_special', requirements.special);
    
    // Calculate strength
    let strength = 0;
    Object.values(requirements).forEach(met => {
        if (met) strength += 1;
    });
    
    // Add extra point for longer passwords
    if (password.length >= 8) strength += 1;
    
    let strengthLabel = '';
    let strengthColor = '';
    
    switch (strength) {
        case 0:
        case 1:
            strengthLabel = 'Very Weak';
            strengthColor = '#ef4444';
            break;
        case 2:
            strengthLabel = 'Weak';
            strengthColor = '#f59e0b';
            break;
        case 3:
        case 4:
            strengthLabel = 'Medium';
            strengthColor = '#10b981';
            break;
        case 5:
        case 6:
            strengthLabel = 'Strong';
            strengthColor = '#059669';
            break;
    }
    
    const percentage = (strength / 6) * 100;
    strengthBar.style.width = percentage + '%';
    strengthBar.style.background = strengthColor;
    strengthText.textContent = strengthLabel;
    strengthText.style.color = strengthColor;
}

// Update requirement indicator
function updateRequirementIndicator(elementId, isMet) {
    const element = document.getElementById(elementId);
    const icon = element.querySelector('i');
    
    if (isMet) {
        element.classList.remove('text-muted');
        element.classList.add('text-success');
        icon.classList.remove('fa-circle');
        icon.classList.add('fa-check-circle');
    } else {
        element.classList.remove('text-success');
        element.classList.add('text-muted');
        icon.classList.remove('fa-check-circle');
        icon.classList.add('fa-circle');
    }
}

// Password matching checker
function checkPasswordMatch() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const confirmField = document.getElementById('confirm_password');
    const matchFeedback = document.getElementById('password_match_feedback');
    const mismatchFeedback = document.getElementById('password_mismatch_feedback');
    
    // Only check if both fields have values
    if (newPassword && confirmPassword) {
        if (newPassword === confirmPassword) {
            // Passwords match
            confirmField.classList.remove('is-invalid');
            confirmField.classList.add('is-valid');
            matchFeedback.style.display = 'block';
            mismatchFeedback.style.display = 'none';
            confirmField.setCustomValidity('');
        } else {
            // Passwords don't match
            confirmField.classList.remove('is-valid');
            confirmField.classList.add('is-invalid');
            matchFeedback.style.display = 'none';
            mismatchFeedback.style.display = 'block';
            confirmField.setCustomValidity('Passwords do not match');
        }
    } else {
        // Clear validation states if fields are empty
        confirmField.classList.remove('is-valid', 'is-invalid');
        matchFeedback.style.display = 'none';
        mismatchFeedback.style.display = 'none';
        confirmField.setCustomValidity('');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const newPasswordField = document.getElementById('new_password');
    const confirmPasswordField = document.getElementById('confirm_password');
    const form = document.getElementById('passwordForm');
    
    // Check password strength on input
    newPasswordField.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch(); // Also check password match when new password changes
    });
    
    // Validate password confirmation on input
    confirmPasswordField.addEventListener('input', function() {
        checkPasswordMatch();
    });
    
    // Also check on paste events
    newPasswordField.addEventListener('paste', function() {
        setTimeout(checkPasswordMatch, 10); // Small delay to allow paste to complete
    });
    
    confirmPasswordField.addEventListener('paste', function() {
        setTimeout(checkPasswordMatch, 10);
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (newPasswordField.value !== confirmPasswordField.value) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Passwords do not match',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
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
                    text: data.message,
                    confirmButtonColor: '#667eea'
                }).then(() => {
                    window.location.href = '<?= base_url('profile') ?>';
                });
            } else {
                let errorMessage = data.message || 'Password change failed';
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
                text: error.message || 'An unexpected error occurred',
                confirmButtonColor: '#667eea'
            });
        });
    });
});
</script>


