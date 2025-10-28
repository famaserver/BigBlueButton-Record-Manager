<?php
require_once __DIR__ . '/auth.php';

$serverId = isset($_GET['server_id']) ? (int)$_GET['server_id'] : 0;
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$validStatuses = ['published', 'processing', 'all'];
if (!in_array($status, $validStatuses, true)) {
	$status = 'published';
}

$actionMessage = '';
$actionError = '';

$errorMessage = '';
$records = [];

function bbbApiRequest($baseUrl, $secret, $method, array $params) {
	// Ensure base ends with a slash and points to /bigbluebutton/
	if (substr($baseUrl, -1) !== '/') {
		$baseUrl .= '/';
	}
	$apiBase = $baseUrl . 'api/';
	$query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
	$checksum = sha1($method . $query . $secret);
	$url = $apiBase . $method . '?' . $query . '&checksum=' . $checksum;

	$ch = curl_init($url);
	curl_setopt_array($ch, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CONNECTTIMEOUT => 8,
		CURLOPT_TIMEOUT => 15,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
	]);
	$response = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);

	return [$response, $err];
}

$server = null;
if ($serverId > 0) {
	$pdo = getPDOConnection();
	try {
		$stmt = $pdo->prepare('SELECT id, name, url, secret FROM servers WHERE id = :id LIMIT 1');
		$stmt->execute([':id' => $serverId]);
		$server = $stmt->fetch();
		if (!$server) {
			$errorMessage = 'سرور یافت نشد.';
		}
	} catch (PDOException $e) {
		$errorMessage = 'خطای پایگاه داده.';
	}
} else {
	$errorMessage = 'شناسه سرور نامعتبر است.';
}

// Handle POST actions: rebuild/delete
if ($server && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$recordId = isset($_POST['record_id']) ? trim($_POST['record_id']) : '';
	$action = isset($_POST['action']) ? trim($_POST['action']) : '';
	if ($recordId !== '' && in_array($action, ['rebuild', 'delete'], true)) {
		$method = $action === 'rebuild' ? 'rebuildRecordings' : 'deleteRecordings';
		$params = ['recordID' => $recordId];
		list($xmlString, $curlErr) = bbbApiRequest($server['url'], $server['secret'], $method, $params);
		if ($curlErr) {
			$actionError = 'خطای ارتباط: ' . htmlspecialchars($curlErr, ENT_QUOTES, 'UTF-8');
		} elseif (!$xmlString) {
			$actionError = 'پاسخ معتبر دریافت نشد.';
		} else {
			$xml = @simplexml_load_string($xmlString);
			if ($xml === false) {
				$actionError = 'فرمت پاسخ نامعتبر است.';
			} else {
				$result = (string)($xml->returncode ?? 'FAILED');
				$msgKey = isset($xml->messageKey) ? (string)$xml->messageKey : '';
				$msg = isset($xml->message) ? (string)$xml->message : '';
				if (strtoupper($result) === 'SUCCESS') {
					$actionMessage = ($action === 'rebuild') ? 'درخواست بازسازی ارسال شد.' : 'درخواست حذف ارسال شد.';
				} else {
					$actionError = 'عملیات ناموفق بود' . ($msgKey || $msg ? (' - ' . htmlspecialchars($msgKey . ' ' . $msg, ENT_QUOTES, 'UTF-8')) : '');
				}
			}
		}
	}
}

