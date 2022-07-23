<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomla-v4-components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla-v4-components\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;

/**
 * Joomla-v4-component component helper.
 *
 * @since  1.0.0
 */
class Joomla-v4-componentHelper extends ContentHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public static function addSubmenu($vName)
	{
		if (ComponentHelper::isEnabled('com_fields') && ComponentHelper::getParams('com_joomla-v4-components')->get('custom_fields_enable', '1'))
		{
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELDS'),
				'index.php?option=com_fields&context=com_joomla-v4-components.joomla-v4-component',
				$vName == 'fields.fields'
			);
			\JHtmlSidebar::addEntry(
				Text::_('JGLOBAL_FIELD_GROUPS'),
				'index.php?option=com_fields&view=groups&context=com_joomla-v4-components.joomla-v4-component',
				$vName == 'fields.groups'
			);
		}
	}
}
