<?php

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
