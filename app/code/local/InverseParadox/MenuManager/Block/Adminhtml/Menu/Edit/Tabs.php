<?php

class InverseParadox_MenuManager_Block_Adminhtml_Menu_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('ipmenumanager_menu_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Menu Manager / Menus'));
	}

	protected function _beforeToHtml()
	{

		if ($this->getRequest()->getParam('id') != null) {
			$content = $this->getLayout()->createBlock('ipmenumanager/adminhtml_menu_edit_tab_form')->toHtml()
				     . $this->getLayout()->createBlock('ipmenumanager/adminhtml_menu_edit_tab_create')->toHtml()
				     . $this->getLayout()->createBlock('ipmenumanager/adminhtml_menu_edit_tab_items')->toHtml();
		} else {
			$content = $this->getLayout()->createBlock('ipmenumanager/adminhtml_menu_edit_tab_form')->toHtml();
		}

		$this->addTab('general',
			array(
				'label' => $this->__('General'),
				'title' => $this->__('General'),
				'content' => $content,
			)
		);

		return parent::_beforeToHtml();
	}
}
