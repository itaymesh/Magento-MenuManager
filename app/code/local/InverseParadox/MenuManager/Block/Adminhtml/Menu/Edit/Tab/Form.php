<?php

class InverseParadox_MenuManager_Block_Adminhtml_Menu_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('menu_');
        $form->setFieldNameSuffix('menu');

		$this->setForm($form);

		$fieldset = $form->addFieldset('menu_general', array('legend'=> $this->__('General Information')));

		$fieldset->addField('title', 'text', array(
			'name' 		=> 'title',
			'label' 	=> $this->__('Title'),
			'title' 	=> $this->__('Title'),
			'required'	=> true,
			'class'		=> 'required-entry',
		));

		$fieldset->addField('code', 'text', array(
			'name' 		=> 'code',
			'label' 	=> $this->__('Code'),
			'title' 	=> $this->__('Code'),
			'note'		=> $this->__('This is a unique identifier that is used to inject the menu via XML'),
			'required'	=> true,
			'class'		=> 'required-entry validate-code',
		));

		$fieldset->addField('is_enabled', 'select', array(
			'name' => 'is_enabled',
			'title' => $this->__('Enabled'),
			'label' => $this->__('Enabled'),
			'required' => true,
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));

		$fieldset->addField('store_id', 'select', array(
			'name'		=> 'store_id',
			'label'		=> $this->__('Store'),
			'title'		=> $this->__('Store'),
			'required'	=> true,
			'class'		=> 'required-entry',
			'values'	=> $this->_getStores()
		));


		if ($menu = Mage::registry('ipmenumanager_menu')) {
			$form->setValues($menu->getData());
		}

		return parent::_prepareForm();
	}

	/**
	 * Retrieve an array of all of the stores
	 *
	 * @return array
	 */
	protected function _getStores()
	{
		$stores = Mage::getResourceModel('core/store_collection');
		$options = array(0 => $this->__('Global'));

		foreach($stores as $store) {
			$options[$store->getId()] = $store->getWebsite()->getName() . ': ' . $store->getName();
		}

		return $options;
	}
}
