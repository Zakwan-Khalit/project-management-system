<!-- Users Header -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 15px 45px rgba(102,126,234,0.2); position: relative; overflow: hidden;">
    <!-- Decorative Elements -->
    <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; opacity: 0.3;"></div>
    <div style="position: absolute; bottom: -20px; left: -20px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
    
    <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
        <div>
            <h1 style="color: white; font-size: 2rem; font-weight: 800; margin-bottom: 0.4rem; font-family: 'Poppins', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-users" style="margin-right: 0.75rem; color: rgba(255,255,255,0.9);"></i>
                User Management
            </h1>
            <p style="color: rgba(255,255,255,0.95); font-size: 0.95rem; margin-bottom: 0; font-weight: 400;">
                Manage system users, roles, and permissions
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <button onclick="window.location.href='<?= base_url('users/create') ?>'" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: white; border-radius: 0.75rem; padding: 0.6rem 1.25rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <i class="fas fa-plus" style="margin-right: 0.4rem;"></i>
                Add User
            </button>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: 1px solid #86efac; color: #065f46; padding: 0.75rem 1.25rem; border-radius: 0.6rem; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <i class="fas fa-check-circle" style="margin-right: 0.5rem; color: #059669;"></i>
            <span style="font-size: 0.9rem;"><?= session()->getFlashdata('success') ?></span>
        </div>
        <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #065f46; font-size: 1rem; cursor: pointer; padding: 0; margin-left: 0.75rem;">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem 1.25rem; border-radius: 0.6rem; margin-bottom: 1.25rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem; color: #dc2626;"></i>
            <span style="font-size: 0.9rem;"><?= session()->getFlashdata('error') ?></span>
        </div>
        <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #991b1b; font-size: 1rem; cursor: pointer; padding: 0; margin-left: 0.75rem;">
            <i class="fas fa-times"></i>
        </button>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 1px solid #fca5a5; color: #991b1b; padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; align-items: center;">
        <i class="fas fa-exclamation-triangle" style="margin-right: 0.75rem; color: #dc2626;"></i>
        <span><?= $error_message ?></span>
    </div>
<?php endif; ?>

<!-- Users Table -->
<div style="background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 2rem;">
    <div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0; padding: 1.5rem;">
        <h5 style="color: #374151; font-weight: 600; font-size: 1.1rem; margin: 0; font-family: 'Poppins', sans-serif;">
            <i class="fas fa-table" style="margin-right: 0.5rem; color: #667eea;"></i>
            All Users
        </h5>
    </div>
    <div style="padding: 0;">
        <?php if (!empty($users)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;" id="usersTable">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">#</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Full Name</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Email</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Phone</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Department</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Position</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Status</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border: none;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $index => $user): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 1rem; border: none; vertical-align: middle;"><?= $index + 1 ?></td>
                                <td style="padding: 1rem; border: none; vertical-align: middle;">
                                    <div style="display: flex; align-items: center;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; margin-right: 0.75rem;">
                                            <?= strtoupper(substr($user['full_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <span style="font-weight: 500; color: #374151;"><?= esc($user['full_name'] ?? 'N/A') ?></span>
                                    </div>
                                </td>
                                <td style="padding: 1rem; border: none; vertical-align: middle; color: #6b7280;"><?= esc($user['email']) ?></td>
                                <td style="padding: 1rem; border: none; vertical-align: middle; color: #6b7280;"><?= esc($user['phone'] ?? 'N/A') ?></td>
                                <td style="padding: 1rem; border: none; vertical-align: middle;">
                                    <span style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                        <?= esc($user['department_name'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; border: none; vertical-align: middle;">
                                    <span style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.025em;">
                                        <?= esc($user['position_name'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; border: none; vertical-align: middle;">
                                    <button class="status-toggle" data-user-id="<?= $user['id'] ?>" style="<?= $user['is_active'] ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%);' : 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);' ?> color: white; border: none; border-radius: 0.5rem; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; min-width: 85px;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';" title="Click to toggle status">
                                        <i class="fas <?= $user['is_active'] ? 'fa-check' : 'fa-times' ?>" style="margin-right: 0.25rem;"></i>
                                        <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                    </button>
                                </td>
                                <td style="padding: 1rem; border: none; vertical-align: middle;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="<?= base_url('users/edit/' . $user['id']) ?>" style="background: transparent; border: 2px solid #3b82f6; color: #3b82f6; border-radius: 0.375rem; padding: 0.5rem; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center;" onmouseover="this.style.background='#3b82f6'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#3b82f6';" title="Edit User">
                                            <i class="fas fa-edit" style="font-size: 0.875rem;"></i>
                                        </a>
                                        <button class="delete-user" data-user-id="<?= $user['id'] ?>" data-user-name="<?= esc($user['full_name']) ?>" style="background: transparent; border: 2px solid #ef4444; color: #ef4444; border-radius: 0.375rem; padding: 0.5rem; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#ef4444';" title="Delete User">
                                            <i class="fas fa-trash" style="font-size: 0.875rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem 2rem;">
                <i class="fas fa-users" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                <h5 style="color: #6b7280; font-weight: 600; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif;">No users found</h5>
                <p style="color: #9ca3af; margin-bottom: 1.5rem;">Start by adding your first user to the system.</p>
                <button onclick="window.location.href='<?= base_url('users/create') ?>'" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                    Add User
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#usersTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [6, 7] }
            ]
        });
    }

    // Status toggle functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.status-toggle')) {
            const button = e.target.closest('.status-toggle');
            const userId = button.getAttribute('data-user-id');
            
            if (button.disabled) return;
            
            button.disabled = true;
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.25rem;"></i>Loading...';
            
            fetch(`<?= base_url('users/toggleStatus') ?>/${userId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const isActive = data.new_status;
                    
                    if (isActive) {
                        button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                        button.innerHTML = '<i class="fas fa-check" style="margin-right: 0.25rem;"></i>Active';
                    } else {
                        button.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                        button.innerHTML = '<i class="fas fa-times" style="margin-right: 0.25rem;"></i>Inactive';
                    }
                    
                    showAlert('success', data.message);
                } else {
                    button.innerHTML = originalContent;
                    showAlert('error', data.error || 'Failed to update status');
                }
            })
            .catch(error => {
                button.innerHTML = originalContent;
                showAlert('error', 'Failed to update user status');
            })
            .finally(() => {
                button.disabled = false;
            });
        }
    });

    // Delete user functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-user')) {
            const button = e.target.closest('.delete-user');
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            if (confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
                window.location.href = `<?= base_url('users/delete') ?>/${userId}`;
            }
        }
    });
});

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    const isSuccess = type === 'success';
    
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        background: ${isSuccess ? 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)' : 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)'};
        border: 1px solid ${isSuccess ? '#86efac' : '#fca5a5'};
        color: ${isSuccess ? '#065f46' : '#991b1b'};
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    `;
    
    alertDiv.innerHTML = `
        <i class="fas ${isSuccess ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="margin-right: 0.75rem; color: ${isSuccess ? '#059669' : '#dc2626'};"></i>
        <span style="flex: 1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; color: ${isSuccess ? '#065f46' : '#991b1b'}; font-size: 1.25rem; cursor: pointer; padding: 0; margin-left: 1rem;">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-dismiss after 4 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 4000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
