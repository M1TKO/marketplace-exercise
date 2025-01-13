<?php

namespace MarketplaceExercise\Controllers;

use DB;
use MarketplaceExercise\Managers\ProductsManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Throwable;

class ProductsController extends BaseController
{
	/**
	 * @throws Throwable
	 */
	public function actionBrowseProducts(Request $request, Response $response): Response
	{
		return $this->getRenderer()->render($response, 'products.php', [
			'products_list' => ProductsManager::instance()->getProducts(),
			'product_bundles' => ProductsManager::instance()->getProductBundles(),
		]);
	}

	/**
	 * @throws Throwable
	 */
	public function actionManageProductsAndBundles(Request $request, Response $response): Response
	{
		return $this->getRenderer()->render($response, 'edit_products.php', [
			'products_list' => ProductsManager::instance()->getProducts(),
			'product_bundles' => ProductsManager::instance()->getProductBundles(),
		]);
	}

	/**
	 * @throws Throwable
	 */
	public function actionEditProduct(Request $request, Response $response, $args = []): Response
	{
		$id_product = (int)($args['id_product'] ?? 0);
		$product = null;

		// no such product
		if ($id_product > 0) {
			$product = ProductsManager::instance()->getProduct($id_product);

			if (empty($product)) {
				return $response->withHeader('Location', "/edit-products")->withStatus(302);
			}
		}

		return $this->getRenderer()->render($response, 'edit_product.php', [
			'product' => $product ?? [],
		]);
	}

	/**
	 * @throws Throwable
	 */
	public function actionSaveProduct(Request $request, Response $response, $args = []): Response
	{
		$post_data = $request->getParsedBody();
		$id_product = (int)($post_data['id_product'] ?? 0);

		try {
			$id_product = ProductsManager::instance()->createOrUpdateProduct($post_data);
			$show_save_success = true;
		} catch (Throwable $e) {
			$error_message = $e->getMessage();
		}

		return $this->getRenderer()->render($response, 'edit_product.php', [
			'product' => ProductsManager::instance()->getProduct($id_product) ?? [],
			'error_message' => $error_message ?? '',
			'show_save_success' => $show_save_success ?? false,
		]);
	}

	/**
	 * @throws Throwable
	 */
	public function actionEditBundle(Request $request, Response $response, $args = []): Response
	{
		$id_product = (int)($args['id_product'] ?? 0);

		return $this->getRenderer()->render($response, 'edit_bundle.php', [
			'bundle' => ProductsManager::instance()->getBundleForProduct($id_product) ?? [],
			'product' => ProductsManager::instance()->getProduct($id_product) ?? [],
		]);
	}

	/**
	 * @throws Throwable
	 */
	public function actionSaveBundle(Request $request, Response $response, $args = []): Response
	{
		$post_data = $request->getParsedBody();
		$id_product = (int)($post_data['id_product'] ?? 0);

		try {
			ProductsManager::instance()->createOrUpdateBundle($post_data);
			$show_save_success = true;
		} catch (Throwable $e) {
			$error_message = $e->getMessage();
		}

		return $this->getRenderer()->render($response, 'edit_bundle.php', [
			'bundle' => ProductsManager::instance()->getBundleForProduct($id_product) ?? [],
			'product' => ProductsManager::instance()->getProduct($id_product) ?? [],
			'error_message' => $error_message ?? '',
			'show_save_success' => $show_save_success ?? false,
		]);
	}

	/**
	 * @throws Throwable
	 */
	public function ajaxActionDeleteProduct(Request $request, Response $response, $args = []): Response
	{
		$id_product = (int)($args['id_product'] ?? 0);

		if (!empty($id_product)) {
			DB::delete('products', ['id_product' => $id_product]);
			DB::delete('product_bundles', ['id_product' => $id_product]);
		}

		$response->getBody()->write(json_encode(['success' => true]));

		return $response->withHeader('Content-Type', 'application/json');
	}

	/**
	 * @throws Throwable
	 */
	public function ajaxActionDeleteBundle(Request $request, Response $response, $args = []): Response
	{
		$id_product = (int)($args['id_product'] ?? 0);

		if (!empty($id_product)) {
			DB::delete('product_bundles', ['id_product' => $id_product]);
		}

		$response->getBody()->write(json_encode(['success' => true]));

		return $response->withHeader('Content-Type', 'application/json');
	}
}
