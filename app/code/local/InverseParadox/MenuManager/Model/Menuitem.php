<?php

class InverseParadox_MenuManager_Model_Menuitem extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menuitem');
	}

	/**
	 * Retrieve the menu model associated with the item
	 *
	 * @return InverseParadox_MenuManager_Model_Menu|false
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
	 * @return InverseParadox_MenuManager_Model_Menuitem
	 */
	public function getParentItem()
	{
		if (!$this->getData('parent_item')) {
			$this->setData('parent_item', Mage::getModel('ipmenumanager/menuitem')->load($this->getParent()));
		}
		return $this->getData('parent_item');
	}

	/**
	 * Retrieve full category path
	 *
	 * @return string
	 */
	public function getPath()
	{
		if (!$this->getData('path')) {
			$path = array();
			$path[] = $this->getTitle();
			if ($this->getParent() != 0) {
				$lowest_level = false;
				$parent = $this->getParentItem();
				while (!$lowest_level) {
					$path[] = $parent->getTitle();
					if ($parent->getParent() != 0) {
						$parent = $parent->getParentItem();
					} else {
						$lowest_level = true;
					}
				}	
			}
			$path = array_reverse($path);
			$path = implode(' > ', $path);
			$this->setData('path', $path);
		}
		return $this->getData('path');
	}


	public function getChildren($includeDisabled = false)
	{

		$children = $this->getMenu()->getMenuitemCollection($includeDisabled, $this->getMenuitemId());
		return $children;

	}


	/**
	 * Determine whether the item has a valid URL
	 *
	 * @return bool
	 */
	public function hasUrl()
	{
		return strlen($this->getUrl()) > 1;
	}


	/**
	 * Retrieve the URL
	 * This converts relative URL's to absolute
	 *
	 * @return string
	 */
	public function getUrl()
	{
		if ($this->_getData('url')) {
			if (strpos($this->_getData('url'), 'http://') === false && strpos($this->_getData('url'), 'https://') === false) {
				$this->setUrl(Mage::getBaseUrl() . ltrim($this->_getData('url'), '/ '));
			}
		}

		return $this->_getData('url');
	}

	/**
	 * Retrieve the magento category url
	 *
	 * @return string
	 */
	public function getCategoryUrl()
	{
		if (!$this->getData('category_url')) {
			if ($this->getData('cat_id')) {
				$url = Mage::getModel('catalog/category')->load($this->getData('cat_id'))->getUrl();
				$this->setData('category_url', $url);
			}
		}
		return $this->getData('category_url');
	}
	

	/**
	 * Retrieve the link based on type
	 *
	 * @return string
	 */
	public function getLink()
	{
		if ($this->_getData('type') == 'url') {
			return $this->getUrl();
		} else if ($this->_getData('type') == 'category') {
			return $this->getCategoryUrl();
		} else if ($this->_getData('type') == 'category_children') {
			return $this->getCategoryChildren();
		} else {
			return Mage::getUrl($this->getData('type'));
		}
	}


	public function renderItem()
	{
		$block = Mage::getSingleton('core/layout')
		              ->createBlock('ipmenumanager/item')
		              ->setItem($this);
		return $block->toHtml();
	}

}
