<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$emailOrUsername = isset($_POST['email']) ? trim($_POST['email']) : '';
	$password = isset($_POST['password']) ? (string)$_POST['password'] : '';

	if ($emailOrUsername === '' || $password === '') {
		$errorMessage = 'ایمیل/نام کاربری و کلمه عبور الزامی است';
	} else {
		$pdo = getPDOConnection();
		$stmt = $pdo->prepare('SELECT id, username, email, password FROM users WHERE email = :id1 OR username = :id2 LIMIT 1');
		$stmt->execute([':id1' => $emailOrUsername, ':id2' => $emailOrUsername]);
		$user = $stmt->fetch();

		if ($user) {
			$stored = (string)$user['password'];
			$isValid = false;
			if (strlen($stored) > 0 && (strpos($stored, '$2y$') === 0 || strpos($stored, '$argon2') === 0)) {
				// Likely a hashed password
				$isValid = password_verify($password, $stored);
			} else {
				// Plain text fallback
				$isValid = hash_equals($stored, $password);
			}

			if ($isValid) {
				$_SESSION['user_id'] = (int)$user['id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['email'] = $user['email'];
				header('Location: index.php');
				exit;
			}
		}

		$errorMessage = 'اطلاعات ورود نادرست است';
	}
}
?>
<!DOCTYPE html>
<html lang="en" dir="rtl" >
	
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<meta name="description" content="Spruha -  Admin Panel laravel Dashboard Template">
		<meta name="author" content="Spruko Technologies Private Limited">
		<meta name="keywords" content="admin laravel template, template laravel admin, laravel css template, best admin template for laravel, laravel blade admin template, template admin laravel, laravel admin template bootstrap 4, laravel bootstrap 4 admin template, laravel admin bootstrap 4, admin template bootstrap 4 laravel, bootstrap 4 laravel admin template, bootstrap 4 admin template laravel, laravel bootstrap 4 template, bootstrap blade template, laravel bootstrap admin template">

		<!-- Favicon -->
		<link rel="icon" href="assets/img/brand/favicon.ico" type="image/x-icon"/>

		<!-- Title -->
		<title>famabutton</title>

		<!-- Bootstrap css-->
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>

		<!-- Icons css-->
		<link href="assets/plugins/web-fonts/icons.css" rel="stylesheet"/>
		<link href="assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
		<link href="assets/plugins/web-fonts/plugin.css" rel="stylesheet"/>

		<!-- Style css-->
		<link href="assets/css-rtl/style/style.css" rel="stylesheet">
		<link href="assets/css-rtl/skins.css" rel="stylesheet">
		<link href="assets/css-rtl/dark-style.css" rel="stylesheet">
		<link href="assets/css-rtl/colors/default.css" rel="stylesheet">
		
		
		<!-- Color css-->
		<link id="theme" rel="stylesheet" type="text/css" media="all" href="assets/css-rtl/colors/color.css">

		<!-- Switcher css-->
		<link href="assets/switcher/css/switcher-rtl.css" rel="stylesheet">
		<link href="assets/switcher/demo.css" rel="stylesheet">


		


	</head>

	<body class="main-body leftmenu">

			
		
		<?php if ($errorMessage !== ''): ?>
		<div class="container mt-4">
			<div class="alert alert-danger text-right" role="alert"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
		</div>
		<?php endif; ?>
		
		<!-- Page -->
		<div class="page main-signin-wrapper">

			<!-- Row -->
			<div class="row signpages text-center">
				<div class="col-md-12">
					<div class="card">
						<div class="row row-sm">
							<div class="col-lg-6 col-xl-5 d-none d-lg-block text-center bg-primary details">
								<div class="d-flex align-items-center justify-content-center" style="min-height: 100%; height: 100%;">
									<a href="https://famaserver.com/" target="_blank" style="display: inline-block;">
										<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img mb-4" alt="logo">
									</a>
								</div>
							</div>
							<div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form ">
								<div class="container-fluid">
									<div class="row row-sm">
										<div class="card-body mt-2 mb-2">
											<img src="assets/img/brand/logo-famaserver.png" class=" d-lg-none header-brand-img text-left float-left mb-4" alt="logo">
											<div class="clearfix"></div>
											<form method="post" action="login.php">
												<h5 class="text-right mb-2">به حساب خود وارد شوید</h5>
												<p class="mb-4 text-muted tx-13 ml-0 text-right">برای ورود به سیستم نام کاربری و رمز عبور خود را وارد کنید</p>
												<div class="form-group text-right">
													<label>نام کاربری</label>
													<input name="email" class="form-control" placeholder="نام کاربری خود را وارد کنید" type="text" autocomplete="username">
												</div>
												<div class="form-group text-right">
													<label>کلمه عبور</label>
													<input name="password" class="form-control" placeholder="رمز ورود خود را وارد کنید" type="password" autocomplete="current-password">
												</div>
												<button class="btn ripple btn-main-primary btn-block" type="submit">ورود</button>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->

			</div>
			<!-- End Page -->


			<!-- Jquery js-->
			<script src="assets/plugins/jquery/jquery.min.js"></script>

			<!-- Bootstrap js-->
			<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
			<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

			<!-- Select2 js-->
			<script src="assets/plugins/select2/js/select2.min.js"></script>
			
			
			<!-- Custom js -->
			<script src="assets/js/custom.js"></script>

			<!-- Switcher js -->
			<script src="assets/switcher/js/switcher-rtl.js"></script>



			


	</body>

</html>