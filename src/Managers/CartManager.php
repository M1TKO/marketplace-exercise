<?php

namespace MarketplaceExercise\Managers;

class CartManager extends BaseSingletonManager
{
	private const CART_CACHE_KEY = 'cart';

	public function getProductsInCart(): array
	{
		return CacheManager::instance()->get(self::CART_CACHE_KEY, []);
	}

	public function addToCart(int $id_product): void
	{
		$cart = $this->getProductsInCart();
		$cart[$id_product] ??= 0;
		$cart[$id_product]++;

		CacheManager::instance()->set(self::CART_CACHE_KEY, $cart);
	}

	public function removeFromCart($id_product): void
	{
		$cart = $this->getProductsInCart();

		if (empty($cart[$id_product])) {
			return;
		}

		$cart[$id_product]--;

		// 0 or less quantity for the product, so we remove it from the cart
		if ($cart[$id_product] < 1) {
			unset($cart[$id_product]);
		}

		CacheManager::instance()->set(self::CART_CACHE_KEY, $cart);
	}

	public function flushCart(): void
	{
		CacheManager::instance()->delete(self::CART_CACHE_KEY);
	}
}
