<?php

return [
	'/' => [
		'handler'=> 'App\Controller\DefaultController::index',
		'middleware' => 'App\Middleware\Dummy'
	],
	'/<locale:locale>/dummy/<str:name>' => [
		'handler'=> 'App\Controller\DefaultController::dummy',
	],
	'/dummy/<str:name>/<int:id>' => [
		'host' => '<str:name>.example.com',
		'scheme' => ['http', 'https'],
		'handler'=> 'App\Controller\DefaultController::dummy',
	]
];