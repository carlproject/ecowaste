<!DOCTYPE html>
<html>
<head>
    <title>Register - EcoByte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-image: url('app/views/ewaste.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4caf50;
            margin: 0;
            font-size: 28px;
        }

        .header p {
            color: #666;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            font-size: 16px;
        }

        input:focus {
            border-color: #4caf50;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .footer a {
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .requirements {
            font-size: 12px;
            color: #666;
            margin-top: 8px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .requirements ul {
            margin: 5px 0;
            padding-left: 20px;
        }

        .requirements li {
            margin: 3px 0;
        }

        .requirement-met {
            color: #28a745;
        }

        .requirement-not-met {
            color: #dc3545;
        }

        .captcha-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .captcha-image {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .refresh-captcha {
            color: #4caf50;
            text-decoration: none;
            font-size: 14px;
            margin-left: 10px;
            cursor: pointer;
        }

        .refresh-captcha:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-container {
                margin: 10px;
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="header">
            <h1>Join EcoByte</h1>
            <p>Create your account to start managing e-waste</p>
        </div>

        <?php 
        $alert = get_flash_alert();
        if ($alert): 
        ?>
            <div class="alert alert-<?php echo $alert['type']; ?>">
                <?php echo $alert['message']; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo site_url('register'); ?>" id="registerForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Choose a username">
                <div class="requirements">3-20 characters long</div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required 
                       placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Create a password">
                <div class="requirements">
                    Password must contain:
                    <ul>
                        <li id="length" class="requirement-not-met">At least 6 characters</li>
                        <li id="uppercase" class="requirement-not-met">At least 1 uppercase letter</li>
                        <li id="number" class="requirement-not-met">At least 1 number</li>
                        <li id="special" class="requirement-not-met">At least 1 special character (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required 
                       placeholder="Enter your complete address">
                <div class="requirements">Minimum 5 characters</div>
            </div>

            <div class="captcha-container">
                <img src="<?php echo site_url('captcha'); ?>" alt="CAPTCHA" class="captcha-image" id="captchaImage">
                <a class="refresh-captcha" onclick="refreshCaptcha()">Refresh CAPTCHA</a>
                <input type="text" id="captcha" name="captcha" required 
                       placeholder="Enter the code above" style="margin-top: 10px;">
            </div>

            <button type="submit" id="submitBtn" disabled>Create Account</button>
        </form>

        <div class="footer">
            <p>Already have an account? <a href="<?php echo site_url(''); ?>">Sign in here</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('registerForm');

            const requirements = {
                length: str => str.length >= 6,
                uppercase: str => /[A-Z]/.test(str),
                number: str => /[0-9]/.test(str),
                special: str => /[!@#$%^&*(),.?":{}|<>]/.test(str)
            };

            function validatePassword(value) {
                let valid = true;
                Object.keys(requirements).forEach(req => {
                    const element = document.getElementById(req);
                    if (requirements[req](value)) {
                        element.className = 'requirement-met';
                        element.innerHTML = '✓ ' + element.innerHTML.replace('✓ ', '').replace('✗ ', '');
                    } else {
                        valid = false;
                        element.className = 'requirement-not-met';
                        element.innerHTML = '✗ ' + element.innerHTML.replace('✓ ', '').replace('✗ ', '');
                    }
                });
                return valid;
            }

            function validateForm() {
                const username = document.getElementById('username').value;
                const email = document.getElementById('email').value;
                const address = document.getElementById('address').value;
                const captcha = document.getElementById('captcha').value;
                const passwordValid = validatePassword(password.value);

                submitBtn.disabled = !(
                    username.length >= 3 &&
                    username.length <= 20 &&
                    email &&
                    passwordValid &&
                    address.length >= 5 &&
                    captcha.length > 0
                );
            }

            password.addEventListener('input', function() {
                validatePassword(this.value);
                validateForm();
            });

            ['username', 'email', 'address', 'captcha'].forEach(id => {
                document.getElementById(id).addEventListener('input', validateForm);
            });

            form.addEventListener('submit', function(e) {
                if (!validatePassword(password.value)) {
                    e.preventDefault();
                    alert('Please meet all password requirements.');
                }
            });
        });

        function refreshCaptcha() {
            document.getElementById('captchaImage').src = '<?php echo site_url('captcha'); ?>?' + new Date().getTime();
            document.getElementById('captcha').value = '';
            validateForm();
        }
    </script>
</body>
</html>
