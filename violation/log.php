<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IPMS | Log in</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="log.css">
</head>
<body>

<div class="bg-container">
    <div class="overlay"></div>
    <div class="login-box">
        <div class="login-logo">
            <img src="cics.png" alt="SJC Logo">
            <h4></h4>
            <h1>Welcome </h1>
       
            <h2>CICS Student Violation Record Management System</h2>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"></p>

                <form>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" id="username" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="sign">Log in</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>

<script>
  $(document).ready(function(){
    $("#sign").click(function(){
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });

      var username = $("#username").val();
      var password = $("#password").val();

      $.ajax({
        url: 'Actions/login.php',
        data: {username: username, password: password},
        type: 'POST',
        success: function(data){
          if(data === 'A') {
            window.location.href = 'index.php';
          } else if(data === 'B') {
            window.location.href = 'coordinatorindex.php';
          } else if(data === 'C') {
            window.location.href = 'supervisorindex.php';
          } else if(data === 'S') {
            window.location.href = 'studentindex.php';
          } else {
            Toast.fire({
              icon: 'error',
              title: 'Wrong Credentials. Try Again'
            });
          }
        }
      });
    });
  });
</script>

</body>
</html>