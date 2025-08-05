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
            <a href="<?= base_url('users') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                <i class="fas fa-users" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                User Management
            </a>
            <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
        </li>
        <li style="color: #ffffff; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem; background: rgba(255,255,255,0.1); border-radius: 0.2rem;">
            <i class="fas fa-plus" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
            Edit User
        </li>
    </ol>
</nav>

<div style="padding: 1.5rem; min-height: 100vh;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Error Messages -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #f87171; color: #dc2626; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: flex-start;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 0.75rem; color: #ef4444; margin-top: 0.125rem;"></i>
                    <div>
                        <h6 style="margin: 0 0 0.5rem 0; color: #dc2626; font-weight: 600;">Please fix the following errors:</h6>
                        <ul style="margin: 0; padding-left: 1rem;">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #f87171; color: #dc2626; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start;">
                <i class="fas fa-exclamation-circle" style="margin-right: 0.75rem; color: #ef4444; margin-top: 0.125rem;"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div style="display: flex; justify-content: center;">
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2); width: 100%; max-width: 800px;">
                <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 1rem 1rem 0 0;">
                    <h5 style="margin: 0; color: #1e293b; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-user-cog" style="margin-right: 0.75rem; color: #667eea;"></i>
                        User Information
                    </h5>
                </div>
                <form id="userForm" style="padding: 2rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Full Name <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="<?= old('full_name', $user['full_name'] ?? '') ?>"
                                   required
                                   placeholder="Enter full name"
                                   style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s ease; outline: none;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Email Address <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= old('email', $user['email'] ?? '') ?>"
                                   required
                                   placeholder="Enter email address"
                                   style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s ease; outline: none;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Phone Number
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?= old('phone', $user['phone'] ?? '') ?>"
                                   placeholder="Enter phone number"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s ease; outline: none;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                                   onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Status
                            </label>
                            <div style="display: flex; align-items: center; padding: 0.75rem 0;">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       <?= old('is_active', $user['is_active'] ?? 0) ? 'checked' : '' ?>
                                       style="width: 1.25rem; height: 1.25rem; border: 2px solid #e5e7eb; border-radius: 0.25rem; margin-right: 0.75rem; accent-color: #667eea;">
                                <label for="is_active" style="color: #374151; font-weight: 500; cursor: pointer;">
                                    Active User
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Department <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="department_id" 
                                    id="department_id"
                                    required
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s ease; outline: none; background: white;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                <option value="">Select Department</option>
                                <?php if (!empty($departments)): ?>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" 
                                                <?= old('department_id', $user['department_id'] ?? '') == $dept['id'] ? 'selected' : '' ?>>
                                            <?= esc($dept['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Position <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="position_id" 
                                    id="position_id"
                                    required
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s ease; outline: none; background: white;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                <option value="">Select Position</option>
                                <?php if (!empty($positions)): ?>
                                    <?php foreach ($positions as $pos): ?>
                                        <option value="<?= $pos['id'] ?>" 
                                                <?= old('position_id', $user['position_id'] ?? '') == $pos['id'] ? 'selected' : '' ?>>
                                            <?= esc($pos['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                        <button type="submit" 
                                id="submitBtn"
                                style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center;"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102,126,234,0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                            Update User
                        </button>
                        <a href="<?= base_url('users') ?>" 
                           style="padding: 0.75rem 1.5rem; border: 2px solid #6b7280; color: #6b7280; background: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: all 0.2s ease; display: inline-flex; align-items: center;"
                           onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#374151'; this.style.color='#374151'"
                           onmouseout="this.style.background='white'; this.style.borderColor='#6b7280'; this.style.color='#6b7280'">
                            <i class="fas fa-times" style="margin-right: 0.5rem;"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const departmentSelect = $('#department_id');
    const positionSelect = $('#position_id');
    const userForm = $('#userForm');
    const submitBtn = $('#submitBtn');

    // Store current position ID for reselection
    const currentPositionId = '<?= old('position_id', $user['position_id'] ?? '') ?>';
    const userId = '<?= $user['id'] ?>';

    // Function to show SweetAlert notifications
    function showAlert(type, title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        } else {
            alert(title + ': ' + message);
        }
    }

    // Function to show validation errors
    function showValidationErrors(errors) {
        let errorList = '';
        if (Array.isArray(errors)) {
            errorList = errors.join('\n• ');
        } else if (typeof errors === 'object') {
            errorList = Object.values(errors).join('\n• ');
        } else {
            errorList = errors;
        }
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Validation Errors',
                html: '<div style="text-align: left;"> ' + errorList.replace(/\n/g, '<br> ') + '</div>',
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        } else {
            alert('Please fix the following errors:\n• ' + errorList);
        }
    }

    // Department change handler
    departmentSelect.on('change', function() {
        const departmentId = $(this).val();
        
        if (departmentId) {
            // Load positions for selected department
            positionSelect.html('<option value="">Loading...</option>');
            
            $.ajax({
                url: '<?= base_url('users/getPositionsByDepartment') ?>',
                method: 'POST',
                data: {
                    department_id: departmentId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response); // Debug log
                    let options = '<option value="">Select Position</option>';
                    
                    if (response.success && response.positions && response.positions.length > 0) {
                        response.positions.forEach(function(position) {
                            const selected = currentPositionId == position.id ? 'selected' : '';
                            options += `<option value="${position.id}" ${selected}>${position.name}</option>`;
                        });
                    } else {
                        options = '<option value="">No positions available</option>';
                    }
                    
                    positionSelect.html(options);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText); // Debug log
                    positionSelect.html('<option value="">Error loading positions</option>');
                }
            });
        } else {
            // Clear position select
            positionSelect.html('<option value="">Select Position</option>');
        }
    });

    // AJAX Form submission
    userForm.on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        const fullName = $('#full_name').val().trim();
        const email = $('#email').val().trim();
        const departmentId = departmentSelect.val();
        const positionId = positionSelect.val();
        
        // Client-side validation
        if (!fullName || !email || !departmentId || !positionId) {
            showAlert('error', 'Validation Error', 'Please fill in all required fields.');
            return false;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Updating...');
        
        // Prepare form data
        const formData = {
            full_name: fullName,
            email: email,
            phone: $('#phone').val().trim(),
            department_id: departmentId,
            position_id: positionId,
            is_active: $('#is_active').is(':checked') ? 1 : 0,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        };
        
        // Send AJAX request
        $.ajax({
            url: '<?= base_url('users/update/') ?>' + userId,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Update response:', response);
                
                if (response.success) {
                    showAlert('success', 'Success!', response.message || 'User updated successfully!');
                    
                    // Redirect to users list after a short delay
                    setTimeout(function() {
                        window.location.href = '<?= base_url('users') ?>';
                    }, 1500);
                } else {
                    // Show validation errors
                    if (response.errors) {
                        showValidationErrors(response.errors);
                    } else {
                        showAlert('error', 'Error', response.message || 'Failed to update user.');
                    }
                    
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).html('<i class="fas fa-save" style="margin-right: 0.5rem;"></i>Update User');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                
                let errorMessage = 'An error occurred while updating the user.';
                
                // Try to parse error response
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    } else if (errorResponse.errors) {
                        showValidationErrors(errorResponse.errors);
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save" style="margin-right: 0.5rem;"></i>Update User');
                        return;
                    }
                } catch (e) {
                    // Use default error message
                }
                
                showAlert('error', 'Error', errorMessage);
                
                // Re-enable submit button
                submitBtn.prop('disabled', false).html('<i class="fas fa-save" style="margin-right: 0.5rem;"></i>Update User');
            }
        });
    });

    // Load positions for current department if set
    const currentDepartmentId = departmentSelect.val();
    if (currentDepartmentId) {
        departmentSelect.trigger('change');
    }
});
</script>
