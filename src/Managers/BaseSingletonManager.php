<?php

namespace MarketplaceExercise\Managers;

class BaseSingletonManager
{
	/** @var static Store the class to be used as singleton */
	private static self $_instance;

	/**
	 * @return static
	 */
	public static function instance(): static
	{
		return $_instance ??= new static();
	}
}
