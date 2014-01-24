<?php

class InverseParadox_MenuManager_Model_Menu extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menu');
	}

	/**
	 * Load the model based on the code field
	 *
	 * @param string $code
	 * @return InverseParadox_MenuManager_Model_Menu
	 */
	public function loadByCode($code)
	{
		return $this->load($code, 'code');
	}

	/**
	 * Determine whether the menu is enabled
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->getIsEnabled();
	}

	/**
	 * Retrieve a collection of items associated with this menu
	 *
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function getMenuitemCollection($includeDisabled = false, $parent = 0)
	{
		if (!$this->hasMenuitemCollection()) {
			$this->setMenuitemCollection($this->_getMenuitemCollectionPerLevel($includeDisabled, $parent));
		}

		return $this->_getData('menuitem_collection');
	}

	public function getFlatMenuitemCollection($includeDisabled = false)
	{
		if (!$this->hasFlatMenuitemCollection()) {
			$this->setFlatMenuitemCollection($this->getResource()->getFlatMenuitemCollection($this, $includeDisabled));
		}

		return $this->_getData('flat_menuitem_collection');
	}

	private function _getMenuitemCollectionPerLevel($includeDisabled = false, $parent = 0)
	{
		$collection = $this->getResource()->getMenuitemCollection($this, $parent, $includeDisabled);
		foreach ($collection as &$collection_item) {
			$sub_collection = $this->getResource()->getMenuitemCollection($this, $collection_item->getId(), $includeDisabled);
			$collection_item->setData('child_collection', $sub_collection);
		}
		return $collection;
	}

	public function getMenuitemOptionArray()
	{
		$options = array();
		foreach ($this->_getMenuitemFlatList($this->getMenuitemCollection(true)) as $item) {
			$options[] = array(
				'value' => $item['id'],
				'label' => $item['label']
			);
		}
		return $options;
	}

	private function _getMenuitemFlatList($collection)
	{
		$list_items = array();
		foreach ($collection as $item) {
			$line = '';
			for ($i = 1; $i < (int) $item->getLevel(); $i++) {
				$line .= '--';
			}
			if ($line != '') {
				$line .= ' ';
			}
			$line .= $item->getTitle();
			$list_item = array(
				'label' => $line,
				'id' => $item->getMenuitemId()
			);
			array_push($list_items, $list_item);
			if ($item->getChildCollection()) {
				$list_items = array_merge($list_items, $this->_getMenuitemFlatList($item->getChildCollection()));
			}
		}
		return $list_items;
	}

	/**
	 * Retrieve the amount of items in this menu
	 *
	 * @return int
	 */
	public function getMenuitemCount()
	{
		if (!$this->hasMenuitemCount()) {
			$this->setMenuitemCount($this->getMenuitemCollection()->count());
		}

		return $this->_getData('menuitem_count');
	}

}
