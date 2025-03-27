<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- FontAwesome 6.2.0 CSS -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    
   
    
    <!-- Bootstrap CSS -->
    <link href="../../admin/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../admin/assets/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="../../admin/assets/css/style.css" rel="stylesheet" />
    <link href="../../admin/assets/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- loader-->
    <link href="../../admin/assets/css/pace.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="style.css">
    <style>
    .btn-primary-login {
        background-color: #ff7f00;
        color: white;
        border: none;
    }

    .btn-primary-login:hover {
        background-color: #ff7f00;
        border: none;
    }

    .color-regis {
        color: #ff7f00;
    }

    .mb-0 a {
        color: #ff7f00;
    }

    .form-check-input-change:checked {
        border: #ff7f00;
        background-color: #ff7f00;
    }

    .text-end a {
        color: #ff7f00;
    }

    .images img {
        width: 50%;
    }

    .error-message {
        color: red;
    }


    label[id*="-error"] {
        color: red;
        position: relative;
        padding: 0;
    }
    </style>
    <title>GoldenBeeGroup Authentication</title>
    <style>

    </style>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>


    <!--start wrapper-->
    <div class="wrapper">
        <?php
include "./auth-header.php";
?>

    <form  action="insUser.php" method="POST">
    <div class="container mt-4">
    <div class="row justify-content-center">
    <div class="col-md-6">
                        <div class="card mx-4">
                        <div class="card-body p-4">
    <h2>Đăng ký tài khoản</h2>

    <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
        <input class="form-control" placeholder="Họ tên" type="text" name="ho_ten" required>
        </div>
        
        <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
        <input class="form-control" placeholder="Mật khẩu" type="password" name="mat_khau" required >
        </div>
<!-- 
        <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
                                    <input type="file" class="form-control" name="avatar" id="avatar" accept="image/*"  onchange="previewImage(event)">
									<img id="preview" src="https://www.w3schools.com/w3images/avatar2.png" class="rounded-circle" height="70">
                                </div> -->
        
        <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
        <input class="form-control" placeholder="Địa chỉ" type="text" name="diachi">
        </div>
        
        <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
        <input class="form-control" placeholder="Số điện thoại" type="text" name="sodienthoai" required>
        </div>
        
        <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </span>
                                    </div>
        <input class="form-control" placeholder="Email" type="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-block btn-success"">Tạo tài khoản!</button>
        <p>Nếu bạn đã có Tài khoản, xin mời Đăng nhập</p>
        <a class="btn btn-primary form-control"
                                            href="login.php">Đăng nhập</a>
        
    </div>
    </div>
    </div>
    </div>
    </form>


    <?php
include "./auth-footer.php";
?>

    </div>
</body>
</html>
