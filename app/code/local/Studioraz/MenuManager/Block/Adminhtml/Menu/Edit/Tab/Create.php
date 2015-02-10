<?php
class Studioraz_MenuManager_Block_Adminhtml_Menu_Edit_Tab_Create extends Mage_Adminhtml_Block_Widget_Form
{

    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('ipmenumanager/menuitem/quickadd.phtml');
    }

}  
?>