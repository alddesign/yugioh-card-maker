<?php 
$_SESSION['token'] = bin2hex(random_bytes(8));
?><!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Google Analytics -->
		<?php view('gtag') ?>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta name="robots" content="index, follow">
		<meta name="theme-color" content="#d0521e">

		<title><?= TITLE ?></title>

		<meta title="<?= TITLE ?>">
		<meta name="description" content="<?= DESCRIPTION ?>">
		<meta name="keywords" content="<?= KEYWORDS ?>">
		<meta property="og:site_name" content="Yu-Gi-Oh! Card Maker" />
		<meta property="og:title" content="<?= TITLE ?>" />
		<meta property="og:description" content="<?= DESCRIPTION ?>">
		<meta property="og:image" content="<?= ICON_URL ?>" />
		<meta property="og:logo" content="<?= ICON_URL ?>" />
        <meta property="og:locale" content="en_US">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?= BASE_URL ?>">

		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?= TITLE ?>">
		<meta name="twitter:description" content="<?= DESCRIPTION ?>">
		<meta name="twitter:image" content="<?= ICON_URL ?>">

		<link rel="canonical" href="<?= BASE_URL ?>">
		<link rel="icon" href="<?= ICON_URL ?>" type="image/png" sizes="512x512">
		<link rel="apple-touch-icon" href="<?= ICON_URL ?>" type="image/png" sizes="512x512">

		<link rel="stylesheet" href="./css/bootstrap-4.6.2.min.css">
		<link rel="stylesheet" href="./css/style.css?v=<?= VERSION ?>">
		
		<script id="php-js">
			var token = '<?= $_SESSION['token'] ?>';
			var isLocal = <?= IS_LOCAL ? 'true' : 'false' ?>;
			var baseUrl = '<?= BASE_URL ?>';
		</script>
		<script src="./js/jquery-4.0.0.slim.min.js" defer></script>
		<script src="./js/script.js?v=<?= VERSION ?>" defer></script>
	</head>
	<body class="dark">
		<div class="container-fluid">