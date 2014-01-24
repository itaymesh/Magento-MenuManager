<?php

class InverseParadox_MenuManager_Block_Adminhtml_Menuitem_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();

		$this->setId('menuitem_grid');
		$this->setDefaultSort('title');
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
		$collection = Mage::getResourceModel('ipmenumanager/menuitem_collection');

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

		$this->addColumn('menu_id', array(
			'header'	=> $this->__('Menu'),
			'align'		=> 'left',
			'index'		=> 'menu_id',
			'type'		=> 'options',
			'options' 	=> $this->_getMenus(),
		));

		$this->addColumn('title', array(
			'header'		=> $this->__('Title'),
			'align'			=> 'left',
			'index'			=> 'title',
		));

		$this->addColumn('sort_order', array(
			'header'		=> $this->__('Order'),
			'align'			=> 'left',
			'width'		=> '60px',
			'index'			=> 'sort_order',
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
				'getter'     => 'getId',
				'actions'   => array(
					array(
						'caption' => Mage::helper('catalog')->__('Edit'),
						'url'     => array(
						'base'=>'*/*/edit',
					),
					'field'   => 'id'
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
		return $this->getUrl('*/*/grid');
	}

	/**
	 * Retrieve the URL for the row
	 *
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	/**
	 * Retrieve an array of all of the stores
	 *
	 * @return array
	 */
	protected function _getMenus()
	{
		$menus = Mage::getResourceModel('ipmenumanager/menu_collection');
		$options = array();

		foreach($menus as $menu) {
			$options[$menu->getId()] = $menu->getTitle();
		}

		return $options;
	}

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('menuitem_id');
		$this->getMassactionBlock()->setFormFieldName('menuitem');

		$this->getMassactionBlock()->addItem('delete', array(
			'label'=> $this->__('Delete'),
			'url'  => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('catalog')->__('Are you sure?')
		));
	}
}