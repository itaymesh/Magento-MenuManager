<?php

class Studioraz_MenuManager_Block_Adminhtml_Menuitem_Edit  extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();

		$this->_controller = 'adminhtml_menuitem';
		$this->_blockGroup = 'ipmenumanager';
		$this->_headerText = $this->_getHeaderText();

		$this->_addButton('save_and_edit_button', array(
			'label'     => Mage::helper('catalog')->__('Save and Continue Edit'),
			'onclick'   => 'editForm.submit(\''.$this->getSaveAndContinueUrl().'\')',
			'class' => 'save'
		));
	}

	/**
	 * Retrieve the URL used for the save and continue link
	 * This is the same URL with the back parameter added
	 *
	 * @return string
	 */
	public function getSaveAndContinueUrl()
	{
		return $this->getUrl('*/*/save', array(
			'_current'   => true,
			'back'       => 'edit',
		));
	}

	public function getBackUrl()
    {
    	if ($this->getRequest()->getParam($this->_objectId)) {
			$menu_id = Mage::getModel('ipmenumanager/menuitem')->load($this->getRequest()->getParam($this->_objectId))->getMenuId();
			return $this->getUrl('*/adminhtml_menu/edit/', array('id' => $menu_id));
		} else {
        	return $this->getUrl('*/adminhtml_menu/');
        }
    }

    /**
     * Retrieve the header text
     * If splash page exists, use name
     *
     * @return string
     */
	protected function _getHeaderText()
	{
		if ($item = Mage::registry('ipmenumanager_menuitem')) {
			if ($displayName = $item->getTitle()) {
				return $displayName;
			}
		}

		return $this->__('Edit Menu Item');
	}
}
