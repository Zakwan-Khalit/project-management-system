<!-- Login Page Content - Redesigned -->
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #fff; font-family: 'Roboto', sans-serif;">
    <div style="display: flex; flex-wrap: wrap; width: 100%; max-width: 1100px; min-height: 600px; box-shadow: 0 20px 60px rgba(0,0,0,0.10); border-radius: 2rem; overflow: hidden; background: #fff;">
        <!-- Left: Welcome & Form -->
        <div style="flex: 1 1 400px; min-width: 350px; background: #fff; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 48px 32px 48px 48px;">
            <div style="width: 100%; max-width: 350px;">
                <div style="width: 100vw; min-width: 350px; display: flex; align-items: center; justify-content: flex-start; margin-bottom: 1.2rem; gap: 1.2rem; position: relative; left: -48px;">
                    <span style="font-family: 'Poppins', sans-serif; font-size: 2.0rem; color: #222; font-weight: 400; letter-spacing: -1px; white-space: nowrap;">Welcome to</span>
                    <img src="<?= base_url('assets/images/kertask_login.jpeg') ?>" alt="kertask logo" style="height: 64px; width: auto; display: inline-block; margin: 0; vertical-align: middle; white-space: nowrap;">
                </div>
                <div style="width: 100%; display: flex; justify-content: center; align-items: center; margin-bottom: 2.2rem; margin-top: 0.2rem; font-family: 'Roboto', sans-serif;">
                    <span style="font-size: 1.13rem; color:#222; text-align: center;">Simplify tracking and amplify results</span>
                </div>
                <form id="loginForm" method="post" novalidate style="margin-top: 1.5rem;">
                    <div style="margin-bottom: 22px;">
                        <label style="display: block; margin-bottom: 7px; color: #222; font-weight: 500; font-size: 0.97rem;">
                            <i class="fas fa-envelope" style="margin-right: 8px; color: #667eea;"></i>
                            Email Address
                        </label>
                        <input type="email" name="email" required 
                            style="width: 100%; padding: 13px 15px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1.07rem; transition: all 0.3s ease; outline: none; background: #f8fafc;"
                            placeholder="Enter your email"
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.10)'"
                            onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                    </div>
                    <div style="margin-bottom: 22px;">
                        <label style="display: block; margin-bottom: 7px; color: #222; font-weight: 500; font-size: 0.97rem;">
                            <i class="fas fa-lock" style="margin-right: 8px; color: #667eea;"></i>
                            Password
                        </label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="password" required 
                                style="width: 100%; padding: 13px 45px 13px 15px; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1.07rem; transition: all 0.3s ease; outline: none; background: #f8fafc;"
                                placeholder="Enter your password"
                                onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102,126,234,0.10)'"
                                onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                            <button type="button" onclick="togglePassword()" 
                                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #667eea; cursor: pointer; font-size: 1.1rem;">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
                        <label style="display: flex; align-items: center; cursor: pointer; font-size: 0.97rem; color: #6c757d;">
                            <input type="checkbox" name="remember" style="margin-right: 8px;">
                            Remember me
                        </label>
                        <a href="#" style="color: #667eea; text-decoration: none; font-size: 0.97rem; font-weight: 500;"
                            onmouseover="this.style.textDecoration='underline'"
                            onmouseout="this.style.textDecoration='none'">
                            Forgot Password?
                        </a>
                    </div>
                    <button type="submit" 
                        style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 15px; border-radius: 10px; font-size: 1.13rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-bottom: 10px; box-shadow: 0 4px 16px rgba(102,126,234,0.10);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102,126,234,0.13)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(102,126,234,0.10)'">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        Sign In
                    </button>
                </form>
            </div>
        </div>
        <!-- Right: Illustration -->
        <div style="flex: 1 1 400px; min-width: 350px; background: #181c2a; display: flex; align-items: center; justify-content: center; position: relative; padding: 0;">
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                <img src="<?= base_url('assets/images/login_banner.jpeg') ?>" style="width: 100%; height: 100%; border-radius: 1.2rem; box-shadow: 0 8px 32px rgba(102,126,234,0.13); background: #181c2a;">
            </div>
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
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            console.log('=== Login Form Submitted ===');
            console.log('Form submitted via jQuery - AJAX mode');
            console.log('Event prevented:', e.isDefaultPrevented());
            
            const $form = $(this);
            const $submitButton = $form.find('button[type="submit"]');
            const originalText = $submitButton.html();
            
            // Get form data
            const formData = {
                email: $form.find('input[name="email"]').val(),
                password: $form.find('input[name="password"]').val(),
                remember: $form.find('input[name="remember"]').is(':checked')
            };
            
            console.log('Form data:', formData);
            
            // Basic validation
            if (!formData.email || !formData.password) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please enter both email and password.',
                        confirmButtonColor: '#667eea'
                    });
                } else {
                    alert('Please enter both email and password.');
                }
                return false;
            }
            
            // Show loading state
            $submitButton.html('<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Signing In...');
            $submitButton.prop('disabled', true);
            
            const loginUrl = '<?= base_url('login') ?>';
            console.log('Making AJAX request to:', loginUrl);
            
            $.ajax({
                url: loginUrl,
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                timeout: 15000, // 15 second timeout
                success: function(data) {
                    console.log('=== AJAX Success Response ===');
                    console.log('Raw response:', data);
                    console.log('Response type:', typeof data);
                    console.log('Success property:', data.success);
                    console.log('Message:', data.message);
                    console.log('Redirect:', data.redirect);
                    
                    if (data && data.success === true) {
                        console.log('Login successful, handling redirect...');
                        
                        // Prevent any further form submissions
                        $form.off('submit');
                        $submitButton.prop('disabled', true);
                        
                        if (typeof Swal !== 'undefined') {
                            console.log('Using SweetAlert2 for success message...');
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful!',
                                text: 'Welcome back! Redirecting to dashboard...',
                                confirmButtonText: 'Continue',
                                confirmButtonColor: '#667eea',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then((result) => {
                                console.log('SweetAlert completed, redirecting...');
                                const redirectUrl = data.redirect || '<?= base_url('dashboard') ?>';
                                console.log('Redirect URL:', redirectUrl);
                                window.location.href = redirectUrl;
                            });
                        } else {
                            console.log('SweetAlert2 not available, using immediate redirect...');
                            const redirectUrl = data.redirect || '<?= base_url('dashboard') ?>';
                            console.log('Redirect URL:', redirectUrl);
                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 500);
                        }
                    } else {
                        console.log('Login failed:', data.message || 'Unknown error');
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: data.message || 'Invalid credentials. Please check your email and password.',
                                confirmButtonColor: '#667eea'
                            });
                        } else {
                            alert('Login Failed: ' + (data.message || 'Invalid credentials'));
                        }
                        
                        // Reset button
                        $submitButton.html(originalText);
                        $submitButton.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('=== AJAX Error ===');
                    console.error('XHR Status:', xhr.status);
                    console.error('Status Text:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Ready State:', xhr.readyState);
                    
                    // Try to parse response as JSON in case server returned JSON error
                    let errorMessage = 'Unable to connect to server. Please check your connection and try again.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.log('Response is not JSON:', xhr.responseText);
                    }
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: errorMessage,
                            confirmButtonColor: '#667eea'
                        });
                    } else {
                        alert('Error: ' + errorMessage);
                    }
                    
                    // Reset button
                    $submitButton.html(originalText);
                    $submitButton.prop('disabled', false);
                }
            });
            
            // Absolutely prevent any form submission
            return false;
        });
        
        console.log('✓ AJAX form handler attached successfully');
    } else {
        console.error('✗ jQuery not available! Form will use standard submission');
        
        // Fallback for when jQuery is not available
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            console.log('Using fallback form submission (no AJAX)');
            // Let the form submit normally to the server
        });
    }
    
    console.log('=== Login Form Initialization Complete ===');
});
</script>
