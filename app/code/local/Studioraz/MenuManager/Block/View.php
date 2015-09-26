<?php

class Studioraz_MenuManager_Block_View extends Mage_Core_Block_Template
{


	protected function _construct()
	{
		$this->addData(array(
			'cache_lifetime'=> 86400, // (seconds) data lifetime in the cache
			'cache_tags' => array(
				Mage_Core_Model_Store::CACHE_TAG,
				Mage_Cms_Model_Block::CACHE_TAG,
				Mage_Catalog_Model_Product::CACHE_TAG,
				Mage_Catalog_Model_Category::CACHE_TAG
			)
		));
	}


	/**
	 * Determine whether a valid menu is set
	 *
	 * @return bool
	 */
	public function hasValidMenu()
	{
		if ($this->helper('ipmenumanager')->isEnabled()) {
			return is_object($this->getMenu());
		}

		return false;
	}


	/**
	 * Set the menu code
	 * The menu code is validated before being set
	 *
	 * @param string $code
	 */
	public function setMenuCode($code)
	{

		$currentMenuCode = $this->getMenuCode();

		if ($currentMenuCode != $code) {
			$this->setMenu(null);
			$this->setData('menu_code', null);

			$menu = Mage::getModel('ipmenumanager/menu')->loadByCode($code);

			if ($menu->getId() && $menu->getIsEnabled()) {
				if (in_array($menu->getStoreId(), array(0, Mage::app()->getStore()->getId()))) {
					$this->setMenu($menu);
					$this->setData('menu_code', $code);
				}
			}
		}

		return $this;
	}

	/**
	 * Retrieve a collection of items
	 *
	 * @return Studioraz_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function getMenuitems()
	{
		return $this->getMenu()->getMenuitemCollection();
	}

	/**
	 * If a template isn't passed in the XML, set the default template
	 *
	 * @return Studioraz_MenuManager_Block_View
	 */
	protected function _beforeToHtml()
	{
		parent::_beforeToHtml();

		if (!$this->getTemplate()) {
			$this->setTemplate('ipmenumanager/default.phtml');
		}

		return $this;
	}

}
