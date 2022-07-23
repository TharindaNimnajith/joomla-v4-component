<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomla_v4_components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla_v4_components\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

/**
 * Controller for a single joomla_v4_component
 *
 * @since  1.0
 */
class Joomla_v4_componentController extends FormController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_JOOMLA_V4_COMPONENTS_JOOMLA_V4_COMPONENT';

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   1.0
	 */
	public function batch($model = null)
	{
		$this->checkToken();

		$model = $this->getModel('Joomla_v4_component', 'Administrator', array());

		// Preset the redirect
		$this->setRedirect(Route::_('index.php?option=com_joomla_v4_components&view=joomla_v4_components' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
