<?php
include 'function.php';
require 'config.php';

//cek login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Dapatkan kata sandi terenkripsi dari database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if ($row = mysqli_fetch_assoc($result)) {
        $hashedPassword = $row['password'];

        // Periksa apakah kata sandi yang dimasukkan sesuai dengan yang terenkripsi
        if (password_verify($password, $hashedPassword)) {
            // Kata sandi cocok, beri izin login
            $_SESSION['user'] = $username; // Simpan nama user dalam sesi
            $_SESSION['log'] = 'True';
            $_SESSION['previous_user'] = $username;            
            header('location:index.php');
        } else {
            // Kata sandi tidak cocok, arahkan kembali ke halaman login
            header('location:login.php');
        }
    } else {
        // Tidak ada akun dengan username tersebut
        header('location:login.php');
    }
}

?>

<!-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SDK Bhakti</title>
        <style>
            .with-background {
                background-image: url('assets/img/login.jpeg'); /* Ganti 'url-gambar-anda.jpg' dengan URL gambar yang ingin Anda gunakan */
                background-size: cover; /* Untuk mengatur gambar agar menutupi seluruh div */
                background-repeat: no-repeat; /* Agar gambar tidak diulang */
                background-position: center center; /* Agar gambar terpusat dalam div */
                /* opacity: 0.6; */
            }
        </style>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication" class="with-background">
            <div id="layoutAuthentication_content">
                <main>
                    <br>
                    <div class="row" style="text-align: center;" >
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6">
                            <figure class="bg-light p-4" style="opacity: 0.55;">
                                <blockquote class="blockquote pb-2">
                                    <h2>
                                        SISTEM INFORMASI MANAJEMEN KEUANGAN
                                    </h2>
                                    <h2>
                                        SD KATOLIK BHAKTI ROGOJAMPI
                                    </h2>
                                </blockquote>
                            </figure>
                        </div>
                        <div class="col-md-3">
                        </div>
                    </div>

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="username" id="inputUsername" type="text" placeholder="Username" />
                                                <label for="inputUsername">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                            <div class="d-flex justify-content-center mt-4 mb-0">
                                                <a class="small" href="#.html"></a>
                                                <button class="btn btn-primary mx-auto" name="login">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="#"></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html> -->

