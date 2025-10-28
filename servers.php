<?php
require_once __DIR__ . '/auth.php';

$deleteSuccess = '';
$deleteError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_server_id'])) {
	$deleteId = (int)$_POST['delete_server_id'];
	if ($deleteId > 0) {
		$pdo = getPDOConnection();
		try {
			$stmt = $pdo->prepare('DELETE FROM servers WHERE id = :id');
			$stmt->execute([':id' => $deleteId]);
			$deleteSuccess = 'سرور با موفقیت حذف شد.';
		} catch (PDOException $e) {
			$deleteError = 'حذف سرور با خطا مواجه شد.';
		}
	}
}

$pdo = getPDOConnection();
$servers = [];
try {
	$stmt = $pdo->query('SELECT id, name, url FROM servers ORDER BY id DESC');
	$servers = $stmt->fetchAll();
} catch (PDOException $e) {
	$servers = [];
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
				
				
				
				
				
				</li>
			</ul>
		</div>
	</div>
	<!-- End Sidemenu -->	
	 	<!-- Main Header-->

	<div class="mobile-main-header">
		<div class="mb-1 navbar navbar-expand-lg  nav nav-item  navbar-nav-right responsive-navbar navbar-dark  ">
			<div class="collapse navbar-collapse" id="navbarSupportedContent-4">

			</div>
		</div>
	</div>
	<!-- Mobile-header closed -->
	<!-- Main Content-->
	<div class="main-content side-content pt-0">
		<div class="container-fluid">
			<div class="inner-body">


				<!-- Page Header -->
				<div class="page-header">
					<div>
						<h2 class="main-content-title tx-24 mg-b-5">لیست سرورها</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">سرورها</a></li>
							<li class="breadcrumb-item active" aria-current="page">لیست</li>
						</ol>
					</div>
					
				</div>
				<!-- End Page Header -->

				<!-- Row -->
				<div class="row row-sm">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div>
									<h6 class="main-content-label mb-1">جدول حاشیه ای</h6>
									<p class="text-muted card-sub-title">حاشیه ها را از چهار طرف جدول و سلول ها اضافه کنید.</p>
								</div>
								<div class="table-responsive">
									<?php if ($deleteSuccess !== ''): ?>
										<div class="alert alert-success text-right" role="alert"><?php echo htmlspecialchars($deleteSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
									<?php endif; ?>
									<?php if ($deleteError !== ''): ?>
										<div class="alert alert-danger text-right" role="alert"><?php echo htmlspecialchars($deleteError, ENT_QUOTES, 'UTF-8'); ?></div>
									<?php endif; ?>
									<table class="table text-nowrap text-md-nowrap table-bordered mg-b-0">
										<thead>
										<tr>
											<th>نام سرور</th>
											<th>URL</th>
											<th class="text-center">عملیات</th>
										</tr>
										</thead>
										<tbody>
										<?php if (empty($servers)): ?>
											<tr><td colspan="3" class="text-center">سروری ثبت نشده است.</td></tr>
										<?php else: ?>
											<?php foreach ($servers as $server): ?>
												<tr>
													<td><?php echo htmlspecialchars($server['name'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="text-left" dir="ltr" style="max-width:420px; white-space:normal; word-break:break-all;">
														<?php echo htmlspecialchars($server['url'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td class="text-center">
														<a class="btn btn-sm btn-info" href="view_record.php?server_id=<?php echo (int)$server['id']; ?>&status=published">پابلیش</a>
														<a class="btn btn-sm btn-warning" href="view_record.php?server_id=<?php echo (int)$server['id']; ?>&status=processing">پردازش</a>
														<form method="post" action="servers.php" class="d-inline" onsubmit="return confirm('حذف این سرور؟');">
															<input type="hidden" name="delete_server_id" value="<?php echo (int)$server['id']; ?>">
															<button type="submit" class="btn btn-sm btn-danger">حذف</button>
														</form>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
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

<!-- Sidebar js -->
<script src="assets/plugins/sidebar/sidebar-rtl.js"></script>

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