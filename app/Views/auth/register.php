<div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Roboto', sans-serif; padding: 20px;">
    
    <div style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; width: 100%; max-width: 450px; margin: 20px;">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px 30px 25px 30px; text-align: center;">
            <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i class="fas fa-user-plus" style="font-size: 2rem;"></i>
            </div>
            <h2 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin: 0; font-size: 1.6rem;">Create Account</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 0.9rem;">Join our project management platform</p>
        </div>

        <!-- Register Form -->
        <div style="padding: 30px;">
            <form id="registerForm">
                <!-- Name Fields -->
                <div style="display: flex; gap: 15px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 6px; color: #333; font-weight: 500; font-size: 0.85rem;">
                            <i class="fas fa-user" style="margin-right: 6px; color: #28a745;"></i>
                            First Name
                        </label>
                        <input type="text" name="first_name" required 
                               style="width: 100%; padding: 10px 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; outline: none;"
                               placeholder="First name"
                               onfocus="this.style.borderColor='#28a745'; this.style.boxShadow='0 0 0 3px rgba(40,167,69,0.1)'"
                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 6px; color: #333; font-weight: 500; font-size: 0.85rem;">
                            Last Name
                        </label>
                        <input type="text" name="last_name" required 
                               style="width: 100%; padding: 10px 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; outline: none;"
                               placeholder="Last name"
                               onfocus="this.style.borderColor='#28a745'; this.style.boxShadow='0 0 0 3px rgba(40,167,69,0.1)'"
                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                    </div>
                </div>

                <!-- Email -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; color: #333; font-weight: 500; font-size: 0.85rem;">
                        <i class="fas fa-envelope" style="margin-right: 6px; color: #28a745;"></i>
                        Email Address
                    </label>
                    <input type="email" name="email" required 
                           style="width: 100%; padding: 10px 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; outline: none;"
                           placeholder="your.email@example.com"
                           onfocus="this.style.borderColor='#28a745'; this.style.boxShadow='0 0 0 3px rgba(40,167,69,0.1)'"
                           onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                </div>

                <!-- Password -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; color: #333; font-weight: 500; font-size: 0.85rem;">
                        <i class="fas fa-lock" style="margin-right: 6px; color: #28a745;"></i>
                        Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" required 
                               style="width: 100%; padding: 10px 40px 10px 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; outline: none;"
                               placeholder="Create a strong password"
                               onfocus="this.style.borderColor='#28a745'; this.style.boxShadow='0 0 0 3px rgba(40,167,69,0.1)'"
                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'"
                               onkeyup="checkPasswordStrength(this.value)">
                        <button type="button" onclick="togglePassword()" 
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6c757d; cursor: pointer; font-size: 0.9rem;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div id="passwordStrength" style="margin-top: 5px; font-size: 0.8rem;"></div>
                </div>

                <!-- Confirm Password -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; color: #333; font-weight: 500; font-size: 0.85rem;">
                        <i class="fas fa-check-circle" style="margin-right: 6px; color: #28a745;"></i>
                        Confirm Password
                    </label>
                    <input type="password" name="confirm_password" id="confirmPassword" required 
                           style="width: 100%; padding: 10px 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; outline: none;"
                           placeholder="Confirm your password"
                           onfocus="this.style.borderColor='#28a745'; this.style.boxShadow='0 0 0 3px rgba(40,167,69,0.1)'"
                           onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'"
                           onkeyup="checkPasswordMatch()">
                    <div id="passwordMatch" style="margin-top: 5px; font-size: 0.8rem;"></div>
                </div>

                <!-- Terms and Conditions -->
                <div style="margin-bottom: 25px;">
                    <label style="display: flex; align-items: flex-start; cursor: pointer; font-size: 0.85rem; color: #6c757d; line-height: 1.4;">
                        <input type="checkbox" name="terms" required style="margin-right: 8px; margin-top: 2px;">
                        I agree to the 
                        <a href="#" style="color: #28a745; text-decoration: none; margin: 0 3px;"
                           onmouseover="this.style.textDecoration='underline'"
                           onmouseout="this.style.textDecoration='none'">Terms & Conditions</a>
                        and 
                        <a href="#" style="color: #28a745; text-decoration: none; margin-left: 3px;"
                           onmouseover="this.style.textDecoration='underline'"
                           onmouseout="this.style.textDecoration='none'">Privacy Policy</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        style="width: 100%; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 12px; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(40,167,69,0.3)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                    Create Account
                </button>

                <!-- Login Link -->
                <div style="text-align: center;">
                    <p style="color: #6c757d; margin: 0; font-size: 0.9rem;">
                        Already have an account? 
                        <a href="<?= base_url('login') ?>" style="color: #28a745; text-decoration: none; font-weight: 600;"
                           onmouseover="this.style.textDecoration='underline'"
                           onmouseout="this.style.textDecoration='none'">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
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

    // Check password strength
    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('passwordStrength');
        const patterns = {
            weak: /^.{1,5}$/,
            medium: /^(?=.*[a-z])(?=.*[A-Z])|(?=.*\d).{6,}$/,
            strong: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/
        };

        if (password.length === 0) {
            strengthDiv.innerHTML = '';
            return;
        }

        if (patterns.strong.test(password)) {
            strengthDiv.innerHTML = '<span style="color: #28a745;"><i class="fas fa-check-circle"></i> Strong password</span>';
        } else if (patterns.medium.test(password)) {
            strengthDiv.innerHTML = '<span style="color: #ffc107;"><i class="fas fa-exclamation-circle"></i> Medium strength</span>';
        } else {
            strengthDiv.innerHTML = '<span style="color: #dc3545;"><i class="fas fa-times-circle"></i> Weak password</span>';
        }
    }

    // Check password match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const matchDiv = document.getElementById('passwordMatch');

        if (confirmPassword.length === 0) {
            matchDiv.innerHTML = '';
            return;
        }

        if (password === confirmPassword) {
            matchDiv.innerHTML = '<span style="color: #28a745;"><i class="fas fa-check-circle"></i> Passwords match</span>';
        } else {
            matchDiv.innerHTML = '<span style="color: #dc3545;"><i class="fas fa-times-circle"></i> Passwords do not match</span>';
        }
    }

    // Handle form submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Passwords do not match. Please try again.'
            });
            return;
        }
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Creating Account...';
        submitButton.disabled = true;
        
        $.ajax({
            url: '<?= base_url('register') ?>',
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: data.message,
                        confirmButtonText: 'Go to Login'
                    }).then(() => {
                        window.location.href = data.redirect || '<?= base_url('login') ?>';
                    });
                } else {
                    let errorMessage = data.message || 'Registration failed';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).join(', ');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: errorMessage
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong. Please try again.'
                });
            },
            complete: function() {
                // Reset button
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    });
</script>
