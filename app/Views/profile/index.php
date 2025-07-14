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
                    <?= esc(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) ?: esc($user['email']) ?>
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
                    Member since <?= !empty($user['created_at']) ? date('F Y', strtotime($user['created_at'])) : 'N/A' ?>
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
                        <div style="font-size: 2rem; font-weight: 700; color: #667eea; margin-bottom: 0.5rem;">
                            <?= isset($userStats['project_count']) ? (int)$userStats['project_count'] : 0 ?>
                        </div>
                        <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Projects</div>
                    </div>
                    <div style="text-align: center; padding: 1rem;" class="col-md-4">
                        <div style="font-size: 2rem; font-weight: 700; color: #667eea; margin-bottom: 0.5rem;">
                            <?= isset($userStats['task_count']) ? (int)$userStats['task_count'] : 0 ?>
                        </div>
                        <div style="color: #6b7280; font-weight: 500; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Tasks</div>
                    </div>
                    <div style="text-align: center; padding: 1rem;" class="col-md-4">
                        <div style="font-size: 2rem; font-weight: 700; color: #667eea; margin-bottom: 0.5rem;">
                            <?= (isset($userStats['project_count'], $userStats['task_count']) && ($userStats['project_count'] + $userStats['task_count']) > 0) ? round(($userStats['task_count'] / max(1, ($userStats['project_count'] + $userStats['task_count']))) * 100) : 0 ?>%
                        </div>
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
            <div style="backdrop-filter: blur(12px); background: rgba(255,255,255,0.55); border-radius: 1.5rem; box-shadow: 0 8px 32px rgba(102,126,234,0.18); padding: 2.5rem 1.5rem 2rem 1.5rem; height: 100%; position: relative; overflow: hidden; border: 1.5px solid rgba(102,126,234,0.10);">
                <div style="position: absolute; top: -40px; right: -40px; width: 120px; height: 120px; background: rgba(102,126,234,0.10); border-radius: 50%; z-index: 0;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 80px; height: 80px; background: rgba(102,126,234,0.06); border-radius: 50%; z-index: 0;"></div>
                <div style="display: flex; flex-direction: column; align-items: center; position: relative; z-index: 2; margin-bottom: 1.5rem;">
                    <div style="width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 16px rgba(102,126,234,0.15); display: flex; align-items: center; justify-content: center; margin-bottom: 0.75rem; border: 3px solid #fff; overflow: hidden;">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <span style="color: #fff; font-size: 2.5rem; font-weight: 700; letter-spacing: 1px;">
                                <?= strtoupper(substr($user['first_name'] ?? $user['email'] ?? 'U', 0, 1)) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div style="font-family: 'Poppins',sans-serif; font-weight: 700; color: #4f46e5; font-size: 1.25rem; text-align: center;">
                        <?= esc(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) ?: esc($user['email']) ?>
                    </div>
                    <div style="color: #6b7280; font-size: 0.95rem; font-weight: 500; text-align: center; margin-bottom: 0.5rem;">
                        <i class="fas fa-user-tag me-1"></i> <?= esc($user['role'] ?? 'User') ?>
                    </div>
                    <div style="width: 100%; margin: 0.5rem 0 0.5rem 0;">
                        <div style="height: 8px; background: #e0e7ff; border-radius: 4px; overflow: hidden;">
                            <?php 
                                $fieldsFilled = 0;
                                $fieldsTotal = 6;
                                if (!empty($user['first_name'])) $fieldsFilled++;
                                if (!empty($user['last_name'])) $fieldsFilled++;
                                if (!empty($user['phone'])) $fieldsFilled++;
                                if (!empty($user['avatar'])) $fieldsFilled++;
                                if (!empty($user['email'])) $fieldsFilled++;
                                if (!empty($user['last_login'])) $fieldsFilled++;
                                $profilePercent = round(($fieldsFilled / $fieldsTotal) * 100);
                            ?>
                            <div style="width: <?= $profilePercent ?>%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); height: 100%; transition: width 0.5s;"></div>
                        </div>
                        <div style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem; text-align: right;">Profile Completeness: <?= $profilePercent ?>%</div>
                    </div>
                </div>
                <h5 class="mb-4" style="font-family: 'Poppins',sans-serif; font-weight: 700; color: #4f46e5; position: relative; z-index: 1; text-align: center;">
                    <i class="fas fa-id-card me-2"></i>
                    Profile Information
                </h5>
                <ul class="list-group list-group-flush" style="background: transparent; position: relative; z-index: 1;">
                    <li class="list-group-item d-flex align-items-center" style="background: transparent; border: none; border-bottom: 1px solid #e5e7eb; padding: 0.85rem 0; gap: 0.75rem;">
                        <span style="color: #667eea; background: #e0e7ff; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"><i class="fas fa-envelope"></i></span>
                        <span style="color: #374151; font-weight: 600; font-size: 1rem;"> <?= esc($user['email']) ?> </span>
                    </li>
                    <li class="list-group-item d-flex align-items-center" style="background: transparent; border: none; border-bottom: 1px solid #e5e7eb; padding: 0.85rem 0; gap: 0.75rem;">
                        <span style="color: #667eea; background: #e0e7ff; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"><i class="fas fa-user"></i></span>
                        <span style="color: #374151; font-weight: 600; font-size: 1rem;"> <?= esc($user['first_name'] ?? 'Not set') ?> </span>
                    </li>
                    <li class="list-group-item d-flex align-items-center" style="background: transparent; border: none; border-bottom: 1px solid #e5e7eb; padding: 0.85rem 0; gap: 0.75rem;">
                        <span style="color: #667eea; background: #e0e7ff; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"><i class="fas fa-user"></i></span>
                        <span style="color: #374151; font-weight: 600; font-size: 1rem;"> <?= esc($user['last_name'] ?? 'Not set') ?> </span>
                    </li>
                    <li class="list-group-item d-flex align-items-center" style="background: transparent; border: none; border-bottom: 1px solid #e5e7eb; padding: 0.85rem 0; gap: 0.75rem;">
                        <span style="color: #667eea; background: #e0e7ff; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"><i class="fas fa-phone"></i></span>
                        <span style="color: #374151; font-weight: 600; font-size: 1rem;"> <?= esc($user['phone'] ?? 'Not set') ?> </span>
                    </li>
                    <li class="list-group-item d-flex align-items-center" style="background: transparent; border: none; border-bottom: 1px solid #e5e7eb; padding: 0.85rem 0; gap: 0.75rem;">
                        <span style="color: #667eea; background: #e0e7ff; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"><i class="fas fa-toggle-on"></i></span>
                        <span style="color: #374151; font-weight: 600; font-size: 1rem;">
                            <?= $user['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex align-items-center" style="background: transparent; border: none; padding: 0.85rem 0; gap: 0.75rem;">
                        <span style="color: #667eea; background: #e0e7ff; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"><i class="fas fa-clock"></i></span>
                        <span style="color: #374151; font-weight: 600; font-size: 1rem;">
                            <?= !empty($user['last_login']) ? date('M j, Y H:i', strtotime($user['last_login'])) : 'Never' ?>
                        </span>
                    </li>
                </ul>
                <div class="mt-4 d-grid gap-2">
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary" style="font-weight:600; border-radius:0.75rem;">
                        <i class="fas fa-edit me-2"></i>
                        Edit Profile
                    </a>
                    <a href="<?= base_url('profile/change-password') ?>" class="btn btn-outline-secondary" style="font-weight:600; border-radius:0.75rem;">
                        <i class="fas fa-lock me-2"></i>
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


