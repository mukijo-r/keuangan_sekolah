<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title></title>
        <style>
            @media print {
                .sb-topnav, .navbar, .navbar-expand, .navbar-dark, .bg-dark, .navbar-brand, .ps-3,
                .btn, .btn-link, .btn-sm, .order-1, .order-lg-0, .me-4, .me-lg-0, .d-none, .d-md-inline-block,
                .form-inline, .ms-auto, .me-0, .me-md-3, .my-2, .my-md-0, .navbar-nav, .ms-md-0, .me-3,
                .me-lg-4, .nav-item, .dropdown, .nav-link, .dropdown-toggle, .dropdown-menu, .dropdown-menu-end,
                .dropdown-item, .dropdown-divider {
                    display: none !important;
                }
                body {
                margin: 0 !important;
                padding: 0 !important;
                }
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    </head>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-4" href="index.php">SIM SDK BHAKTI</a>            
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalRegister">Register</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalGantiPassword">Ganti password</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
    </nav>

    <!-- Modal Register-->
    <div class="modal fade" id="modalRegister">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Tambah Akun</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <!-- Modal body -->      
            <form method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="username">Username :</label>   
                    <input type="text" name="username" placeholder="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password">Password :</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="confirmPassword">Konfirmasi Password :</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
                    <div id="passwordError" class="text-danger"></div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success" name="tambahUser">Tambah</button> 
            </div>
            <br> 
        </form>   
        </div>
    </div>
    </div>

    <script>
        document.getElementById("confirmPassword").addEventListener("input", function() {
            const password = document.getElementById("password").value;
            const confirmPassword = this.value;
            const passwordError = document.getElementById("passwordError");

            if (password !== confirmPassword) {
                passwordError.textContent = "Password harus sama";
            } else {
                passwordError.textContent = ""; // Kosongkan pesan kesalahan jika password cocok
            }
        });

        <?php
        if (isset($_POST['tambahUser'])) {
            $username = $_POST['username'];
            $password1 = $_POST['password'];
            $password2 = $_POST['confirmPassword'];
            if ($password1 == $password2) {
                $password = password_hash($password1, PASSWORD_BCRYPT);
            
            // Coba jalankan query insert
            $addSiswa = mysqli_query($conn, "INSERT INTO `users`(`username`, `password`) VALUES ('$username', '$password')");

            $checkUserQuery = "SELECT * FROM `users` WHERE `username` = '$username'";
            $checkUserResult = mysqli_query($conn, $checkUserQuery);

            if (mysqli_num_rows($checkUserResult) > 0) {
                // Akun berhasil ditambahkan
                echo "Swal.fire({
                    title: 'Sukses!',
                    text: 'Akun berhasil ditambahkan.',
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });";
            }
        } else {
            // Gagal menambahkan akun
            echo "Swal.fire({
                title: 'Gagal!',
                text: 'Tambah akun gagal.',
                icon: 'error',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            });";
        }
        }
        ?>
    </script>