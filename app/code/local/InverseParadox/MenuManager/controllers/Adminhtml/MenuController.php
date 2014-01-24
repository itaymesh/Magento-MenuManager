<?php

class InverseParadox_MenuManager_Adminhtml_MenuController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('cms/ipmenumanager');
		$this->renderLayout();
	}

	/**
	 * Display the menu grid
	 *
	 */
	public function gridAction()
	{
		$this->getResponse()
			->setBody($this->getLayout()->createBlock('ipmenumanager/adminhtml_menu_grid')->toHtml());
	}

	/**
	 * Forward to the edit action so the user can add a new menu
	 *
	 */
	public function newAction()
	{
		$this->_forward('edit');
	}

	/**
	 * Display the edit/add form
	 *
	 */
	public function editAction()
	{
		$menu = $this->_initMenuModel();

		$this->loadLayout();

		if ($headBlock = $this->getLayout()->getBlock('head')) {
			$titles = array('Menu Manager');

			if ($menu) {
				array_unshift($titles, $menu->getTitle());
			}
			else {
				array_unshift($titles, 'Create a Menu');
			}

			$headBlock->setTitle(implode(' - ', $titles));
		}

		$this->renderLayout();
	}

	/**
	 * Save the menu
	 *
	 */
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost('menu')) {
			$menu = Mage::getModel('ipmenumanager/menu')
				->setData($data)
				->setId($this->getRequest()->getParam('id'));

			try {
				$menu->save();
				$this->_getSession()->addSuccess($this->__('Menu was saved'));
			}
			catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
				Mage::logException($e);
			}

			if ($this->getRequest()->getParam('back') && $menu->getId()) {
				$this->_redirect('*/*/edit', array('id' => $menu->getId()));
				return;
			}
		}
		else {
			$this->_getSession()->addError($this->__('There was no data to save'));
		}

		$this->_redirect('*/*');
	}

	/**
	 * Delete a menu
	 *
	 */
	public function deleteAction()
	{
		if ($menuId = $this->getRequest()->getParam('id')) {
			$menu = Mage::getModel('ipmenumanager/menu')->load($menuId);

			if ($menu->getId()) {
				try {
					$collection = $menu->getFlatMenuitemCollection(true);
					foreach ($collection as $collection_item) {
						$collection_item->delete();
					}
					$menu->delete();
					$this->_getSession()->addSuccess($this->__('The menu was deleted.'));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}

		$this->_redirect('*/*');
	}

	/**
	 * Delete multiple menus in one go
	 *
	 */
	public function massDeleteAction()
	{
		$menuIds = $this->getRequest()->getParam('menu');

		if (!is_array($menuIds)) {
			$this->_getSession()->addError($this->__('Please select some menus.'));
		}
		else {
			if (!empty($menuIds)) {
				try {
					foreach ($menuIds as $menuId) {
						$menu = Mage::getSingleton('ipmenumanager/menu')->load($menuId);

						Mage::dispatchEvent('ipmenumanager_controller_menu_delete', array('ipmenumanager_menu' => $menu));

						$menu->delete();
					}

					$this->_getSession()->addSuccess($this->__('Total of %d menu(s) have been deleted.', count($menuIds)));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}

		$this->_redirect('*/*');
	}

	/**
	 * Initialise the menu model
	 *
	 * @return null|InverseParadox_MenuManager_Model_Menu
	 */
	protected function _initMenuModel()
	{
		if ($menuId = $this->getRequest()->getParam('id')) {
			$menu = Mage::getModel('ipmenumanager/menu')->load($menuId);

			if ($menu->getId()) {
				Mage::register('ipmenumanager_menu', $menu);
			}
		}

		return Mage::registry('ipmenumanager_menu');
	}
}