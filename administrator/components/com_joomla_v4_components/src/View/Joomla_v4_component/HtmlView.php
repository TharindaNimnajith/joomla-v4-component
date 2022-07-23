<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomla_v4_components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla_v4_components\Administrator\View\Joomla_v4_component;

defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Joomla_v4_components\Administrator\Model\Joomla_v4_componentModel;

/**
 * View to edit a joomla_v4_component.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The Form object
	 *
	 * @var    Form
	 * @since  1.0.0
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var    object
	 * @since  1.0.0
	 */
	protected $item;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @throws  Exception
	 * @since   1.0.0
	 */
	public function display($tpl = null)
	{
		/** @var Joomla_v4_componentModel $model */
		$model      = $this->getModel();
		$this->item = $model->getItem();

		// If we are forcing a language in modal (used for associations).
		if ($this->getLayout() === 'modal'
			&& $forcedLanguage = Factory::getApplication()->input->get('forcedLanguage', ''))
		{
			// Set the language field to the forcedLanguage and disable changing it.
			$this->form->setValue('language', null, $forcedLanguage);
			$this->form->setFieldAttribute('language', 'readonly', 'true');

			// Only allow to select categories with All language or with the forced language.
			$this->form->setFieldAttribute('catid', 'language', '*,' . $forcedLanguage);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   1.0.0
	 */
	private function addToolbar(): void
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$user   = Factory::getUser();
		$userId = $user->id;
		$isNew  = ($this->item->id == 0);

		ToolbarHelper::title($isNew ? Text::_('COM_JOOMLA_V4_COMPONENTS_MANAGER_JOOMLA_V4_COMPONENT_NEW')
			: Text::_('COM_JOOMLA_V4_COMPONENTS_MANAGER_JOOMLA_V4_COMPONENT_EDIT'),
			'address joomla_v4_component');

		// Since we don't track these assets at the item level, use the category id.
		$canDo = ContentHelper::getActions('com_joomla_v4_components', 'category', $this->item->catid);

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($isNew && (count($user->getAuthorisedCategories('com_joomla_v4_components', 'core.create')) > 0))
			{
				ToolbarHelper::apply('joomla_v4_component.apply');

				ToolbarHelper::saveGroup(
					[
						['save', 'joomla_v4_component.save'],
						['save2new', 'joomla_v4_component.save2new'],
					],
					'btn-success'
				);
			}

			ToolbarHelper::cancel('joomla_v4_component.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit')
				|| ($canDo->get('core.edit.own')
					&& $this->item->created_by == $userId);

			$toolbarButtons = [];

			// Can't save the record if it's not editable
			if ($itemEditable)
			{
				ToolbarHelper::apply('joomla_v4_component.apply');

				$toolbarButtons[] = ['save', 'joomla_v4_component.save'];

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					$toolbarButtons[] = ['save2new', 'joomla_v4_component.save2new'];
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2copy', 'joomla_v4_component.save2copy'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			if (Associations::isEnabled() && ComponentHelper::isEnabled('com_associations'))
			{
				ToolbarHelper::custom('joomla_v4_component.editAssociations', 'contract', 'contract',
					'JTOOLBAR_ASSOCIATIONS', false, false);
			}

			ToolbarHelper::cancel('joomla_v4_component.cancel', 'JTOOLBAR_CLOSE');
		}

		ToolbarHelper::divider();
		ToolbarHelper::help('', false, 'http://joomla.org');
	}
}
