<?php use Tonight\MVC\Router; ?>
<?php $public = Router::getLink() ?>
<?php $login = Router::getLink('login') ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $title ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="<?= $public ?>/assets/css/normalize.css">
		<link type="text/css" rel="stylesheet" href="<?= $public ?>/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?= $public ?>/assets/css/style.css" />
	</head>
	<body>

<?php require __DIR__ . '/header.php'; ?>

<?php $this->content(); ?>

<?php require __DIR__ . '/footer.php'; ?>

		<!--[if lt IE 9]>
			<script src="<?= $public ?>/assets/js/html5shiv.js"></script>
			<script src="<?= $public ?>/assets/js/respond.js"></script>
		<![endif]-->
		<script type="text/javascript" src="<?= $public ?>/assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="<?= $public ?>/assets/js/script.js"></script>
		<script type="text/javascript" src="<?= $public ?>/assets/js/bootstrap.min.js"></script>
	</body>
</html>