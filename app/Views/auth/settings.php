<!-- Modern Settings Page -->
<div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; padding: 2rem; font-family: 'Roboto', sans-serif;">
    
    <!-- Settings Header -->
    <div style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-cog" style="font-size: 1.8rem;"></i>
            </div>
            <div>
                <h1 style="margin: 0; font-size: 2rem; font-weight: 700; font-family: 'Poppins', sans-serif;">Settings</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9; font-size: 1rem;">Manage your account preferences and application settings</p>
            </div>
        </div>
    </div>

    <!-- Settings Content -->
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            
            <!-- Profile Settings Card -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user"></i>
                        Profile Settings
                    </h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-info-circle" style="color: #2196f3; font-size: 1.2rem;"></i>
                            <h4 style="margin: 0; color: #1976d2; font-size: 1rem;">Coming Soon</h4>
                        </div>
                        <p style="margin: 0; color: #1565c0; line-height: 1.5;">
                            Profile settings including name, email, password change, and avatar upload will be available soon.
                        </p>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button style="flex: 1; background: #e9ecef; color: #6c757d; border: none; padding: 0.75rem 1rem; border-radius: 0.5rem; font-weight: 500; cursor: not-allowed;" disabled>
                            <i class="fas fa-edit" style="margin-right: 0.5rem;"></i>
                            Edit Profile
                        </button>
                        <button style="flex: 1; background: #e9ecef; color: #6c757d; border: none; padding: 0.75rem 1rem; border-radius: 0.5rem; font-weight: 500; cursor: not-allowed;" disabled>
                            <i class="fas fa-key" style="margin-right: 0.5rem;"></i>
                            Change Password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notification Settings Card -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-bell"></i>
                        Notifications
                    </h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="background: #d1fae5; border: 1px solid #10b981; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-info-circle" style="color: #10b981; font-size: 1.2rem;"></i>
                            <h4 style="margin: 0; color: #065f46; font-size: 1rem;">Coming Soon</h4>
                        </div>
                        <p style="margin: 0; color: #047857; line-height: 1.5;">
                            Email notifications, push notifications, and project alerts will be configurable here.
                        </p>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: not-allowed; opacity: 0.6;">
                            <input type="checkbox" disabled style="width: 18px; height: 18px; cursor: not-allowed;">
                            <span style="color: #374151; font-weight: 500;">Email Notifications</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: not-allowed; opacity: 0.6;">
                            <input type="checkbox" disabled style="width: 18px; height: 18px; cursor: not-allowed;">
                            <span style="color: #374151; font-weight: 500;">Task Reminders</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: not-allowed; opacity: 0.6;">
                            <input type="checkbox" disabled style="width: 18px; height: 18px; cursor: not-allowed;">
                            <span style="color: #374151; font-weight: 500;">Project Updates</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Theme Settings Card -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-palette"></i>
                        Appearance
                    </h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-info-circle" style="color: #f59e0b; font-size: 1.2rem;"></i>
                            <h4 style="margin: 0; color: #92400e; font-size: 1rem;">Coming Soon</h4>
                        </div>
                        <p style="margin: 0; color: #78350f; line-height: 1.5;">
                            Dark mode, theme customization, and layout preferences will be available soon.
                        </p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 0.75rem; padding: 1rem; text-align: center; cursor: not-allowed; opacity: 0.6;">
                            <i class="fas fa-sun" style="font-size: 1.5rem; color: #ffc107; margin-bottom: 0.5rem;"></i>
                            <p style="margin: 0; font-weight: 500; color: #495057;">Light Mode</p>
                        </div>
                        <div style="background: #343a40; border: 2px solid #6c757d; border-radius: 0.75rem; padding: 1rem; text-align: center; cursor: not-allowed; opacity: 0.6;">
                            <i class="fas fa-moon" style="font-size: 1.5rem; color: #6c757d; margin-bottom: 0.5rem;"></i>
                            <p style="margin: 0; font-weight: 500; color: #adb5bd;">Dark Mode</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Privacy & Security Card -->
            <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 1.5rem; border-bottom: 1px solid #e2e8f0;">
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-shield-alt"></i>
                        Privacy & Security
                    </h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="background: #fee2e2; border: 1px solid #ef4444; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <i class="fas fa-info-circle" style="color: #ef4444; font-size: 1.2rem;"></i>
                            <h4 style="margin: 0; color: #991b1b; font-size: 1rem;">Coming Soon</h4>
                        </div>
                        <p style="margin: 0; color: #7f1d1d; line-height: 1.5;">
                            Two-factor authentication, session management, and privacy controls will be available soon.
                        </p>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <button style="width: 100%; background: #e9ecef; color: #6c757d; border: none; padding: 0.75rem 1rem; border-radius: 0.5rem; font-weight: 500; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" disabled>
                            <i class="fas fa-mobile-alt"></i>
                            Enable 2FA
                        </button>
                        <button style="width: 100%; background: #e9ecef; color: #6c757d; border: none; padding: 0.75rem 1rem; border-radius: 0.5rem; font-weight: 500; cursor: not-allowed; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" disabled>
                            <i class="fas fa-history"></i>
                            Session History
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Back to Dashboard Button -->
        <div style="text-align: center; margin-top: 3rem;">
            <a href="<?= base_url('dashboard') ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(102,126,234,0.3);" 
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.4)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102,126,234,0.3)'">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
