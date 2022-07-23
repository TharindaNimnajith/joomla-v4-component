<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomla_v4_components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla_v4_components\Administrator\Service\HTML;

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/**
 * Joomla_v4_component HTML class.
 *
 * @since  1.0.0
 */
class AdministratorService
{
	/**
	 * Get the associated language flags
	 *
	 * @param   integer  $joomla_v4_componentid  The item id to search associations
	 *
	 * @return  string  The language HTML
	 *
	 * @throws  Exception
	 */
	public function association($joomla_v4_componentid)
	{
		// Defaults
		$html = '';

		// Get the associations
		if ($associations = Associations::getAssociations('com_joomla_v4_components', '#__joomla_v4_components_details', 'com_joomla_v4_components.item', $joomla_v4_componentid, 'id', null))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			// Get the associated joomla_v4_component items
			$db = Factory::getDbo();
			$query = $db->getQuery(true)
				->select('c.id, c.name as title')
				->select('l.sef as lang_sef, lang_code')
				->from('#__joomla_v4_components_details as c')
				->select('cat.title as category_title')
				->join('LEFT', '#__categories as cat ON cat.id=c.catid')
				->where('c.id IN (' . implode(',', array_values($associations)) . ')')
				->where('c.id != ' . $joomla_v4_componentid)
				->join('LEFT', '#__languages as l ON c.language=l.lang_code')
				->select('l.image')
				->select('l.title as language_title');
			$db->setQuery($query);

			try
			{
				$items = $db->loadObjectList('id');
			}
			catch (\RuntimeException $e)
			{
				throw new \Exception($e->getMessage(), 500, $e);
			}

			if ($items)
			{
				foreach ($items as &$item)
				{
					$text = strtoupper($item->lang_sef);
					$url = Route::_('index.php?option=com_contact&task=contact.edit&id=' . (int) $item->id);
					$tooltip = '<strong>' . htmlspecialchars($item->language_title, ENT_QUOTES, 'UTF-8') . '</strong><br>'
						. htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') . '<br>' . Text::sprintf('JCATEGORY_SPRINTF', $item->category_title);
					$classes = 'badge badge-secondary';

					$item->link = '<a href="' . $url . '" title="' . $item->language_title . '" class="' . $classes . '">' . $text . '</a>'
						. '<div role="tooltip" id="tip' . (int) $item->id . '">' . $tooltip . '</div>';
				}
			}

			$html = LayoutHelper::render('joomla.content.associations', $items);
		}

		return $html;
	}
}
