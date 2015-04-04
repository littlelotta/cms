<?php
/**
 * @link http://buildwithcraft.com/
 * @copyright Copyright (c) 2015 Pixel & Tonic, Inc.
 * @license http://buildwithcraft.com/license
 */

namespace craft\app\variables;

use craft\app\db\Query;
use craft\app\helpers\JsonHelper;

/**
 * Route functions.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class Routes
{
	// Public Methods
	// =========================================================================

	/**
	 * Returns the routes defined in the CP.
	 *
	 * @return array
	 */
	public function getDbRoutes()
	{
		$routes = [];

		$results = (new Query())
			->select(['id', 'locale', 'urlParts', 'template'])
			->from('{{%routes}}')
			->orderBy('sortOrder')
			->all();

		foreach ($results as $result)
		{
			$urlDisplayHtml = '';
			$urlParts = JsonHelper::decode($result['urlParts']);

			foreach ($urlParts as $part)
			{
				if (is_string($part))
				{
					$urlDisplayHtml .= $part;
				}
				else
				{
					$urlDisplayHtml .= '<span class="token" data-name="'.$part[0].'" data-value="'.$part[1].'"><span>'.$part[0].'</span></span>';
				}
			}

			$routes[] = [
				'id'             => $result['id'],
				'locale'         => $result['locale'],
				'urlDisplayHtml' => $urlDisplayHtml,
				'template'       => $result['template']
			];
		}

		return $routes;
	}
}
