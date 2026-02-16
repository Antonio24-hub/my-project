<?php

use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->render('welcome', [ 'message' => 'You are gonna do great things!' ]);
	});

	$router->get('/hello-world/@name', function($name) {
		echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
	});

	// Besoin routes
	$router->get('/besoin/list', [ApiExampleController::class, 'listBesoins']);
	$router->get('/besoin/form', [ApiExampleController::class, 'formBesoin']);
	$router->post('/besoin/save', [ApiExampleController::class, 'saveBesoin']);
	$router->get('/besoin/edit', [ApiExampleController::class, 'editBesoin']);
	$router->post('/besoin/update', [ApiExampleController::class, 'updateBesoin']);
	$router->get('/besoin/delete', [ApiExampleController::class, 'deleteBesoin']);

	// Don routes
	$router->get('/don/list', [ApiExampleController::class, 'listDons']);
	$router->get('/don/form', [ApiExampleController::class, 'formDon']);
	$router->post('/don/save', [ApiExampleController::class, 'saveDon']);
	$router->get('/don/edit', [ApiExampleController::class, 'editDon']);
	$router->post('/don/update', [ApiExampleController::class, 'updateDon']);
	$router->get('/don/delete', [ApiExampleController::class, 'deleteDon']);

	// Distribution routes
	$router->get('/distribution/list', [ApiExampleController::class, 'listDistributions']);
	$router->get('/distribution/form', [ApiExampleController::class, 'formDistribution']);
	$router->post('/distribution/save', [ApiExampleController::class, 'saveDistribution']);
	$router->get('/distribution/edit', [ApiExampleController::class, 'editDistribution']);
	$router->post('/distribution/update', [ApiExampleController::class, 'updateDistribution']);
	$router->get('/distribution/delete', [ApiExampleController::class, 'deleteDistribution']);

	// Stock routes
	$router->get('/stock/list', [ApiExampleController::class, 'listStock']);

	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
	});
	
}, [ SecurityHeadersMiddleware::class ]);