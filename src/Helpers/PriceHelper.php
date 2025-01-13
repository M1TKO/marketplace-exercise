<?php

namespace MarketplaceExercise\Helpers;

class PriceHelper
{
	public static function getDisplayPrice(int $price_cents, $add_currency_symbol = true): string
	{
		$price = number_format($price_cents / 100, 2, '.', '');

		return ($add_currency_symbol ? '$' : '') . $price;
	}
}
