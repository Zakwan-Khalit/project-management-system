<!-- Create User Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1.5rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 20px 60px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
    <!-- Decorative Elements -->
    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
    <div style="position: absolute; bottom: -30px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
        <div>
            <h1 style="color: white; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-user-plus" style="margin-right: 1rem; color: rgba(255,255,255,0.9);"></i>
                Add New User
            </h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 1.1rem; margin-bottom: 0; font-weight: 400;">
                Create a new user account in the system
            </p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button onclick="window.location.href='<?= base_url('users') ?>'" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 1rem; padding: 0.75rem 1.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i>
                Back to Users
            </button>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('errors')): ?>
    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: flex-start;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.75rem; color: #dc2626; margin-top: 0.25rem;"></i>
            <div style="flex: 1;">
                <h6 style="color: #991b1b; font-weight: 600; margin-bottom: 0.5rem;">Please fix the following errors:</h6>
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
    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; align-items: center;">
        <i class="fas fa-exclamation-triangle" style="margin-right: 0.75rem; color: #dc2626;"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Create User Form -->
<div style="display: flex; justify-content: center; margin-bottom: 2rem;">
    <div style="width: 100%; max-width: 800px;">
        <div style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0; padding: 1.5rem;">
                <h5 style="color: #374151; font-weight: 600; font-size: 1.1rem; margin: 0; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-user-edit" style="margin-right: 0.5rem; color: #667eea;"></i>
                    User Information
                </h5>
            </div>
            <div style="padding: 2rem;">
                <form action="<?= base_url('users/create') ?>" method="POST" id="userForm">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #374151; font-weight: 500;">
                                Full Name <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" 
                                   name="full_name" 
                                   value="<?= old('full_name') ?>"
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
                                   name="email" 
                                   value="<?= old('email') ?>"
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
                                   name="phone" 
                                   value="<?= old('phone') ?>"
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
                                       <?= old('is_active') ? 'checked' : 'checked' ?>
                                       style="width: 1.125rem; height: 1.125rem; margin-right: 0.75rem; accent-color: #667eea;">
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
                                                <?= old('department_id') == $dept['id'] ? 'selected' : '' ?>>
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
                                    disabled
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.875rem; transition: all 0.2s ease; outline: none; background: white;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                                <option value="">Select Position</option>
                            </select>
                        </div>
                    </div>

                    <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border: 1px solid #93c5fd; color: #1e40af; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start;">
                        <i class="fas fa-info-circle" style="margin-right: 0.75rem; color: #2563eb; margin-top: 0.125rem;"></i>
                        <div>
                            <strong>Password Information:</strong> The temporary password will be <strong>"123qwe"</strong>. The user will be required to change their password on first login.
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                        <a href="<?= base_url('users') ?>" 
                           style="padding: 0.75rem 1.5rem; border: 2px solid #6b7280; color: #6b7280; background: white; border-radius: 0.5rem; text-decoration: none; font-weight: 500; transition: all 0.2s ease; display: inline-flex; align-items: center;"
                           onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#374151'; this.style.color='#374151'"
                           onmouseout="this.style.background='white'; this.style.borderColor='#6b7280'; this.style.color='#6b7280'">
                            <i class="fas fa-times" style="margin-right: 0.5rem;"></i>
                            Cancel
                        </a>
                        <button type="submit" 
                                id="submitBtn"
                                style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center;"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102,126,234,0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fas fa-save" style="margin-right: 0.5rem;"></i>
                            Create User
                        </button>
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

    // Department change handler
    departmentSelect.on('change', function() {
        const departmentId = $(this).val();
        
        if (departmentId) {
            // Enable position select
            positionSelect.prop('disabled', false);
            
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
                            const selected = '<?= old('position_id') ?>' == position.id ? 'selected' : '';
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
            // Disable and clear position select
            positionSelect.prop('disabled', true).html('<option value="">Select Position</option>');
        }
    });

    // Form validation
    userForm.on('submit', function(e) {
        const fullName = $('#full_name').val().trim();
        const email = $('#email').val().trim();
        const departmentId = departmentSelect.val();
        const positionId = positionSelect.val();
        
        if (!fullName || !email || !departmentId || !positionId) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Creating...');
    });

    // Trigger department change if there's an old value
    <?php if (old('department_id')): ?>
        departmentSelect.trigger('change');
    <?php endif; ?>
});
</script>
