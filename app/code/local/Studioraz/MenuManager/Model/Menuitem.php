<?php

class Studioraz_MenuManager_Model_Menuitem extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menuitem');
	}

	/**
	 * Retrieve the menu model associated with the item
	 *
	 * @return Studioraz_MenuManager_Model_Menu|false
	 */
	public function getMenu()
	{
		if (!$this->hasMenu()) {
			$this->setMenu($this->getResource()->getMenu($this));
		}

		return $this->getData('menu');
	}

	/**
	 * Retrieve parent model item
	 *
	 * @return Studioraz_MenuManager_Model_Menuitem
	 */
	public function getParentItem()
	{
		if (!$this->getData('parent_item')) {
			$this->setData('parent_item', Mage::getModel('ipmenumanager/menuitem')->load($this->getParent()));
		}
		return $this->getData('parent_item');
	}


	public function getChildren($includeDisabled = false)
	{

		$children = $this->getMenu()->getMenuitemCollection($includeDisabled, $this->getMenuitemId());
		return $children;

	}



	public function renderItem()
	{
		$block = Mage::app()->getLayout()
		              ->createBlock('ipmenumanager/item')
		              ->setItem($this);
		return $block->toHtml();
	}


	/**
	 * Retrieve the link based on type
	 *
	 * @return false|mixed
	 */
	public function getLink()
	{
		if ($renderer = $this->_getRenderer($this->getType())) {
			return $renderer->setItem($this)->render();
		}

		return false;
	}

	/**
	 * Retrieves the rendering class
	 * If class based on $type isn't found, returns default renderer
	 *
	 * @param string $type
	 * @return Studioraz_MenuManager_Model_Item_Renderer_Abstract
	 */
	protected function _getRenderer($type)
	{
		$types = array($type, 'default');
		$baseDir = Mage::getModuleDir('', 'Studioraz_MenuManager') . DS . 'Model' . DS . 'Item' . DS . 'Renderer' . DS;

		foreach($types as $type) {
			$classFile = $baseDir . uc_words($type, DS) . '.php';

			if (is_file($classFile) && ($renderer = Mage::getModel('ipmenumanager/item_renderer_' . $type)) !== false) {
				return $renderer;
			}
		}

		return false;
	}

}
