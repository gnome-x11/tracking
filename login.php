<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="icon" type="png" href="assets/img/PLMUNLOGO.png">
  <title>PLMUN Access Control System</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    
    body {
      width: 100%;
      height: 100vh;
      overflow: hidden;
      background: #0a2e1a; /* Dark green background */
    }
    
    .login-container {
      display: flex;
      height: 100vh;
    }
    
    .login-left {
      flex: 1.5;
      background-image: url('assets/img/PLMUN.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
    
    .login-left::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(10, 46, 26, 0.85); /* Dark green overlay */
      z-index: 1;
    }
    
    .logo-container {
      position: relative;
      z-index: 2;
      text-align: center;
      padding: 20px;
      max-width: 80%;
    }
    
    .logo {
      width: 120px;
      height: 120px;
      margin: 0 auto 20px;
      background-image: url('assets/img/PLMUNLOGO.png');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
    }
    
    .logotitle {
      color: white;
      font-size: 2.2rem;
      font-weight: 600;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
      line-height: 1.3;
    }
    
    .login-right {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #0a2e1a, #1a5c2e); /* Dark green gradient */
    }
    
    .login-card {
      width: 100%;
      max-width: 400px;
      background: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      padding: 40px;
      margin: 20px;
      transform: translateY(0);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      backdrop-filter: blur(5px); /* Frosted glass effect */
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .login-header h2 {
      color: white;
      font-weight: 600;
      margin-bottom: 10px;
    }
    
    .login-header p {
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.9rem;
    }
    
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: white;
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 8px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }
    
    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }
    
    .form-control:focus {
      border-color: #4CAF50;
      box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
      outline: none;
      background-color: rgba(255, 255, 255, 0.2);
    }
    
    .input-icon {
      position: absolute;
      right: 15px;
      top: 38px;
      color: rgba(255, 255, 255, 0.7);
    }
    
    .btn-login {
      width: 100%;
      padding: 12px;
      background: #4CAF50; /* Green button */
      border: none;
      border-radius: 8px;
      color: white;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
    }
    
    .btn-login:hover {
      background: #45a049;
      transform: translateY(-2px);
    }
    
    .btn-login:active {
      transform: translateY(0);
    }
    
    .alert {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }
    
    .alert-danger {
      background: rgba(220, 53, 69, 0.2);
      color: #ff6b6b;
      border: 1px solid rgba(220, 53, 69, 0.3);
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
      .login-container {
        flex-direction: column;
      }
      
      .login-left, .login-right {
        flex: none;
        width: 100%;
        height: auto;
      }
      
      .login-left {
        padding: 40px 20px;
        height: 200px;
      }
      
      .login-right {
        padding: 40px 20px;
      }
      
      .logo {
        width: 80px;
        height: 80px;
      }
      
      .logotitle {
        font-size: 1.5rem;
      }
    }
    
    @media (max-width: 576px) {
      .login-card {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-left">
      <div class="logo-container">
        <div class="logo"></div>
        <div class="logotitle">WELCOME TO PLMUN<br>Access Control System</div>
      </div>
    </div>
    
    <div class="login-right">
      <div class="login-card">
        <div class="login-header">
          <h2>Sign In</h2>
          <p>Enter your credentials to access the system</p>
        </div>
        
        <form id="login-form">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
            <i class="fas fa-user input-icon"></i>
          </div>
          
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            <i class="fas fa-lock input-icon"></i>
          </div>
          
          <button type="submit" class="btn-login">Login</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function() {
    $('#login-form').submit(function(e){
      e.preventDefault();
      var btn = $(this).find('button[type="submit"]');
      btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Logging in...');
      
      if($(this).find('.alert-danger').length > 0) {
        $(this).find('.alert-danger').remove();
      }
      
      $.ajax({
        url: 'ajax.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        error: function(err) {
          console.log(err);
          btn.prop('disabled', false).html('Login');
        },
        success: function(resp) {
          if(resp == 1) {
            location.href = 'index.php';
          } else if(resp == 2) {
            location.href = 'staff_dashboard.php';
          } else {
            $('#login-form').prepend(
              '<div class="alert alert-danger">' +
              '<i class="fas fa-exclamation-circle"></i> Username or password is incorrect.' +
              '</div>'
            );
            btn.prop('disabled', false).html('Login');
            
            // Add shake animation to form
            $('.login-card').css('animation', 'shake 0.5s');
            setTimeout(function() {
              $('.login-card').css('animation', '');
            }, 500);
          }
        }
      });
    });
    
    // Add focus effects
    $('.form-control').focus(function() {
      $(this).parent().find('label').css('color', '#4CAF50');
    }).blur(function() {
      $(this).parent().find('label').css('color', 'white');
    });
  });
  </script>
  
  <style>
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-5px); }
      40%, 80% { transform: translateX(5px); }
    }
  </style>
</body>
</html>