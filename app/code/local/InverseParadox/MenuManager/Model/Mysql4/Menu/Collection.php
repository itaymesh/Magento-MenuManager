<?php

class Studioraz_MenuManager_Model_Mysql4_Menu_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('ipmenumanager/menu');
	}
}
