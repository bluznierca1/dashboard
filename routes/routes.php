<?php

/**
 * Register routes.
 * Router::registerPathWithController( {PATH}, {CONTROLLER}, {METHOD}
 * {PATH} -> expected URL
 * {Controller} -> name of controller handling request for given URL
 * {METHOD} -> name of method inside controller to be used for given URL
 */

use Dashboard\Router\Router;

// Controller
Router::registerPathWithController('notFound', 'Controller', 'return404');
Router::registerPathWithController('fillDb/customers', 'Controller', 'fillCustomersTable');
Router::registerPathWithController('fillDb/orders', 'Controller', 'fillOrdersTable');
Router::registerPathWithController('fillDb/orderitems', 'Controller', 'fillOrderItemsTable');
Router::registerPathWithController('fillDb/refill', 'Controller', 'refillDb');


// DashboardController
Router::registerPathWithController('index', 'DashboardController', 'index');
Router::registerPathWithController('admin/panel', 'DashboardController', 'hello');

// AjaxController
Router::registerPathWithController('hello', 'AjaxController', 'hello');
Router::registerPathWithController('handle-ajax', 'AjaxController', 'handleAjax');
