<?php

class Studioraz_MenuManager_Block_Adminhtml_Menuitem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

	protected function _prepareForm()
	{

		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('menuitem_');
        $form->setFieldNameSuffix('menuitem');

		$this->setForm($form);

		$fieldset = $form->addFieldset('menuitem_general', array('legend'=> $this->__('General Information')));

		$this->_addElementTypes($fieldset);

		if ($this->getRequest()->getParam('menuid')) {
			$fieldset->addField('menu_id', 'select', array(
				'name'			=> 'menu_id',
				'label'			=> $this->__('Menu'),
				'title'			=> $this->__('Menu'),
				'required'		=> true,
				'class'			=> 'required-entry',
				'values'		=> $this->_getMenus(),
				'value'         => $this->getRequest()->getParam('menuid')
			));
		} else {
			$fieldset->addField('menu_id', 'select', array(
				'name'			=> 'menu_id',
				'label'			=> $this->__('Menu'),
				'title'			=> $this->__('Menu'),
				'required'		=> true,
				'class'			=> 'required-entry',
				'values'		=> $this->_getMenus()
			));
		}

		$fieldset->addField('title', 'text', array(
			'name' 		=> 'title',
			'label' 	=> $this->__('Title'),
			'title' 	=> $this->__('Title'),
			'required'	=> true,
			'class'		=> 'required-entry',
		));

		$type = $fieldset->addField('type', 'select', array(
			'name'			=> 'type',
			'label'			=> $this->__('Link Type'),
			'title'			=> $this->__('Link type'),
			'required'		=> true,
			'class'			=> 'required-entry',
			'values'		=> $this->_getLinkTypes(),
		));

		$raw_url = $fieldset->addField('raw_url', 'text', array(
			'name' 		=> 'raw_url',
			'label' 	=> $this->__('URL'),
			'title' 	=> $this->__('URL'),
			'required' => true
		));

		$cat_id = $fieldset->addField('category_id', 'text', array(
			'name' 		=> 'category_id',
			'label' 	=> $this->__('Category Id'),
			'title' 	=> $this->__('Category Id'),
			'required' => true
		));

		$cms_page = $fieldset->addField('cms_page', 'select', array(
			'name' 		=> 'cms_page',
			'label' 	=> $this->__('Cms Page'),
			'title' 	=> $this->__('Cms Page'),
			'values'    => Mage::getModel('cms/page')->getCollection()->toOptionArray(),
			'required' => true
		));

		$fieldset->addField('parent', 'select', array(
			'name'			=> 'parent',
			'label'			=> $this->__('Parent Item'),
			'title'			=> $this->__('Parent Item'),
			'values'		=> $this->_getMenuItems(),
		));

		$fieldset->addField('class', 'text', array(
			'name' 		=> 'class',
			'label' 	=> $this->__('CSS Class'),
			'title' 	=> $this->__('CSS Class')
		));

		$fieldset->addField('sort_order', 'text', array(
			'name' 		=> 'sort_order',
			'label' 	=> $this->__('Sort Order'),
			'title' 	=> $this->__('Sort Order'),
			'class'		=> 'validate-digits',
		));

		$fieldset->addField('is_enabled', 'select', array(
			'name' => 'is_enabled',
			'title' => $this->__('Enabled'),
			'label' => $this->__('Enabled'),
			'required' => true,
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
			'value' => 1
		));


		$this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($type->getHtmlId(), $type->getName())
            ->addFieldMap($cms_page->getHtmlId(), $cms_page->getName())
            ->addFieldMap($raw_url->getHtmlId(), $raw_url->getName())
			->addFieldMap($cat_id->getHtmlId(), $cat_id->getName())
            ->addFieldDependence(
	            $raw_url->getName(),
                $type->getName(),
                'url'
            )
            ->addFieldDependence(
                $cms_page->getName(),
                $type->getName(),
                'page'
            )
           ->addFieldDependence(
                $cat_id->getName(),
                $type->getName(),
                'category'
            )
        );

		if ($item = Mage::registry('ipmenumanager_menuitem')) {
			$form->setValues($item->getData());
		}

		return parent::_prepareForm();
	}

	/**
	 * Retrieve an array of all of the stores
	 *
	 * @return array
	 */
	protected function _getMenus()
	{
		$menus = Mage::getResourceModel('ipmenumanager/menu_collection');
		$options = array('' => $this->__('-- Please Select --'));

		foreach($menus as $menu) {
			$options[$menu->getId()] = $menu->getTitle();
		}

		return $options;
	}

	protected function _getMenuItems()
	{

		$menu_id = $this->getRequest()->getParam('menuid');
		$menu = Mage::getModel('ipmenumanager/menu')->load($menu_id);

		$options = array_merge(
			array('' => $this->__('-- Please Select --')),
			$menu->getMenuitemOptionArray()
		);

		return $options;
	}

	protected function _getLinkTypes() {
		$options = array(
			'' => $this->__('-- Please Select --'),
			'url' => 'URL',
            'page' => 'CMS Page',
			'category' => 'Category Id'
			/*
            'customer/account' => 'Customer Account',
            'customer/account/login' => 'Login',
            'customer/account/logout' => 'Logout',
            'wishlist' => 'Wishlist',
            'checkout/cart' => 'Cart'
			*/
		);
		return $options;
	}

}
