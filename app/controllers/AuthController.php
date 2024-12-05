<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->call->model('users_model');
        $this->call->library('session');
        $this->call->helper('url');
    }

    private function validate_password($password) {
        // Check minimum length
        if (strlen($password) < 6) {
            return false;
        }
        
        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // Check for at least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return false;
        }
        
        return true;
    }

    public function generate_captcha() {
        // Create a blank image
        $image = imagecreatetruecolor(120, 40);
        
        // Colors
        $bg = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, 120, 40, $bg);
        
        // Generate random string
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $captcha_string = '';
        for ($i = 0; $i < 6; $i++) {
            $captcha_string .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Save captcha text in session
        $_SESSION['captcha'] = $captcha_string;
        
        // Add noise
        for ($i = 0; $i < 100; $i++) {
            $x = rand(0, 120);
            $y = rand(0, 40);
            imagesetpixel($image, $x, $y, $text_color);
        }
        
        // Add the text
        imagettftext($image, 20, 0, 15, 30, $text_color, 'C:/Windows/Fonts/arial.ttf', $captcha_string);
        
        // Output the image
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit();
    }

    private function verify_captcha($input) {
        return isset($_SESSION['captcha']) && strtoupper($input) === $_SESSION['captcha'];
    }

    public function Login() {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            redirect('dashboard');
        }

        if ($this->form_validation->submitted()) {
            $this->form_validation->name('username')->required();
            $this->form_validation->name('password')->required();

            if ($this->form_validation->run()) {
                $username = $this->io->post('username');
                $password = $this->io->post('password');

                $user = $this->users_model->verify_login($username, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    redirect('dashboard');
                } else {
                    $this->call->view('login', ['error' => 'Invalid username or password.']);
                }
            } else {
                $errors = $this->form_validation->errors();
                $this->call->view('login', ['error' => $errors]);
            }
        } else {
            $this->call->view('login');
        }
    }

    public function register() {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            redirect('dashboard');
        }

        if($this->form_validation->submitted()) {
            // Verify CAPTCHA
            $captcha_input = $this->io->post('captcha');
            if (!$captcha_input || !$this->verify_captcha($captcha_input)) {
                set_flash_alert('danger', 'Invalid CAPTCHA code.');
                redirect('register');
                return;
            }

            $this->form_validation
                ->name('username')
                    ->required('Username is required!')->min_length(3)->max_length(20)
                ->name('email')
                    ->required('Email is required!')->valid_email()
                ->name('password')
                    ->required('Password is required!')
                ->name('address')
                    ->required('Address is required!')->min_length(5);

            if($this->form_validation->run()) {
                $username = $this->io->post('username');
                $email = $this->io->post('email');
                $password = $this->io->post('password');
                $address = $this->io->post('address');

                // Validate password complexity
                if (!$this->validate_password($password)) {
                    set_flash_alert('danger', 'Password must contain at least 6 characters, 1 uppercase letter, 1 number, and 1 special character.');
                    redirect('register');
                    return;
                }

                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                if ($this->users_model->create($username, $email, $hashed_password, $address)) {
                    set_flash_alert('success', 'Registration successful! Please login.');
                    redirect('');
                } else {
                    set_flash_alert('danger', 'Username or email already exists.');
                    redirect('register');
                }
            } else {
                set_flash_alert('danger', $this->form_validation->errors());
                redirect('register');
            }
        }
        $this->call->view('register');
    }

    public function logout() {
        // Destroy all session data
        session_destroy();
        // Redirect to login page
        redirect('');
    }
}
?>
