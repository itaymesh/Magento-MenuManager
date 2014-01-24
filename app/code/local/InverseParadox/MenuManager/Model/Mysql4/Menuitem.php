<?php

class InverseParadox_MenuManager_Model_Mysql4_Menuitem extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menuitem', 'menuitem_id');
	}

	/**
	 * Logic performed before saving the model
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return InverseParadox_MenuManager_Model_Mysql4_Menuitem
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
		if (!$object->getMenuId()) {
			$object->setMenuId(null);
		}

		if ($object->getParent()) {
			$table = Mage::getSingleton('core/resource')->getTableName('ipmenumanager/menuitem');
        	$query = 'SELECT level FROM ' . $table . ' WHERE menuitem_id = ' . $object->getParent();
        	$parent_level = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchOne($query);
        	$new_level = $parent_level + 1;
        	$object->setLevel($new_level);
		} else {
			$object->setLevel(1);
			$object->setParent(0);
		}

		if (!$object->getSortOrder()) {
			$object->setSortOrder(Mage::helper('ipmenumanager')->getNextSortOrder($object->getMenuId(), $object->getParent()));
		}

		if ($object->getParent()) {
			$table = Mage::getSingleton('core/resource')->getTableName('ipmenumanager/menuitem');
        	$query = 'SELECT composite_order FROM ' . $table . ' WHERE menuitem_id = ' . $object->getParent();
        	$parent_composite_order = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchOne($query);
        	$composite_order = (string) $object->getSortOrder();
        	if (strlen($composite_order) == 1) {
        		$composite_order = '00' . $composite_order;
        	} else if (strlen($composite_order) == 2) {
        		$composite_order = '0' . $composite_order;
        	}
        	$composite_order = $parent_composite_order . '.' . $composite_order;
        	$object->setCompositeOrder($composite_order);
		} else {
			$composite_order = (string) $object->getSortOrder();
        	if (strlen($composite_order) == 1) {
        		$composite_order = '00' . $composite_order;
        	} else if (strlen($composite_order) == 2) {
        		$composite_order = '0' . $composite_order;
        	}
        	$object->setCompositeOrder($composite_order);
		}

		return parent::_beforeSave($object);
	}

	/**
	 * Retrieve the menu model associated with the item
	 *
	 * @param InverseParadox_MenuManager_Model_Menuitem $item
	 * @return InverseParadox_MenuManager_Model_Menu
	 */
	public function getMenu(InverseParadox_MenuManager_Model_Menuitem $item)
	{
		if ($item->getMenuId()) {
			$menu = Mage::getModel('ipmenumanager/menu')->load($item->getMenuId());

			if ($menu->getId()) {
				return $menu;
			}
		}

		return false;
	}
}
