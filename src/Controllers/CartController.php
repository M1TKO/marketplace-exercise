<?php

namespace MarketplaceExercise\Controllers;

use MarketplaceExercise\Managers\CartManager;
use MarketplaceExercise\Managers\ProductsManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class CartController extends BaseController
{
	public function actionCart(Request $request, Response $response): Response
	{
		$cart = CartManager::instance()->getProductsInCart();
		$cart_summary = ProductsManager::instance()->calculateProductQuantitiesSummary($cart);

		return $this->getRenderer()->render($response, 'cart.php', $cart_summary);
	}

	public function ajaxActionAddToCard(Request $request, Response $response): Response
	{
		$id_product = $request->getParsedBody()['id_product'] ?? null;
		$is_product_exist = !empty($id_product) && ProductsManager::instance()->isProductExists($id_product);

		if ($is_product_exist) {
			CartManager::instance()->addToCart($id_product);
		}

		$response->getBody()->write(json_encode([
			'success' => $is_product_exist,
			'cart'    => CartManager::instance()->getProductsInCart(),
		]));

		return $response->withHeader('Content-Type', 'application/json');
	}

	public function ajaxActionRemoveFromCart(Request $request, Response $response, $args): Response
	{
		$id_product = $request->getParsedBody()['id_product'] ?? null;

		if (!empty($id_product)) {
			CartManager::instance()->removeFromCart($id_product);
		}

		$response->getBody()->write(json_encode([
			'success' => true,
			'cart'    => CartManager::instance()->getProductsInCart(),
		]));

		return $response->withHeader('Content-Type', 'application/json');
	}
}
