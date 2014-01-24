<?php

class InverseParadox_MenuManager_Adminhtml_MenuitemController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_setActiveMenu('cms/ipmenumanager');
		$this->renderLayout();
	}

	/**
	 * Display the items grid
	 *
	 */
	public function gridAction()
	{
		$this->getResponse()
			->setBody($this->getLayout()->createBlock('ipmenumanager/adminhtml_menuitem_grid')->toHtml());
	}

	/**
	 * Forward to the edit action so the user can add a new item
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
		$item = $this->_initMenuitemModel();
		$this->loadLayout();

		if ($headBlock = $this->getLayout()->getBlock('head')) {
			$titles = array('Menu Manager');

			if ($item) {
				array_unshift($titles, $item->getTitle());
			}
			else {
				array_unshift($titles, 'Create a Menu Item');
			}

			$headBlock->setTitle(implode(' - ', $titles));
		}

		$this->renderLayout();
	}

	/**
	 * Save the item
	 *
	 */
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost('menuitem')) {
			$item = Mage::getModel('ipmenumanager/menuitem')
				->setData($data)
				->setId($this->getRequest()->getParam('id'));

			try {
				$item->save();
				$this->_getSession()->addSuccess($this->__('Menu Item was saved'));
			}
			catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
				Mage::logException($e);
			}

			if ($this->getRequest()->getParam('back') && $item->getId()) {
				$this->_redirect('*/*/edit', array('id' => $item->getId()));
				return;
			}
		}
		else {
			$this->_getSession()->addError($this->__('There was no data to save'));
		}

		$this->_redirect('*/adminhtml_menu/edit', array('id' => $item->getMenuId()));
	}


	/**
	 * Delete a menu item
	 *
	 */
	public function deleteAction()
	{
		if ($itemId = $this->getRequest()->getParam('id')) {
			$item = Mage::getModel('ipmenumanager/menuitem')->load($itemId);

			if ($item->getId()) {
				try {
					$item->delete();
					$this->_getSession()->addSuccess($this->__('The menu item was deleted.'));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}

		$this->_redirect('*/adminhtml_menu/edit', array('id' => $item->getMenuId()));
	}

	/**
	 * Delete multiple menu items in one go
	 *
	 */
	public function massDeleteAction()
	{
		$itemIds = $this->getRequest()->getParam('menuitem');

		if (!is_array($itemIds)) {
			$this->_getSession()->addError($this->__('Please select some menu items.'));
		}
		else {
			if (!empty($itemIds)) {
				try {
					foreach ($itemIds as $itemId) {
						$item = Mage::getSingleton('ipmenumanager/menuitem')->load($itemId);

						Mage::dispatchEvent('ipmenumanager_controller_menuitem_delete', array('ipmenumanager_menuitem' => $item));

						$item->delete();
					}

					$this->_getSession()->addSuccess($this->__('Total of %d item(s) have been deleted.', count($itemIds)));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}

		$this->_redirect('*/*');
	}

	/**
	 * Initialise the item model
	 *
	 * @return null|InverseParadox_MenuManager_Model_Item
	 */
	protected function _initMenuitemModel()
	{
		if ($itemId = $this->getRequest()->getParam('id')) {
			$item = Mage::getModel('ipmenumanager/menuitem')->load($itemId);

			if ($item->getId()) {
				Mage::register('ipmenumanager_menuitem', $item);
			}
		}

		return Mage::registry('ipmenumanager_menuitem');
	}
}