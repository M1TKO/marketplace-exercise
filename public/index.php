<?php

use MarketplaceExercise\Controllers\CartController;
use MarketplaceExercise\Controllers\ProductsController;
use MarketplaceExercise\Controllers\PurchaseController;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../config/config.php';

$displayErrorDetails = true;

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->get('/',                  [ProductsController::class, 'actionBrowseProducts']);
$app->get('/cart',              [CartController::class,     'actionCart']);
$app->get('/receipt/{id_sale}', [PurchaseController::class, 'actionReceipt']);
$app->post('/add-to-cart',      [CartController::class,     'ajaxActionAddToCard']);
$app->post('/remove-from-cart', [CartController::class,     'ajaxActionRemoveFromCart']);
$app->post('/process-purchase', [PurchaseController::class, 'ajaxActionProcessPurchase']);

// Edit products and bundles page
$app->get('/edit-products',              [ProductsController::class, 'actionManageProductsAndBundles']);

$app->get('/new-product',                [ProductsController::class, 'actionEditProduct']);
$app->post('/new-product',               [ProductsController::class, 'actionSaveProduct']);

$app->get('/edit-product/{id_product}',  [ProductsController::class,  'actionEditProduct']);
$app->post('/edit-product/{id_product}', [ProductsController::class, 'actionSaveProduct']);

$app->get('/edit-bundle/{id_product}',  [ProductsController::class,   'actionEditBundle']);
$app->post('/edit-bundle/{id_product}', [ProductsController::class,   'actionSaveBundle']);

$app->post('/delete-product/{id_product}', [ProductsController::class, 'ajaxActionDeleteProduct']);
$app->post('/delete-bundle/{id_product}',  [ProductsController::class, 'ajaxActionDeleteBundle']);

// Add Error Handling Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);

$app->run();

DB::disconnect();
