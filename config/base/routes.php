<?php

return [
	['GET', '/auth', \Leftaro\App\Controller\AuthController::class, 'indexAction'],
	['POST', '/auth/login', \Leftaro\App\Controller\AuthController::class, 'loginAction'],

	['GET', '/dashboard', \Leftaro\App\Controller\DashboardController::class, 'indexAction'],
];