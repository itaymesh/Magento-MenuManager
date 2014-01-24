<?php

class InverseParadox_MenuManager_Block_Adminhtml_Menu_Edit_Tab_Items 
	extends Mage_Adminhtml_Block_Widget_Grid 
{
	public function __construct()
	{
		parent::__construct();

		$this->setId('menuitem_grid');
		$this->setDefaultSort('composite_order');
		$this->setDefaultDir('asc');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}

	/**
	 * Initialise and set the collection for the grid
	 *
	 */
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('ipmenumanager/menuitem_collection')
			->addMenuIdFilter($this->getMenuId())
			->addOrderByCompositeOrder();

		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	/**
	 * Add the columns to the grid
	 *
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('menuitem_id', array(
			'header'	=> $this->__('ID'),
			'align'		=> 'left',
			'width'		=> '60px',
			'index'		=> 'menuitem_id',
		));

		$this->addColumn('title', array(
			'header'		=> $this->__('Title'),
			'align'			=> 'left',
			'index'			=> 'title',
		));

		$this->addColumn('path', array(
			'header'		=> $this->__('Path'),
			'align'			=> 'left',
			'index'			=> 'path',
			'renderer'		=> 'InverseParadox_MenuManager_Block_Adminhtml_Renderer_Path',
		));

		$this->addColumn('type', array(
			'header'		=> $this->__('Type'),
			'align'			=> 'left',
			'index'			=> 'type',
		));

		$this->addColumn('level', array(
			'header'		=> $this->__('Level'),
			'align'			=> 'left',
			'width'		=> '60px',
			'index'			=> 'level',
		));

		$this->addColumn('sort_order', array(
			'header'		=> $this->__('Position'),
			'align'			=> 'left',
			'width'		=> '60px',
			'index'			=> 'sort_order',
		));
		/* 'editable'  => true, */

		$this->addColumn('composite_order', array(
			'header'		=> $this->__('Order'),
			'align'			=> 'left',
			'width'		=> '60px',
			'index'			=> 'composite_order',
		));

		$this->addColumn('is_enabled', array(
			'header'	=> $this->__('Enabled'),
			'width'		=> '90px',
			'index'		=> 'is_enabled',
			'type'		=> 'options',
			'options'	=> array(
				1 => $this->__('Enabled'),
				0 => $this->__('Disabled'),
			),
		));

		$this->addColumn('action',
			array(
				'width'     => '50px',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption' => Mage::helper('catalog')->__('Edit'),
						'url'     => array(
							'base' => '*/adminhtml_menuitem/edit',
						),
						'field'   => 'id',
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'align' 	=> 'center',
			));

		return parent::_prepareColumns();
	}

	/**
	 * Retrieve the URL used to modify the grid via AJAX
	 *
	 * @return string
	 */
	public function getGridUrl()
	{
		return $this->getUrl('*/*/menuitemGrid');
	}

	/**
	 * Retrieve the URL for the row
	 *
	 */
	public function getRowUrl($row)
	{
		//return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	/**
	 * Retrieve the menu ID
	 *
	 * @return int
	 */
	public function getMenuId()
	{
		return Mage::registry('ipmenumanager_menu') ? Mage::registry('ipmenumanager_menu')->getId() : 0;
	}
}
