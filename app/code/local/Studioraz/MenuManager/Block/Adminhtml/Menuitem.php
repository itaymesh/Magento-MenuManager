<?php
class Studioraz_MenuManager_Block_Adminhtml_Menuitem extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		parent::__construct();

		$this->_controller = 'adminhtml_menuitem';
		$this->_blockGroup = 'ipmenumanager';
		$this->_headerText = $this->__('Menu Manager / Menu Items');
	}
}