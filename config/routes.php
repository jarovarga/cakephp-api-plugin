<?php

declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/** @var RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);
$routes->plugin('Api', ['path' => '/api'], function (RouteBuilder $builder) {
    $builder->prefix('V1', ['path' => '/v1'], function (RouteBuilder $builder) {
        $builder->fallbacks();
    });
});
