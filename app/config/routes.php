<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// Public routes (no authentication required)
$router->match('/', 'AuthController::Login', 'GET|POST');
$router->match('/register', 'AuthController::register', 'GET|POST');
$router->get('/logout', 'AuthController::logout');
$router->get('/captcha', 'AuthController::generate_captcha');

// Protected routes (require authentication)
$router->match('/schedule', 'CollectionController::schedulePickup', 'GET|POST');
$router->match('/track-pickup', 'CollectionController::trackPickup', 'GET|POST');
$router->post('/schedule/cancel', 'CollectionController::cancel_status');
$router->get('/generate-report', 'CollectionController::generateReport');

$router->get('/dashboard', 'DashboardController::index');
$router->get('/trends', 'AnalyticsController::getTrends');
$router->get('/materials', 'EducationController::getMaterials');
$router->get('/rewards', 'RewardsController::getRewards');
$router->get('/claim-reward/(:num)', 'RewardsController::claimReward/$1');

// Admin routes (require admin authentication)
$router->get('/admin', 'AdminController::index');

// Admin Users Management
$router->get('/admin/users', 'AdminController::users');
$router->match('/admin/users/edit/(:num)', 'AdminController::editUser/$1', 'GET|POST');
$router->get('/admin/users/delete/(:num)', 'AdminController::deleteUser/$1');

// Admin Collections Management
$router->get('/admin/collections', 'AdminController::collections');
$router->post('/admin/update_collection_status', 'AdminController::update_collection_status');
$router->get('/admin/collections/view/(:num)', 'AdminController::viewCollection/$1');
$router->get('/admin/export_report', 'AdminController::export_report');

// Admin Analytics
$router->get('/admin/analytics', 'AdminController::analytics');

// Admin Rewards Management
$router->get('/admin/rewards', 'AdminController::rewards');
$router->post('/admin/rewards/add', 'AdminController::add_reward');
$router->get('/admin/rewards/get/(:num)', 'AdminController::get_reward/$1');
$router->post('/admin/rewards/update/(:num)', 'AdminController::update_reward/$1');
$router->get('/admin/rewards/delete/(:num)', 'AdminController::delete_reward/$1');

// API routes for admin dashboard
$router->get('/admin/api/stats', 'AdminController::getStats');
$router->get('/admin/api/recent_collections', 'AdminController::getRecentCollections');
$router->get('/admin/api/recent_users', 'AdminController::getRecentUsers');
