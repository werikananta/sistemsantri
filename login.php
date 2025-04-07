<?php
session_start();

// Cek jika user sudah login, redirect ke index.php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}

// Proses login jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Kredensial valid (username: Operator, password: Operator123)
    if ($username === 'Operator' && $password === 'Operator123') {
        // Set session
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        // Redirect ke halaman profil
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Santri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-subtitle {
            font-size: 0.875rem;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 25px;
        }
        
        .form-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            width: 100%;
            transition: all 0.2s;
            margin-bottom: 15px;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        
        .btn-login {
            background-color: #3b82f6;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.2s;
            width: 100%;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn-login:hover {
            background-color: #2563eb;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: #3b82f6;
            text-decoration: none;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .divider::before {
            margin-right: 15px;
        }
        
        .divider::after {
            margin-left: 15px;
        }
        
        .register-link {
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
        }
        
        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ef4444;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            z-index: 1000;
            transform: translateX(150%);
            transition: transform 0.3s ease;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast i {
            margin-right: 8px;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }
        
        .input-icon input {
            padding-left: 45px;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Header -->
        <div class="login-header">
            <h1 class="login-title">
                <i class="fas fa-user-graduate mr-2"></i> Data Santri v1.0
            </h1>
            <p class="login-subtitle">Login untuk mengakses Dashboard</p>
        </div>
        
        <!-- Login Body -->
        <div class="login-body">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST">
                <!-- Username Input -->
                <div class="input-icon mb-4">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Username" class="form-input" required>
                </div>
                
                <!-- Password Input -->
                <div class="input-icon mb-1">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" class="form-input" required>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
                
                <!-- Divider -->
                <div class="divider">Gunakan kredensial yang diberikan</div>
                
                <!-- Login Info -->
                <div class="register-link">
                    Username: <strong>Operator</strong> | Password: <strong>Operator123</strong>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Login berhasil!</span>
    </div>

    <script>
        // Show toast notification
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            if (isError) {
                toast.classList.add('error');
            } else {
                toast.classList.remove('error');
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Auto show toast if there's error from PHP
        <?php if (isset($error)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('<?php echo addslashes($error); ?>', true);
            });
        <?php endif; ?>
    </script>
</body>
</html>