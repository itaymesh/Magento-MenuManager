<?php
class InverseParadox_MenuManager_Block_Adminhtml_Renderer_Path extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $path = $row->getPath();
        return $path;
        
    }
}