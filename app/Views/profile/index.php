<!-- Profile Page -->

<div class="container-fluid">
    <!-- Profile Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; position: relative; overflow: hidden;">
        <div style="content: ''; position: absolute; top: 0; right: 0; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(50px, -50px);"></div>
        <div class="row align-items-center">
            <div class="col-auto">
                <div style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.2); font-size: 3rem; color: white; margin-bottom: 1rem;">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <?= strtoupper(substr($user['first_name'] ?? $user['email'] ?? 'U', 0, 1)) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col">
                <h1 class="h2 mb-2">
                    <?= esc($user['first_name'] . ' ' . $user['last_name']) ?: esc($user['email']) ?>
                </h1>
                <p class="mb-2 opacity-75">
                    <i class="fas fa-envelope me-2"></i>
                    <?= esc($user['email']) ?>
                </p>
                <?php if (!empty($user['phone'])): ?>
                    <p class="mb-2 opacity-75">
                        <i class="fas fa-phone me-2"></i>
                        <?= esc($user['phone']) ?>
                    </p>
                <?php endif; ?>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-calendar me-2"></i>
                    Member since <?= date('F Y', strtotime($user['created_at'])) ?>
                </p>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('profile/edit') ?>" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Statistics -->
        <div class="col-lg-8">
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 2rem;">
                <div class="row text-center">
                    <div style="text-align: center; padding: 1rem;" class="col-md-4">
                        <div style="font-size: 2rem; font-weight: 700; color: #667eea; margin-bottom: 0.5rem;">15</div>
                        <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Projects</div>
                    </div>
                    <div style="text-align: center; padding: 1rem;" class="col-md-4">
                        <div style="font-size: 2rem; font-weight: 700; color: #667eea; margin-bottom: 0.5rem;">42</div>
                        <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Tasks Completed</div>
                    </div>
                    <div style="text-align: center; padding: 1rem;" class="col-md-4">
                        <div style="font-size: 2rem; font-weight: 700; color: #667eea; margin-bottom: 0.5rem;">98%</div>
                        <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Success Rate</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h5 class="mb-4">
                    <i class="fas fa-clock me-2"></i>
                    Recent Activity
                </h5>
                
                <div style="position: relative; padding-left: 2rem;">
                    <div style="content: ''; position: absolute; left: 0.75rem; top: 0; bottom: 0; width: 2px; background: #e5e7eb;"></div>
                    
                    <?php if (!empty($recentActivity)): ?>
                        <?php foreach ($recentActivity as $activity): ?>
                            <div style="position: relative; margin-bottom: 2rem; background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-left: 1rem;">
                                <div style="content: ''; position: absolute; left: -2.5rem; top: 1.5rem; width: 12px; height: 12px; background: #667eea; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 3px #e5e7eb;"></div>
                                <div style="color: #9ca3af; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">
                                    <?= date('M j, Y - H:i', strtotime($activity['created_at'])) ?>
                                </div>
                                <div>
                                    <h6 style="color: #374151; font-weight: 600; margin-bottom: 0.25rem;"><?= esc($activity['action']) ?></h6>
                                    <p style="color: #6b7280; margin: 0; font-size: 0.875rem;">
                                        <?= esc($activity['description'] ?? 'Activity performed') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-clock text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No recent activity to display</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-lg-4">
            <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); height: 100%;">
                <h5 class="mb-4">
                    <i class="fas fa-user me-2"></i>
                    Profile Information
                </h5>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">Email</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;"><?= esc($user['email']) ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">First Name</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;"><?= esc($user['first_name'] ?? 'Not set') ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">Last Name</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;"><?= esc($user['last_name'] ?? 'Not set') ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">Phone</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;"><?= esc($user['phone'] ?? 'Not set') ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">Role</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;"><?= esc($user['role'] ?? 'User') ?></span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">Status</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;">
                        <?= $user['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?>
                    </span>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0;">
                    <span style="color: #6b7280; font-weight: 500; font-size: 0.875rem;">Last Login</span>
                    <span style="color: #374151; font-weight: 600; font-size: 0.875rem;">
                        <?= $user['last_login'] ? date('M j, Y H:i', strtotime($user['last_login'])) : 'Never' ?>
                    </span>
                </div>

                <div class="mt-4 d-grid gap-2">
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>
                        Edit Profile
                    </a>
                    <a href="<?= base_url('profile/change-password') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-lock me-2"></i>
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