// Fetch recordings only if server is valid
if ($server && $errorMessage === '') {
	$params = [];
	if ($status !== 'all') {
		$params['state'] = $status;
	}
	list($xmlString, $curlErr) = bbbApiRequest($server['url'], $server['secret'], 'getRecordings', $params);
	if ($curlErr) {
		$errorMessage = 'خطا در اتصال به سرور: ' . htmlspecialchars($curlErr, ENT_QUOTES, 'UTF-8');
	} elseif (!$xmlString) {
		$errorMessage = 'پاسخی از سرور دریافت نشد.';
	} else {
		$xml = @simplexml_load_string($xmlString);
		if ($xml === false) {
			$errorMessage = 'فرمت پاسخ نامعتبر است.';
		} else {
			if (isset($xml->recordings->recording)) {
				foreach ($xml->recordings->recording as $rec) {
					$startMs = isset($rec->startTime) ? (int)$rec->startTime : 0;
					$startTs = $startMs > 0 ? (int) floor($startMs / 1000) : 0;
					$records[] = [
						'date' => $startTs ? date('Y-m-d', $startTs) : '-',
						'time' => $startTs ? date('H:i', $startTs) : '-',
						'name' => isset($rec->name) ? (string)$rec->name : (isset($rec->meetingName) ? (string)$rec->meetingName : '-'),
						'meeting_id' => isset($rec->meetingID) ? (string)$rec->meetingID : '-',
						'internal_id' => isset($rec->internalMeetingID) ? (string)$rec->internalMeetingID : '-',
						'record_id' => isset($rec->recordID) ? (string)$rec->recordID : '-',
						'state' => isset($rec->state) ? (string)$rec->state : '-',
					];
				}
			}
		}
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
						<h2 class="main-content-title tx-24 mg-b-5">رکوردهای بیگ‌بلو‌باتن</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">سرورها</a></li>
							<li class="breadcrumb-item active" aria-current="page">رکوردها (<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>)</li>
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
									<h6 class="main-content-label mb-1">فهرست رکوردها</h6>
									<p class="text-muted card-sub-title">
										نمایش بر اساس وضعیت:
										<a class="btn btn-xs btn-outline-secondary ml-2" href="view_record.php?server_id=<?php echo (int)$serverId; ?>&status=all">همه</a>
										<a class="btn btn-xs btn-outline-secondary" href="view_record.php?server_id=<?php echo (int)$serverId; ?>&status=published">پابلیش</a>
										<a class="btn btn-xs btn-outline-secondary" href="view_record.php?server_id=<?php echo (int)$serverId; ?>&status=processing">پردازش</a>
									</p>
								</div>
								<?php if ($errorMessage !== ''): ?>
									<div class="alert alert-danger text-right" role="alert"><?php echo $errorMessage; ?></div>
								<?php endif; ?>
								<?php if ($actionMessage !== ''): ?>
									<div class="alert alert-success text-right" role="alert"><?php echo $actionMessage; ?></div>
								<?php endif; ?>
								<?php if ($actionError !== ''): ?>
									<div class="alert alert-danger text-right" role="alert"><?php echo $actionError; ?></div>
								<?php endif; ?>
								<div class="table-responsive">
									<table class="table text-nowrap text-md-nowrap table-bordered mg-b-0">
										<thead>
										<tr>
											<th>تاریخ</th>
											<th>ساعت</th>
											<th>نام کلاس</th>
											<th>meeting_id</th>
											<th>internal_id</th>
											<th>record_id</th>
											<th>وضعیت</th>
											<th>عملیات</th>
										</tr>
										</thead>
										<tbody>
										<?php if (empty($records)): ?>
											<tr><td colspan="8" class="text-center">رکوردی یافت نشد.</td></tr>
										<?php else: ?>
											<?php foreach ($records as $rec): ?>
												<tr>
													<td><?php echo htmlspecialchars($rec['date'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo htmlspecialchars($rec['time'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td><?php echo htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td dir="ltr" style="max-width:240px; white-space:normal; word-break:break-all;">
														<?php echo htmlspecialchars($rec['meeting_id'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td dir="ltr" style="max-width:240px; white-space:normal; word-break:break-all;">
														<?php echo htmlspecialchars($rec['internal_id'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td dir="ltr" style="max-width:240px; white-space:normal; word-break:break-all;">
														<?php echo htmlspecialchars($rec['record_id'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td><?php echo htmlspecialchars($rec['state'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td>
														<?php
															$urlParts = @parse_url($server['url']);
															$scheme = isset($urlParts['scheme']) ? $urlParts['scheme'] : 'https';
															$host = isset($urlParts['host']) ? $urlParts['host'] : '';
															$port = isset($urlParts['port']) ? $urlParts['port'] : null;
															$baseHost = $host !== '' ? ($scheme . '://' . $host . ($port ? ':' . $port : '')) : rtrim((string)$server['url'], '/');
															$playUrl = $baseHost . '/playback/presentation/2.3/' . rawurlencode($rec['internal_id']);
														?>
														<a class="btn btn-sm btn-success" target="_blank" rel="noopener" href="<?php echo htmlspecialchars($playUrl, ENT_QUOTES, 'UTF-8'); ?>">نمایش</a>
														<?php if ($rec['state'] === 'published'): ?>
															<form method="POST" class="d-inline" action="view_record.php?server_id=<?php echo (int)$serverId; ?>&status=<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>">
																<input type="hidden" name="record_id" value="<?php echo htmlspecialchars($rec['record_id'], ENT_QUOTES, 'UTF-8'); ?>">
																<button type="submit" name="action" value="rebuild" class="btn btn-sm btn-info" onclick="return confirm('آیا از بازسازی رکورد مطمئن هستید؟')">بازسازی</button>
															</form>
															<form method="POST" class="d-inline" action="view_record.php?server_id=<?php echo (int)$serverId; ?>&status=<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>" onsubmit="return confirm('آیا از حذف رکورد مطمئن هستید؟')">
																<input type="hidden" name="record_id" value="<?php echo htmlspecialchars($rec['record_id'], ENT_QUOTES, 'UTF-8'); ?>">
																<button type="submit" name="action" value="delete" class="btn btn-sm btn-danger">حذف</button>
															</form>
														<?php endif; ?>
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