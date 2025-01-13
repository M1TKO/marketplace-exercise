<?php

namespace MarketplaceExercise\Controllers;

use Exception;
use MarketplaceExercise\Managers\CartManager;
use MarketplaceExercise\Managers\ProductsManager;
use MarketplaceExercise\Managers\PurchaseManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Throwable;

class PurchaseController extends BaseController
{
	/**
	 * @throws Throwable
	 */
	public function actionReceipt(Request $request, Response $response, $args = []): Response
	{
		$id_sale = $args['id_sale'] ?? null;
		$data = [];

		if (!empty($id_sale)) {
			$purchase_manager = new PurchaseManager();
			$data = $purchase_manager->getSaleDataForDisplay($id_sale);
		}

		return $this->getRenderer()->render($response, 'receipt.php', $data);
	}

	/**
	 * @throws Throwable
	 */
	public function ajaxActionProcessPurchase(Request $request, Response $response): Response
	{
		try {
			$cart = CartManager::instance()->getProductsInCart();

			if (empty($cart)) {
				throw new Exception('Empty cart');
			}

			$cart_items_summary = ProductsManager::instance()->calculateProductQuantitiesSummary($cart)['items'] ?? [];

			if (empty($cart_items_summary)) {
				throw new Exception('Error occurred while processing the cart items');
			}

			$purchase_manager = new PurchaseManager();
			$id_sale = $purchase_manager->purchaseProducts($cart_items_summary);

			if (is_null($id_sale)) {
				throw new Exception('Failed to purchase the items from cart');
			}

			CartManager::instance()->flushCart();

			$response->getBody()->write(json_encode([
				'success' => true,
				'id_sale' => $id_sale,
			]));
		} catch (Throwable $e) {
			$response->getBody()->write(json_encode([
				'success' => false,
				'message' => $e->getMessage(),
			]));
		}

		return $response->withHeader('Content-Type', 'application/json');
	}
}
