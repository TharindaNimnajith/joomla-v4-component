<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomla_v4_components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla_v4_components\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Joomla_v4_components Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_joomla_v4_components
 * @since       1.5
 */
abstract class Route
{
	/**
	 * Get the URL route for a joomla_v4_components from a joomla_v4_component ID, joomla_v4_components category ID and language
	 *
	 * @param   integer  $id        The id of the joomla_v4_components
	 * @param   integer  $catid     The id of the joomla_v4_components's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the joomla_v4_components
	 *
	 * @since   1.5
	 */
	public static function getJoomla_v4_componentsRoute($id, $catid, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_joomla_v4_components&view=joomla_v4_component&id=' . $id;

		if ($catid > 1)
		{
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	/**
	 * Get the URL route for a joomla_v4_components category from a joomla_v4_components category ID and language
	 *
	 * @param   mixed  $catid     The id of the joomla_v4_components's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the joomla_v4_components
	 *
	 * @since   1.5
	 */
	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof CategoryNode)
		{
			$id = $catid->id;
		}
		else
		{
			$id = (int) $catid;
		}

		if ($id < 1)
		{
			$link = '';
		}
		else
		{
			// Create the link
			$link = 'index.php?option=com_joomla_v4_components&view=category&id=' . $id;

			if ($language && $language !== '*' && Multilanguage::isEnabled())
			{
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}
}
