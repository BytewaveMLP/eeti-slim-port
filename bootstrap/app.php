<?php

use \Respect\Validation\Validator as v;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
		'db' => [
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'homestead',
			'username'  => 'homestead',
			'password'  => 'secret',
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
		],
		'password' => [
			'cost' => 10,
		],
	],
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use ($capsule) {
	return $capsule;
};

$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
		'cache' => false,
	]);

	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));

	return $view;
};

$container['validator'] = function ($container) {
	return new \Eeti\Validation\Validator;
};

$container['HomeController'] = function ($container) {
	return new \Eeti\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
	return new \Eeti\Controllers\Auth\AuthController($container);
};

$container['csrf'] = function ($container) {
	return new \Slim\Csrf\Guard;
};

$app->add(new \Eeti\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \Eeti\Middleware\OldInputMiddleware($container));
$app->add(new \Eeti\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('Eeti\\Validation\\Rules');

require __DIR__ . '/../app/routes.php';
