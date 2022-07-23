<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomla_v4_components
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Joomla_v4_components\Administrator\Field\Modal;

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

/**
 * Supports a modal joomla_v4_component picker.
 *
 * @since  1.0
 */
class Joomla_v4_componentField extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   1.0
	 */
	protected $type = 'Modal_Joomla_v4_component';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		$allowClear  = ((string) $this->element['clear'] != 'false');
		$allowSelect = ((string) $this->element['select'] != 'false');

		// The active joomla_v4_component id field.
		$value = (int) $this->value > 0 ? (int) $this->value : '';

		// Create the modal id.
		$modalId = 'Joomla_v4_component_' . $this->id;

		// Add the modal field script to the document head.
		HTMLHelper::_('script', 'system/fields/modal-fields.min.js', array('version' => 'auto', 'relative' => true));

		// Script to proxy the select modal function to the modal-fields.js file.
		if ($allowSelect)
		{
			static $scriptSelect = null;

			if (is_null($scriptSelect))
			{
				$scriptSelect = array();
			}

			if (!isset($scriptSelect[$this->id]))
			{
				Factory::getDocument()->addScriptDeclaration("
				function jSelectJoomla_v4_component_" . $this->id . "(id, title, object) {
					window.processModalSelect('Joomla_v4_component', '" . $this->id . "', id, title, '', object);
				}
				"
				);

				$scriptSelect[$this->id] = true;
			}
		}

		// Setup variables for display.
		$linkJoomla_v4_components = 'index.php?option=com_joomla_v4_components&amp;view=joomla_v4_components&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken() . '=1';
		$linkJoomla_v4_component  = 'index.php?option=com_joomla_v4_components&amp;view=joomla_v4_component&amp;layout=modal&amp;tmpl=component&amp;' . Session::getFormToken() . '=1';
		$modalTitle               = Text::_('COM_JOOMLA_V4_COMPONENTS_CHANGE_JOOMLA_V4_COMPONENT');

		if (isset($this->element['language']))
		{
			$linkJoomla_v4_components .= '&amp;forcedLanguage=' . $this->element['language'];
			$linkJoomla_v4_component  .= '&amp;forcedLanguage=' . $this->element['language'];
			$modalTitle               .= ' &#8212; ' . $this->element['label'];
		}

		$urlSelect = $linkJoomla_v4_components . '&amp;function=jSelectJoomla_v4_component_' . $this->id;

		if ($value)
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('name'))
				->from($db->quoteName('#__joomla_v4_components_details'))
				->where($db->quoteName('id') . ' = ' . (int) $value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (\RuntimeException $e)
			{
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		$title = empty($title) ? Text::_('COM_JOOMLA_V4_COMPONENTS_SELECT_A_JOOMLA_V4_COMPONENT') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current joomla_v4_component display field.
		$html = '';

		if ($allowSelect || $allowNew || $allowEdit || $allowClear)
		{
			$html .= '<span class="input-group">';
		}

		$html .= '<input class="form-control" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35">';

		if ($allowSelect || $allowNew || $allowEdit || $allowClear)
		{
			$html .= '<span class="input-group-append">';
		}

		// Select joomla_v4_component button
		if ($allowSelect)
		{
			$html .= '<button'
				. ' class="btn btn-primary hasTooltip' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $this->id . '_select"'
				. ' data-toggle="modal"'
				. ' type="button"'
				. ' data-target="#ModalSelect' . $modalId . '"'
				. ' title="' . HTMLHelper::tooltipText('COM_JOOMLA_V4_COMPONENTS_CHANGE_JOOMLA_V4_COMPONENT') . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
				. '</button>';
		}

		// Clear joomla_v4_component button
		if ($allowClear)
		{
			$html .= '<button'
				. ' class="btn btn-secondary' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_clear"'
				. ' type="button"'
				. ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
				. '<span class="icon-remove" aria-hidden="true"></span>' . Text::_('JCLEAR')
				. '</button>';
		}

		if ($allowSelect || $allowNew || $allowEdit || $allowClear)
		{
			$html .= '</span></span>';
		}

		// Select joomla_v4_component modal
		if ($allowSelect)
		{
			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalSelect' . $modalId,
				array(
					'title'      => $modalTitle,
					'url'        => $urlSelect,
					'height'     => '400px',
					'width'      => '800px',
					'bodyHeight' => 70,
					'modalWidth' => 80,
					'footer'     => '<a role="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">'
						. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
				)
			);
		}

		// Note: class='required' for client side validation.
		$class = $this->required ? ' class="required modal-value"' : '';

		$html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' data-required="' . (int) $this->required . '" name="' . $this->name
			. '" data-text="' . htmlspecialchars(Text::_('COM_JOOMLA_V4_COMPONENTS_SELECT_A_JOOMLA_V4_COMPONENT', true), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '">';

		return $html;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   1.0
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_name', parent::getLabel());
	}
}
