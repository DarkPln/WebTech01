<?php

// ── Constants ────────────────────────────────────────────────────────────────
define('BASE_PATH', __DIR__ . '/');
define('BASE_URL',  '/WebTech01');
define('VIEW_PATH', BASE_PATH . 'app/views/');

// ── Session ──────────────────────────────────────────────────────────────────
session_start();

// ── Core autoloader ──────────────────────────────────────────────────────────
foreach (['Database', 'Model', 'Controller', 'Router'] as $class) {
    require BASE_PATH . "core/{$class}.php";
}

// ── Models ───────────────────────────────────────────────────────────────────
foreach (['Car', 'User', 'Listing', 'Booking', 'Message', 'Favorite'] as $class) {
    require BASE_PATH . "app/models/{$class}.php";
}

// ── Controllers ──────────────────────────────────────────────────────────────
foreach ([
    'HomeController', 'AuthController', 'CarController',
    'ListingController', 'BookingController', 'AdminController',
    'MessageController', 'FavoriteController', 'PageController',
    'MerklisteController',
] as $class) {
    require BASE_PATH . "app/controllers/{$class}.php";
}

// ── Static assets pass-through (images, CSS, JS) ─────────────────────────────
// .htaccess handles this — PHP never sees requests for real files/dirs.

// ── Router ───────────────────────────────────────────────────────────────────
$router = new Router();

// Pages
$router->get('/',                [HomeController::class,     'index']);
$router->get('/cars',            [CarController::class,      'list']);
$router->get('/cars/detail',     [CarController::class,      'detail']);
$router->get('/cars/pdf',        [CarController::class,      'pdf']);
$router->get('/auth/login',      [AuthController::class,     'loginView']);
$router->get('/auth/register',   [AuthController::class,     'registerView']);
$router->get('/auth/logout',     [AuthController::class,     'logoutView']);
$router->get('/listings/sell',   [ListingController::class,  'sellView']);
$router->get('/user',            [AuthController::class,     'userView']);
$router->get('/admin',           [AuthController::class,     'adminView']);
$router->get('/bookings',        [BookingController::class,  'index']);
$router->get('/merkliste',       [MerklisteController::class,'index']);
$router->post('/merkliste/remove', [MerklisteController::class,'remove']);

// Static pages — must be registered after all specific routes
$router->get('/{page}', [PageController::class, 'show']);

// Auth API
$router->get('/api/auth/status',    [AuthController::class, 'status']);
$router->post('/api/auth/login',    [AuthController::class, 'login']);
$router->post('/api/auth/logout',   [AuthController::class, 'logout']);
$router->post('/api/auth/register', [AuthController::class, 'register']);
$router->post('/api/auth/update',   [AuthController::class, 'update']);

// Listings API
$router->post('/api/listings/create',   [ListingController::class, 'create']);
$router->get('/api/listings/list',      [ListingController::class, 'list']);
$router->get('/api/listings/list-html', [ListingController::class, 'listHtml']);

// Bookings API
$router->post('/api/bookings/create',   [BookingController::class, 'create']);
$router->post('/api/bookings/cancel',   [BookingController::class, 'cancel']);
$router->get('/api/bookings/list',      [BookingController::class, 'listJson']);
$router->get('/api/bookings/list-html', [BookingController::class, 'listHtml']);

// Messages API
$router->get('/api/messages/list-html',   [MessageController::class, 'listHtml']);
$router->post('/api/messages/mark-read',  [MessageController::class, 'markRead']);
$router->get('/api/messages/unread-count',[MessageController::class, 'unreadCount']);

// Favorites API
$router->post('/api/favorites/toggle', [FavoriteController::class, 'toggle']);
$router->get('/api/favorites/get',     [FavoriteController::class, 'get']);

// Admin API
$router->get('/api/admin/listings',      [AdminController::class, 'getListings']);
$router->post('/api/admin/listings',     [AdminController::class, 'updateListing']);
$router->get('/api/admin/listings-html', [AdminController::class, 'listingsHtml']);
$router->get('/api/admin/orders',        [AdminController::class, 'getOrders']);
$router->post('/api/admin/orders',       [AdminController::class, 'updateOrder']);
$router->get('/api/admin/orders-html',   [AdminController::class, 'ordersHtml']);
$router->get('/api/admin/users',         [AdminController::class, 'getUsers']);
$router->post('/api/admin/users',        [AdminController::class, 'updateUser']);
$router->get('/api/admin/users-html',    [AdminController::class, 'usersHtml']);
$router->post('/api/admin/cars',         [AdminController::class, 'deleteCar']);
$router->get('/api/admin/cars-html',     [AdminController::class, 'carsHtml']);

$router->dispatch();
