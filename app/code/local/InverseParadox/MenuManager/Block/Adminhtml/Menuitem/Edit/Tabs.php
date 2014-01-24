<?php

class InverseParadox_MenuManager_Block_Adminhtml_Menuitem_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('ipmenumanager_item_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Menu Manager / Menu Item'));
	}

	protected function _beforeToHtml()
	{
		$this->addTab('general',
			array(
				'label' => $this->__('General'),
				'title' => $this->__('General'),
				'content' => $this->getLayout()->createBlock('ipmenumanager/adminhtml_menuitem_edit_tab_form')->toHtml(),
			)
		);

		return parent::_beforeToHtml();
	}
}
