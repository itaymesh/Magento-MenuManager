<?php

class InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menuitem');
	}

	/**
	 * Init collection select
	 *
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection
	*/
	protected function _initSelect()
	{
		$this->getSelect()->from(array('main_table' => $this->getMainTable()));

		return $this;
	}

	/**
	 * Filter the collection by a menu ID
	 *
	 * @param int $menuId
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function addMenuIdFilter($menuId)
	{
		return $this->addFieldToFilter('menu_id', $menuId);
	}

	/**
	 * Filter the collection by parent
	 *
	 * @param int $parent
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function addParentFilter($parent_id)
	{
		return $this->addFieldToFilter('parent', $parent_id);
	}

	/**
	 * Filter the collection by enabled items
	 *
	 * @param int $isEnabled = true
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection
	 */
	public function addIsEnabledFilter($isEnabled = true)
	{
		return $this->addFieldToFilter('is_enabled', $isEnabled ? 1 : 0);
	}

	/**
	 * Add order by sort order
	 *
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem_Collection
	*/
	public function addOrderBySortOrder($dir = 'ASC')
	{
		$this->getSelect()->order('sort_order ' . $dir);
		return $this;
	}

	public function addOrderByCompositeOrder($dir = 'ASC')
	{
		$this->getSelect()->order('composite_order ' . $dir);
		return $this;
	}
}
