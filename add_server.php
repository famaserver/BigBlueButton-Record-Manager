<?php
require_once __DIR__ . '/auth.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = isset($_POST['name']) ? trim($_POST['name']) : '';
	$url = isset($_POST['url']) ? trim($_POST['url']) : '';
	$secret = isset($_POST['secret']) ? trim($_POST['secret']) : '';

	if ($name === '' || $url === '' || $secret === '') {
		$errorMessage = 'همه فیلدها الزامی هستند.';
	} elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
		$errorMessage = 'URL نامعتبر است.';
	} else {
		$pdo = getPDOConnection();
		$stmt = $pdo->prepare('INSERT INTO servers (name, url, secret, created_at) VALUES (:name, :url, :secret, NOW())');
		try {
			$stmt->execute([
				':name' => $name,
				':url' => $url,
				':secret' => $secret,
			]);
			// Redirect to servers list after success
			header('Location: servers.php');
			exit;
		} catch (PDOException $e) {
			$errorMessage = 'خطا در ذخیره‌سازی. لطفاً بعداً تلاش کنید.';
		}
	}
}

if (!isset($name)) { $name = ''; }
if (!isset($url)) { $url = ''; }
if (!isset($secret)) { $secret = ''; }
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

		<!-- Select2 css -->
		<link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet">

		
		<!-- Sidemenu css-->
		<link href="assets/css-rtl/sidemenu/sidemenu.css" rel="stylesheet">

		<!-- Switcher css-->
		<link href="assets/switcher/css/switcher-rtl.css" rel="stylesheet">
		<link href="assets/switcher/demo.css" rel="stylesheet">
		

		

	</head>

<body class="main-body leftmenu">



<!-- Loader -->
<div id="global-loader">
	<img src="assets/img/loader.svg" class="loader-img" alt="لودر">
</div>
<!-- End Loader -->

<!-- Page -->
<div class="page">

	<!-- Sidemenu -->
	<div class="main-sidebar main-sidebar-sticky side-menu">
		<div class="sidemenu-logo">
			<a class="main-logo" href="index.html">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img desktop-logo" alt="لوگو">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img icon-logo" alt="لوگو">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img desktop-logo theme-logo" alt="لوگو">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img icon-logo theme-logo" alt="لوگو">
			</a>
		</div>
		<div class="main-sidebar-body">
			<ul class="nav">
				<li class="nav-header"><span class="nav-label">داشبورد</span></li>
				<li class="nav-item">
					<a class="nav-link" href="index.php"><span class="shape1"></span><span class="shape2"></span><i class="ti-home sidemenu-icon"></i><span class="sidemenu-label">داشبورد</span></a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="ti-write sidemenu-icon"></i><span class="sidemenu-label">سرورها</span><i class="angle fe fe-chevron-left"></i></a>
					<ul class="nav-sub">
						<li class="nav-sub-item">
							<a class="nav-sub-link" href="servers.php">لیست سرور ها</a>
						</li>
						<li class="nav-sub-item">
							<a class="nav-sub-link" href="add_server.php">افزودن سرور</a>
						</li>
					</ul>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" href="logout.php"><span class="shape1"></span><span class="shape2"></span><i class="fe fe-log-out sidemenu-icon"></i><span class="sidemenu-label">خروج</span></a>
				</li>
				
				<!-- <li class="nav-item">
					<a class="nav-link" href="tools.html"><span class="shape1"></span><span class="shape2"></span><i class="ti-server sidemenu-icon"></i><span class="sidemenu-label">ابزارک ها</span></a>
				</li> -->
				
				
				
				
				
				</li>
			</ul>
		</div>
	</div>
	<!-- End Sidemenu -->	

	<!-- Main Content-->
	<div class="main-content side-content pt-0">
		<div class="container-fluid">
			<div class="inner-body">


				<!-- Page Header -->
				<div class="page-header">
					<div>
						<h2 class="main-content-title tx-24 mg-b-5">افزودن سرور بیگ‌بلو‌باتن</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">سرورها</a></li>
							<li class="breadcrumb-item active" aria-current="page">افزودن سرور</li>
						</ol>
					</div>
					
				</div>
				<!-- End Page Header -->

				<!-- Row -->
			<div class="col-lg-6 col-md-12">
						<div class="card custom-card">
							<div class="card-body">
								<?php if ($successMessage !== ''): ?>
									<div class="alert alert-success text-right" role="alert"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></div>
								<?php endif; ?>
								<?php if ($errorMessage !== ''): ?>
									<div class="alert alert-danger text-right" role="alert"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
								<?php endif; ?>

								<form method="post" action="add_server.php" class="d-flex flex-column">
									<div class="form-group text-right">
										<label>نام سرور</label>
										<input name="name" class="form-control" placeholder="نام نمایشی سرور" type="text" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="form-group text-right">
										<label>URL</label>
										<input name="url" class="form-control" placeholder="مثال: https://bbb.example.com/bigbluebutton/" type="url" value="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<div class="form-group text-right">
										<label>Secret</label>
										<input name="secret" class="form-control" placeholder="Shared Secret" type="text" value="<?php echo htmlspecialchars($secret, ENT_QUOTES, 'UTF-8'); ?>">
									</div>
									<button class="btn ripple btn-main-primary" type="submit">ثبت سرور</button>
								</form>
							</div>
						</div>
					</div>
				<!-- End Row -->


			</div>
		</div>
	</div>
	<!-- End Main Content-->

	<!-- Main Footer-->
	<div class="main-footer text-center">
		<div class="container">
			<div class="row row-sm">
				<div class="col-md-12">
					<span>کپی رایت © 1399  . طراحی شده توسط <a href="#">Themefix</a> کلیه حقوق محفوظ است.</span>
				</div>
			</div>
		</div>
	</div>
	<!--End Footer-->		

</div>
<!-- End Page -->

<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

<!-- Jquery js-->
<script src="assets/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap js-->
<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap-rtl.js"></script>

<!-- Perfect-scrollbar js -->
<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min-rtl.js"></script>

<!-- Sidemenu js -->
<script src="assets/plugins/sidemenu/sidemenu-rtl.js"></script>

<!-- Select2 js-->
<script src="assets/plugins/select2/js/select2.min.js"></script>


<!-- Sticky js -->
<script src="assets/js/sticky.js"></script>

<!-- Custom js -->
<script src="assets/js/custom.js"></script>

<!-- Switcher js -->
<script src="assets/switcher/js/switcher-rtl.js"></script>


</body>
</html>