<!doctype html>
<html lang="en">
  <head>
  	<title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<style>
			:root {
		  --blue: #007bff;
		  --indigo: #6610f2;
		  --purple: #6f42c1;
		  --pink: #e83e8c;
		  --red: #dc3545;
		  --orange: #fd7e14;
		  --yellow: #ffc107;
		  --green: #28a745;
		  --teal: #20c997;
		  --cyan: #17a2b8;
		  --white: #fff;
		  --gray: #6c757d;
		  --gray-dark: #343a40;
		  --primary: #007bff;
		  --secondary: #6c757d;
		  --success: #28a745;
		  --info: #17a2b8;
		  --warning: #ffc107;
		  --danger: #dc3545;
		  --light: #f8f9fa;
		  --dark: #343a40;
		  --breakpoint-xs: 0;
		  --breakpoint-sm: 576px;
		  --breakpoint-md: 768px;
		  --breakpoint-lg: 992px;
		  --breakpoint-xl: 1200px;
		  --font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
		  --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }

		*,
		*::before,
		*::after {
		  -webkit-box-sizing: border-box;
		  box-sizing: border-box; }


		figure {
		  margin: 0 0 1rem; }

		img {
		  vertical-align: middle;
		  border-style: none; }

		button {
		  border-radius: 0; }

		button:focus {
		  outline: 1px dotted;
		  outline: 5px auto -webkit-focus-ring-color; }

		input,
		button,
		select,
		optgroup,
		textarea {
		  margin: 0;
		  font-family: inherit;
		  font-size: inherit;
		  line-height: inherit; }

		button,
		input {
		  overflow: visible; }

		button,
		select {
		  text-transform: none; }

		select {
		  word-wrap: normal; }



		h1, h2, h3, h4, h5, h6,
		.h1, .h2, .h3, .h4, .h5, .h6 {
		  margin-bottom: 0.5rem;
		  font-weight: 500;
		  line-height: 1.2; }

		h2, .h2 {
		  font-size: 2rem; }

		h3, .h3 {
		  font-size: 1.75rem; }

		small,
		.small {
		  font-size: 80%;
		  font-weight: 400; }

		.img-fluid {
		  max-width: 100%;
		  height: auto; }


		.container {
		  width: 100%;
		  padding-right: 15px;
		  padding-left: 15px;
		  margin-right: auto;
		  margin-left: auto; }
		  @media (min-width: 576px) {
			.container {
			  max-width: 540px; } }
		  @media (min-width: 768px) {
			.container {
			  max-width: 720px; } }
		  @media (min-width: 992px) {
			.container {
			  max-width: 960px; } }
		  @media (min-width: 1200px) {
			.container {
			  max-width: 1140px; } }

		.container-fluid {
		  width: 100%;
		  padding-right: 15px;
		  padding-left: 15px;
		  margin-right: auto;
		  margin-left: auto; }

		.row {
		  display: -webkit-box;
		  display: -ms-flexbox;
		  display: flex;
		  -ms-flex-wrap: wrap;
		  flex-wrap: wrap;
		  margin-right: -15px;
		  margin-left: -15px; }

		@media (min-width: 992px) {
		  .col-lg {
			-ms-flex-preferred-size: 0;
			flex-basis: 0;
			-webkit-box-flex: 1;
			-ms-flex-positive: 1;
			flex-grow: 1;
			max-width: 100%; }
		  .col-lg-auto {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 auto;
			flex: 0 0 auto;
			width: auto;
			max-width: 100%; }
		  .col-lg-1 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 8.33333%;
			flex: 0 0 8.33333%;
			max-width: 8.33333%; }
		  .col-lg-2 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 16.66667%;
			flex: 0 0 16.66667%;
			max-width: 16.66667%; }
		  .col-lg-3 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 25%;
			flex: 0 0 25%;
			max-width: 25%; }
		  .col-lg-4 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 33.33333%;
			flex: 0 0 33.33333%;
			max-width: 33.33333%; }
		  .col-lg-5 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 41.66667%;
			flex: 0 0 41.66667%;
			max-width: 41.66667%; }
		  .col-lg-6 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 50%;
			flex: 0 0 50%;
			max-width: 50%; }
		  .col-lg-7 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 58.33333%;
			flex: 0 0 58.33333%;
			max-width: 58.33333%; }
		  .col-lg-8 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 66.66667%;
			flex: 0 0 66.66667%;
			max-width: 66.66667%; }
		  .col-lg-9 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 75%;
			flex: 0 0 75%;
			max-width: 75%; }
		  .col-lg-10 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 83.33333%;
			flex: 0 0 83.33333%;
			max-width: 83.33333%; }
		  .col-lg-11 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 91.66667%;
			flex: 0 0 91.66667%;
			max-width: 91.66667%; }
		  .col-lg-12 {
			-webkit-box-flex: 0;
			-ms-flex: 0 0 100%;
			flex: 0 0 100%;
			max-width: 100%; }
		  .order-lg-first {
			-webkit-box-ordinal-group: 0;
			-ms-flex-order: -1;
			order: -1; }
		  .order-lg-last {
			-webkit-box-ordinal-group: 14;
			-ms-flex-order: 13;
			order: 13; }
		  .order-lg-0 {
			-webkit-box-ordinal-group: 1;
			-ms-flex-order: 0;
			order: 0; }
		  .order-lg-1 {
			-webkit-box-ordinal-group: 2;
			-ms-flex-order: 1;
			order: 1; }
		  .order-lg-2 {
			-webkit-box-ordinal-group: 3;
			-ms-flex-order: 2;
			order: 2; }
		  .order-lg-3 {
			-webkit-box-ordinal-group: 4;
			-ms-flex-order: 3;
			order: 3; }
		  .order-lg-4 {
			-webkit-box-ordinal-group: 5;
			-ms-flex-order: 4;
			order: 4; }
		  .order-lg-5 {
			-webkit-box-ordinal-group: 6;
			-ms-flex-order: 5;
			order: 5; }
		  .order-lg-6 {
			-webkit-box-ordinal-group: 7;
			-ms-flex-order: 6;
			order: 6; }
		  .order-lg-7 {
			-webkit-box-ordinal-group: 8;
			-ms-flex-order: 7;
			order: 7; }
		  .order-lg-8 {
			-webkit-box-ordinal-group: 9;
			-ms-flex-order: 8;
			order: 8; }
		  .order-lg-9 {
			-webkit-box-ordinal-group: 10;
			-ms-flex-order: 9;
			order: 9; }
		  .order-lg-10 {
			-webkit-box-ordinal-group: 11;
			-ms-flex-order: 10;
			order: 10; }
		  .order-lg-11 {
			-webkit-box-ordinal-group: 12;
			-ms-flex-order: 11;
			order: 11; }
		  .order-lg-12 {
			-webkit-box-ordinal-group: 13;
			-ms-flex-order: 12;
			order: 12; }
		  .offset-lg-0 {
			margin-left: 0; }
		  .offset-lg-1 {
			margin-left: 8.33333%; }
		  .offset-lg-2 {
			margin-left: 16.66667%; }
		  .offset-lg-3 {
			margin-left: 25%; }
		  .offset-lg-4 {
			margin-left: 33.33333%; }
		  .offset-lg-5 {
			margin-left: 41.66667%; }
		  .offset-lg-6 {
			margin-left: 50%; }
		  .offset-lg-7 {
			margin-left: 58.33333%; }
		  .offset-lg-8 {
			margin-left: 66.66667%; }
		  .offset-lg-9 {
			margin-left: 75%; }
		  .offset-lg-10 {
			margin-left: 83.33333%; }
		  .offset-lg-11 {
			margin-left: 91.66667%; } }

		.form-control {
		  display: block;
		  width: 100%;
		  height: calc(1.5em + 0.75rem + 2px);
		  padding: 0.375rem 0.75rem;
		  font-size: 1rem;
		  font-weight: 400;
		  line-height: 1.5;
		  color: #495057;
		  background-color: #fff;
		  background-clip: padding-box;
		  border: 1px solid #ced4da;
		  border-radius: 0.25rem;
		  -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
		  transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
		  -o-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
		  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
		  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out; }
		  @media (prefers-reduced-motion: reduce) {
			.form-control {
			  -webkit-transition: none;
			  -o-transition: none;
			  transition: none; } }
		  .form-control::-ms-expand {
			background-color: transparent;
			border: 0; }
		  .form-control:focus {
			color: #495057;
			background-color: #fff;
			border-color: #80bdff;
			outline: 0;
			-webkit-box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); }
		  .form-control::-webkit-input-placeholder {
			color: #6c757d;
			opacity: 1; }
		  .form-control:-ms-input-placeholder {
			color: #6c757d;
			opacity: 1; }
		  .form-control::-ms-input-placeholder {
			color: #6c757d;
			opacity: 1; }
		  .form-control::placeholder {
			color: #6c757d;
			opacity: 1; }
		  .form-control:disabled, .form-control[readonly] {
			background-color: #e9ecef;
			opacity: 1; }

		.form-group {
		  margin-bottom: 3rem; }

		.justify-content-center {
		  -webkit-box-pack: center !important;
		  -ms-flex-pack: center !important;
		  justify-content: center !important; }

		.align-items-center {
		  -webkit-box-align: center !important;
		  -ms-flex-align: center !important;
		  align-items: center !important; }

		.align-content-center {
		  -ms-flex-line-pack: center !important;
		  align-content: center !important; }

		.align-self-center {
		  -ms-flex-item-align: center !important;
		  -ms-grid-row-align: center !important;
		  align-self: center !important; }

		.text-center {
		  text-align: center !important; }

		body {
		  font-family: "Lato", Arial, sans-serif;
		  font-size: 16px;
		  line-height: 1.8;
		  font-weight: normal;
		  color: gray;
		  position: relative;
		  z-index: 0;
		  padding: 0; }
		  body:after {
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			content: '';
			background: #000;
			opacity: .3;
			z-index: -1; }

		.ftco-section {
		  padding: 7em 0; }

		.ftco-no-pt {
		  padding-top: 0; }

		.ftco-no-pb {
		  padding-bottom: 0; }

		.heading-section {
		  font-size: 28px;
		  color: #fff; }

		.img {
		  background-size: cover;
		  background-repeat: no-repeat;
		  background-position: center center; }

		.login-wrap {
		  position: relative;
		  color: rgba(255, 255, 255, 0.9); }
		  .login-wrap h3 {
			font-weight: 300;
			color: #fff; }
		  .login-wrap .social {
			width: 100%; }
			.login-wrap .social a {
			  width: 100%;
			  display: block;
			  border: 1px solid rgba(255, 255, 255, 0.4);
			  color: #000;
			  background: #fff; }
			  .login-wrap .social a:hover {
				background: #000;
				color: #fff;
				border-color: #000; }

		.form-control {
		  background: transparent;
		  border: none;
		  height: 50px;
		  color: white !important;
		  border: 1px solid transparent;
		  background: rgba(255, 255, 255, 0.08);
		  border-radius: 40px;
		  padding-left: 20px;
		  padding-right: 20px;
		  -webkit-transition: 0.3s;
		  -o-transition: 0.3s;
		  transition: 0.3s; }
		  @media (prefers-reduced-motion: reduce) {
			.form-control {
			  -webkit-transition: none;
			  -o-transition: none;
			  transition: none; } }
		  .form-control::-webkit-input-placeholder {
			/* Chrome/Opera/Safari */
			color: rgba(255, 255, 255, 0.8) !important; }
		  .form-control::-moz-placeholder {
			/* Firefox 19+ */
			color: rgba(255, 255, 255, 0.8) !important; }
		  .form-control:-ms-input-placeholder {
			/* IE 10+ */
			color: rgba(255, 255, 255, 0.8) !important; }
		  .form-control:-moz-placeholder {
			/* Firefox 18- */
			color: rgba(255, 255, 255, 0.8) !important; }
		  .form-control:hover, .form-control:focus {
			background: transparent;
			outline: none;
			-webkit-box-shadow: none;
			box-shadow: none;
			border-color: rgba(255, 255, 255, 0.4); }
		  .form-control:focus {
			border-color: rgba(255, 255, 255, 0.4); }

		.checkbox-wrap {
		  display: block;
		  position: relative;
		  padding-left: 30px;
		  margin-bottom: 12px;
		  cursor: pointer;
		  font-size: 16px;
		  font-weight: 500;
		  -webkit-user-select: none;
		  -moz-user-select: none;
		  -ms-user-select: none;
		  user-select: none; }

		/* Hide the browser's default checkbox */
		.checkbox-wrap input {
		  position: absolute;
		  opacity: 0;
		  cursor: pointer;
		  height: 0;
		  width: 0; }

		/* Create a custom checkbox */
		.checkmark {
		  position: absolute;
		  top: 0;
		  left: 0; }

		/* Create the checkmark/indicator (hidden when not checked) */
		.checkmark:after {
		  content: "\f0c8";
		  font-family: "FontAwesome";
		  position: absolute;
		  color: rgba(255, 255, 255, 0.1);
		  font-size: 20px;
		  margin-top: -4px;
		  -webkit-transition: 0.3s;
		  -o-transition: 0.3s;
		  transition: 0.3s; }
		  @media (prefers-reduced-motion: reduce) {
			.checkmark:after {
			  -webkit-transition: none;
			  -o-transition: none;
			  transition: none; } }

		/* Show the checkmark when checked */
		.checkbox-wrap input:checked ~ .checkmark:after {
		  display: block;
		  content: "\f14a";
		  font-family: "FontAwesome";
		  color: rgba(0, 0, 0, 0.2); }

		/* Style the checkmark/indicator */
		.checkbox-primary {
		  color: #fbceb5; }
		  .checkbox-primary input:checked ~ .checkmark:after {
			color: #fbceb5; }

		.btn {
		  cursor: pointer;
		  border-radius: 40px;
		  -webkit-box-shadow: none !important;
		  box-shadow: none !important;
		  font-size: 15px;
		  text-transform: uppercase; }
		  .btn:hover, .btn:active, .btn:focus {
			outline: none; }
		  .btn.btn-primary {
			background: #fbceb5 !important;
			border: 1px solid #fbceb5 !important;
			color: #000 !important; }
			.btn.btn-primary:hover {
			  border: 1px solid #fbceb5;
			  background: transparent;
			  color: #fbceb5; }
			.btn.btn-primary.btn-outline-primary {
			  border: 1px solid #fbceb5;
			  background: transparent;
			  color: #fbceb5; }
			  .btn.btn-primary.btn-outline-primary:hover {
				border: 1px solid transparent;
				background: #fbceb5;
				color: #fff; }

	</style>

	</head>
	<body class="img js-fullheight" style="background-image: url(assets/img/bg.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 text-center mb-5">
					<h2 class="heading-section">SISTEM INFORMASI MANAJEMEN KEUANGAN</h2>
					<h2 class="heading-section">SD KATOLIK BHAKTI ROGOJAMPI</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">Login</h3>
		      	<form method="post">
		      		<div class="form-group">
		      			<input type="text" name="username" class="form-control" placeholder="Username" required>
		      		</div>
	            <div class="form-group">
	              <input id="password-field" type="password" name="password" class="form-control" placeholder="Password" required>
	              <span toggle="#password-field" class="fa fa-fw field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button name="login" class="form-control btn btn-primary submit px-3">Sign In</button>
                    <!-- <button class="btn btn-primary mx-auto" name="login">Login</button> -->
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-50">
		            	<label class="checkbox-wrap checkbox-primary">Remember Me
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                        </label>
                    </div>
	            </div>
	          </form>
		      </div>
				</div>
			</div>
            <br><br><br><br><br><br>
		</div>
	</section>


	</body>
</html>
