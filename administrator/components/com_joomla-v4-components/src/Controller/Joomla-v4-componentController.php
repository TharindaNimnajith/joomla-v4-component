<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomla-v4-components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla-v4-components\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

/**
 * Controller for a single joomla-v4-component
 *
 * @since  1.0
 */
class Joomla-v4-componentController extends FormController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $text_prefix = 'COM_JOOMLA-V4-COMPONENTS_JOOMLA-V4-COMPONENT';

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

		$model = $this->getModel('Joomla-v4-component', 'Administrator', array());

		// Preset the redirect
		$this->setRedirect(Route::_('index.php?option=com_joomla-v4-components&view=joomla-v4-components' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
