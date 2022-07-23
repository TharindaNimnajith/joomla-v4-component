<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomla_v4_components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla_v4_components\Site\View\Joomla_v4_component;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\Registry\Registry;

/**
 * HTML Joomla_v4_components View class for the Joomla_v4_component component
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 * @since  1.0.0
	 */
	protected $params = null;

	/**
	 * The item model state
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  1.0.0
	 */
	protected $state;

	/**
	 * The item object details
	 *
	 * @var    \JObject
	 * @since  1.0.0
	 */
	protected $item;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$item       = $this->item = $this->get('Item');
		$state      = $this->State = $this->get('State');
		$params     = $this->Params = $state->get('params');
		$itemparams = new Registry(json_decode($item->params));

		$temp = clone $params;
		$temp->merge($itemparams);
		$item->params = $temp;

		Factory::getApplication()->triggerEvent('onContentPrepare', array('com_joomla_v4_components.joomla_v4_component', &$item));

		// Store the events for later
		$item->event                    = new \stdClass;
		$results                        = Factory::getApplication()->triggerEvent('onContentAfterTitle', array('com_joomla_v4_components.joomla_v4_component', &$item, &$item->params));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results                           = Factory::getApplication()->triggerEvent('onContentBeforeDisplay', array('com_joomla_v4_components.joomla_v4_component', &$item, &$item->params));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results                          = Factory::getApplication()->triggerEvent('onContentAfterDisplay', array('com_joomla_v4_components.joomla_v4_component', &$item, &$item->params));
		$item->event->afterDisplayContent = trim(implode("\n", $results));

		return parent::display($tpl);
	}
}
