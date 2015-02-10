<?php

class Studioraz_MenuManager_Block_Adminhtml_Menu_Edit  extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();

		$this->_controller = 'adminhtml_menu';
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

    /**
     * Retrieve the header text
     * If splash page exists, use name
     *
     * @return string
     */
	protected function _getHeaderText()
	{
		if ($menu = Mage::registry('ipmenumanager_menu')) {
			if ($displayName = $menu->getTitle()) {
				return $displayName;
			}
		}

		return $this->__('Edit Menu');
	}
}
