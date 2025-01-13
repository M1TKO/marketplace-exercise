<?php

namespace MarketplaceExercise\Managers;

class CacheManager extends BaseSingletonManager
{
	/** @var string In what key the cache is stored in the session */
	private const CACHE_SESSION_KEY = 'cache';

	/**
	 * Get cached data by key
	 */
	public function get(string $key, mixed $default = null): mixed
	{
		return $_SESSION[self::CACHE_SESSION_KEY][$key] ?? $default;
	}

	/**
	 * Set new or update existing data in the cache
	 */
	public function set(string $key, mixed $value): void
	{
		$_SESSION[self::CACHE_SESSION_KEY][$key] = $value;
	}

	/**
	 * Delete an item from the cache by its unique key.
	 */
	public function delete(string $key): void
	{
		unset($_SESSION[self::CACHE_SESSION_KEY][$key]);
	}

	/**
	 * Wipes clean the entire cache's keys.
	 */
	public function clear(): void
	{
		$_SESSION[self::CACHE_SESSION_KEY] = [];
	}

	/**
	 * Determines whether an item is present in the cache.
	 */
	public function has(string $key): bool
	{
		return isset($_SESSION[self::CACHE_SESSION_KEY][$key]);
	}
}
