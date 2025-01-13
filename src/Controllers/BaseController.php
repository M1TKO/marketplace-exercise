<?php

namespace MarketplaceExercise\Controllers;

use Slim\Views\PhpRenderer;

class BaseController
{
	private PhpRenderer $_renderer;

	protected function getRenderer(): PhpRenderer
	{
		return $this->_renderer ??= new PhpRenderer(VIEW_PAGE_DIR, [], GENERAL_VIEW_TEMPLATE);
	}
}
