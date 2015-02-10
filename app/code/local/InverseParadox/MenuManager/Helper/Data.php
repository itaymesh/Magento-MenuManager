<?php

class Studioraz_MenuManager_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Determine whether the extension is enabled
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return Mage::getStoreConfig('ipmenumanager/settings/enabled');
	}

    public function getNextSortOrder($menu_id, $parent)
    {
        $table = Mage::getSingleton('core/resource')->getTableName('ipmenumanager/menuitem');
        $query = 'SELECT MAX(sort_order) FROM ' . $table . ' WHERE menu_id = ' . $menu_id . ' AND parent = ' . $parent;
        $max = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchOne($query);
        if ($max != null) {
            $max = (int) $max;
            $max = $max + 1;
            return $max;
        } else {
            return 0;
        }
    }
}
