<?php

class Studioraz_MenuManager_Model_Mysql4_Menu extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menu', 'menu_id');
	}

	/**
	 * Retrieve the load select object
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param Mage_Core_Model_Abstract $object
	 * @return Varien_Db_Select
	 */
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = parent::_getLoadSelect($field, $value, $object);

		if (!Mage::app()->isSingleStoreMode() && Mage::app()->getStore()->getId() > 0) {
			$select->where('store_id IN (?)', array(0, Mage::app()->getStore()->getId()))
				->order('store_id DESC')
				->limit(1);
		}

		return $select;
	}

	/**
	 * Retrieve a collection of items associated with the menu
	 *
	 * @param Studioraz_MenuManager_Model_Menu $menu
	 * @return Studioraz_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function getMenuitemCollection(Studioraz_MenuManager_Model_Menu $menu, $parent = 0, $includeDisabled = false)
	{
		$items = Mage::getResourceModel('ipmenumanager/menuitem_collection')
			->addMenuIdFilter($menu->getId())
			->addParentFilter($parent)
			->addOrderBySortOrder();

		if (!$includeDisabled) {
			$items->addIsEnabledFilter(1);
		}

		return $items;
	}

	/**
	 * Retrieve a collection of items associated with the menu
	 *
	 * @param Studioraz_MenuManager_Model_Menu $menu
	 * @return Studioraz_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function getFlatMenuitemCollection(Studioraz_MenuManager_Model_Menu $menu, $includeDisabled = false)
	{
		$items = Mage::getResourceModel('ipmenumanager/menuitem_collection')
			->addMenuIdFilter($menu->getId())
			->addOrderBySortOrder();

		if (!$includeDisabled) {
			$items->addIsEnabledFilter(1);
		}

		return $items;
	}

	/**
	 * Apply processing before saving object
	 *
	 * @param Mage_Core_Model_Abstract $object
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
		if (!$object->getCode()) {
			throw new Exception(Mage::helper('ipmenumanager')->__('Menu must have a unique code'));
		}

		$object->setCode($this->formatMenuCode($object->getCode()));

		if (Mage::getDesign()->getArea() == 'adminhtml') {
			foreach($object->getData() as $field => $value) {
				if (preg_match("/^use_config_([a-zA-Z_]{1,})$/", $field, $result)) {

					$object->setData($result[1], null);
					$object->unsetData($field);
				}
			}
		}

		return parent::_beforeSave($object);
	}

	/**
	 * Convert a string into a valid menu code
	 *
	 * @param string $str
	 * @return string
	 */
	public function formatMenuCode($str)
	{
		$str = preg_replace('#[^0-9a-z]+#i', '_', Mage::helper('catalog/product_url')->format($str));
		$str = strtolower($str);
		$str = trim($str, '_');

		return $str;
	}
}
