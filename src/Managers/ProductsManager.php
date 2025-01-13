<?php

namespace MarketplaceExercise\Managers;

use DB;
use Exception;

class ProductsManager extends BaseSingletonManager
{
	private array $_products;
	private array $_product_bundles;

	public function getProducts(): array
	{
		if (isset($this->_products)) {
			return $this->_products;
		}

		$products = DB::query("SELECT * FROM products") ?? [];

		return $this->_products = $this->indexArrayByColumn('id_product', $products);
	}

	public function getProduct(int $id_product): ?array
	{
		if (isset($this->_products)) {
			return $this->getProducts()[$id_product] ?? null;
		}

		$product = DB::query("SELECT * FROM products WHERE id_product = %i", $id_product);

		if (empty($product)) {
			return null;
		}

		return $product[0] ?? null;
	}

	public function getBundleForProduct(int $id_product): ?array
	{
		if (isset($this->_product_bundles)) {
			return $this->getProductBundles()[$id_product] ?? null;
		}

		$bundle = DB::query("SELECT * FROM product_bundles WHERE id_product = %i", $id_product);

		if (empty($bundle)) {
			return null;
		}

		return $bundle[0] ?? null;
	}

	public function getProductBundles(): array
	{
		if (isset($this->_product_bundles)) {
			return $this->_product_bundles;
		}

		$product_bundles = DB::query("SELECT * FROM product_bundles") ?? [];

		return $this->_product_bundles = $this->indexArrayByColumn('id_product', $product_bundles);
	}

	private function indexArrayByColumn(string $column, array $array): array
	{
		$new_array = [];

		foreach ($array as $product) {
			$new_array[$product[$column]] = $product;
		}

		return $new_array;
	}

	public function isProductExists(string $id_product): bool
	{
		return !empty($this->getProduct($id_product));
	}

	/**
	 * @param array $products_quantities array with [{id_product} => {product_quantity}, ...]
	 * @return array
	 */
	public function calculateProductQuantitiesSummary(array $products_quantities): array
	{
		$summary = [];
		$total_price = 0;
		$total_price_no_discount = 0;
		$products = $this->getProducts();
		$product_bundles = $this->getProductBundles();

		foreach ($products_quantities as $id_product => $quantity) {
			if (!isset($products[$id_product])) {
				continue;
			}

			$product_price = $products[$id_product]['price'];
			$bundle = $product_bundles[$id_product] ?? null;

			if ($bundle && $quantity >= $bundle['product_quantity']) {
				$num_bundles = intdiv($quantity, $bundle['product_quantity']);
				$remaining_units = $quantity % $bundle['product_quantity'];
				$bundle_total = $num_bundles * $bundle['bundle_price'];
				$remaining_total = $remaining_units * $product_price;

				$line_price = $bundle_total + $remaining_total;
				$line_price_no_discount = $quantity * $product_price;
			} else {
				// No bundle, calculate as regular price
				$line_price = $quantity * $product_price;
				$line_price_no_discount = $line_price;
			}

			$summary[] = [
				'id_product'             => $id_product,
				'sku'                    => $products[$id_product]['sku'],
				'single_item_price'      => $product_price,
				'product_quantity'       => $quantity,
				'line_price'             => $line_price,
				'line_price_no_discount' => $line_price_no_discount,
			];

			// Add to total prices
			$total_price += $line_price;
			$total_price_no_discount += $line_price_no_discount;
		}

		return [
			'items'                   => $summary,
			'total_price'             => $total_price,
			'total_price_no_discount' => $total_price_no_discount,
		];
	}

	/**
	 * @throws Exception
	 */
	public function createOrUpdateBundle(array $bundle): void
	{
		$id_product = (int)($bundle['id_product'] ?? 0);

		if ($id_product <= 0 || !$this->isProductExists($id_product)) {
			throw new Exception('Trying to create bundle for not existing product');
		}

		if (($bundle['product_quantity'] ?? 0) < 2) {
			throw new Exception('Invalid product quantity provided');
		}

		if (($bundle['bundle_price'] ?? 0) < 1) {
			throw new Exception('Invalid price provided');
		}

		$insert_data = [
			'id_product'       => (int)$bundle['id_product'],
			'product_quantity' => (int)round($bundle['product_quantity']),
			'bundle_price'     => (int)round($bundle['bundle_price']),
		];

		if (empty($this->getBundleForProduct($insert_data['id_product']))) {
			// create new
			DB::insert('product_bundles', $insert_data);
		} else {
			DB::update('product_bundles', $insert_data, ['id_product' => $insert_data['id_product']]);
		}
	}

	/**
	 * @throws Exception
	 */
	public function createOrUpdateProduct(array $product): int
	{
		$id_product = empty($product['id_product']) || $product['id_product'] <= 0 ? null : (int)$product['id_product'];
		$price = $product['price'] ?? 0;

		if (($product['price'] ?? 0) < 1) {
			throw new Exception('Invalid price provided');
		}

		// End early for existing products
		if (!is_null($id_product) && $this->isProductExists($id_product)) {
			DB::update('products', ['price' => $price], ['id_product' => $id_product]);

			return $id_product;
		}

		$sku = strtoupper($product['sku'] ?? '');
		if (empty($sku) || strlen($sku) > 2) {
			throw new Exception('Invalid SKU provided');
		}

		$sku_search_result = DB::query('SELECT * FROM products WHERE sku = %s', $sku);
		if (!empty($sku_search_result)) {
			throw new Exception('This SKU already exists');
		}

		// create new product
		DB::insert('products', [
			'sku'        => $sku,
			'price'      => $price,
		]);

		return (int)DB::insertId();
	}
}
