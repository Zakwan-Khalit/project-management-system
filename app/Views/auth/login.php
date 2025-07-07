<!-- Login Page Content -->
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Roboto', sans-serif; padding: 20px;">
    
    <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; width: 100%; max-width: 400px; margin: 20px;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 30px 30px 30px; text-align: center;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                <i class="fas fa-user-circle" style="font-size: 2.5rem;"></i>
            </div>
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin: 0; font-size: 1.8rem;">Welcome Back</h2>
            <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 0.95rem;">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <div style="padding: 40px 30px;">
            <form id="loginForm" method="post" action="#" novalidate>
                <!-- Email Input -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 0.9rem;">
                        <i class="fas fa-envelope" style="margin-right: 8px; color: #667eea;"></i>
                        Email Address
                    </label>
                    <input type="email" name="email" required 
                           style="width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; outline: none;"
                           placeholder="Enter your email"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                           onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                </div>

                <!-- Password Input -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500; font-size: 0.9rem;">
                        <i class="fas fa-lock" style="margin-right: 8px; color: #667eea;"></i>
                        Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" required 
                               style="width: 100%; padding: 12px 45px 12px 15px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; outline: none;"
                               placeholder="Enter your password"
                               onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.1)'"
                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                        <button type="button" onclick="togglePassword()" 
                                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6c757d; cursor: pointer; font-size: 1rem;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
                    <label style="display: flex; align-items: center; cursor: pointer; font-size: 0.9rem; color: #6c757d;">
                        <input type="checkbox" name="remember" style="margin-right: 8px;">
                        Remember me
                    </label>
                    <a href="#" style="color: #667eea; text-decoration: none; font-size: 0.9rem; font-weight: 500;"
                       onmouseover="this.style.textDecoration='underline'"
                       onmouseout="this.style.textDecoration='none'">
                        Forgot Password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 14px; border-radius: 10px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.3)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Sign In
                </button>

                <!-- Register Link -->
                <div style="text-align: center;">
                    <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">
                        Don't have an account? 
                        <a href="<?= base_url('register') ?>" style="color: #667eea; text-decoration: none; font-weight: 600;"
                           onmouseover="this.style.textDecoration='underline'"
                           onmouseout="this.style.textDecoration='none'">
                            Sign up here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
console.log('Login script loaded');

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Use window.onload to ensure all scripts are loaded
window.addEventListener('load', function() {
    console.log('=== Login Form Initialization ===');
    console.log('Window loaded, checking libraries...');
    console.log('jQuery:', typeof $ !== 'undefined' ? '✓ Available (v' + $.fn.jquery + ')' : '✗ Not available');
    console.log('SweetAlert2:', typeof Swal !== 'undefined' ? '✓ Available' : '✗ Not available');
    
    if (typeof $ !== 'undefined') {
        console.log('Setting up AJAX login form...');
        
        $('#loginForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('=== Login Form Submitted ===');
            console.log('Form submitted via jQuery - AJAX mode');
            
            const $form = $(this);
            const $submitButton = $form.find('button[type="submit"]');
            const originalText = $submitButton.html();
            
            // Show loading state
            $submitButton.html('<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Signing In...');
            $submitButton.prop('disabled', true);
            
            const loginUrl = '<?= base_url('login') ?>';
            console.log('Making AJAX request to:', loginUrl);
            console.log('Form data:', $form.serialize());
            
            $.ajax({
                url: loginUrl,
                type: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                timeout: 10000, // 10 second timeout
                success: function(data) {
                    console.log('=== AJAX Success Response ===');
                    console.log('Raw response:', data);
                    console.log('Response type:', typeof data);
                    console.log('Success property:', data.success);
                    console.log('Message:', data.message);
                    console.log('Redirect:', data.redirect);
                    
                    if (data.success) {
                        console.log('Login successful, handling redirect...');
                        
                        if (typeof Swal !== 'undefined') {
                            console.log('Using SweetAlert2 for success message...');
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful!',
                                text: 'Welcome back! You will be redirected to the dashboard.',
                                confirmButtonText: 'Continue',
                                confirmButtonColor: '#667eea',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                timer: 3000,
                                timerProgressBar: true
                            }).then((result) => {
                                console.log('SweetAlert result:', result);
                                console.log('Redirecting to dashboard...');
                                const redirectUrl = '<?= base_url('dashboard') ?>';
                                console.log('Redirect URL:', redirectUrl);
                                window.location.href = redirectUrl;
                            });
                        } else {
                            console.log('SweetAlert2 not available, using alert and immediate redirect...');
                            alert('Login successful! Redirecting to dashboard...');
                            const redirectUrl = '<?= base_url('dashboard') ?>';
                            console.log('Redirect URL:', redirectUrl);
                            window.location.href = redirectUrl;
                        }
                    } else {
                        console.log('Login failed:', data.message);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: data.message || 'Invalid credentials',
                                confirmButtonColor: '#667eea'
                            });
                        } else {
                            alert('Login Failed: ' + (data.message || 'Invalid credentials'));
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== AJAX Error ===');
                    console.error('XHR Status:', xhr.status);
                    console.error('Status Text:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Ready State:', xhr.readyState);
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'Unable to connect to server. Please check your connection and try again.',
                            confirmButtonColor: '#667eea'
                        });
                    } else {
                        alert('Connection error. Please try again.');
                    }
                },
                complete: function() {
                    console.log('AJAX request completed, resetting button...');
                    // Reset button
                    $submitButton.html(originalText);
                    $submitButton.prop('disabled', false);
                }
            });
            
            return false;
        });
        
        console.log('✓ AJAX form handler attached successfully');
    } else {
        console.error('✗ jQuery not available! Form will use standard submission');
        
        // Fallback for when jQuery is not available
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            console.log('Using fallback form submission (no AJAX)');
            // Let the form submit normally
        });
    }
    
    console.log('=== Login Form Initialization Complete ===');
});
</script>
