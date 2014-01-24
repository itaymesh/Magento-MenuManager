<?php
class InverseParadox_MenuManager_Block_Item extends Mage_Core_Block_Template
{

    public function setItem($item)
    {

        $currentItem = $this->getItem();

        if ($currentItem != $item) {
            $this->setData('item', $item);
        }

        return $this;
    }

    public function getItem()
    {
        return $this->getData('item');
    }


    public function getClass()
    {
        return $this->getItem()->getClass();
    }


    public function getLink()
    {
        return $this->getItem()->getLink();
    }

    public function getTitle()
    {
        return $this->getItem()->getTitle();
    }


    public function getChildren()
    {
        $children_output = '';
        if (count($this->getItem()->getChildren())) {
            $children_output .= '<ul>';
            foreach ($this->getItem()->getChildren() as $child) {
                $children_output .= $child->renderItem();
            }
            $children_output .= '</ul>';
        }
        return $children_output;
    }


    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        if (!$this->getTemplate()) {
            $this->setTemplate('ipmenumanager/item/default.phtml');
        }

        return $this;
    }

}