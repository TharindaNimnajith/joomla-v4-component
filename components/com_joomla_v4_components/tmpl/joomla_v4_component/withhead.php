<?php

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

echo "<hr>Here you can show a headertext<hr>";

if ($this->item->params->get('show_name')) {
	if ($this->Params->get('show_joomla_v4_component_name_label')) {
		echo Text::_('COM_JOOMLA_V4_COMPONENTS_NAME') . $this->item->name;
	} else {
		echo $this->item->name;
	}
}

echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;
