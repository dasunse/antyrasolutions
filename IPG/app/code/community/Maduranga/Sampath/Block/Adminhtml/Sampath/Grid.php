<?php

class Maduranga_Sampath_Block_Adminhtml_Sampath_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	
	
	protected function _construct() {
		
		parent::_construct();
		$this->setId('sampath_grid');
		$this->setDefaultSort('sampath_id');
		$this->setDefaultDir('desc');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection(){
		$collection = Mage::getModel('sampath/sampath')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	
	protected function _prepareColumns() { 
		
		echo '<style> .adminhtml-sampath-index .add{ display: none; }</style>';
		
		$this->addColumn('sampath_id', 
					array(
							'header'	=>	Mage::helper('sampath')->__('ID'),
							'align'		=>	'center',
							'index'		=>	'sampath_id',
							'width'		=>	'50px',
					)
				);
		$this->addColumn('order_id', 
					array(
							'header'	=>	Mage::helper('sampath')->__('Order Id'),
							'align'		=>	'center',
							'index'		=>	'order_id',	
							'width'		=>	'100px',
							'renderer' => new Maduranga_Sampath_Block_Adminhtml_Sampath_Renderer_OrderId(),
					)
				);
		
		$this->addColumn('date_time',
				array(
						'header'	=>	Mage::helper('sampath')->__('Date/Time'),
						'align'		=>	'center',
						'index'		=>	'date_time',
						'width'		=>	'100px',
				)
		);
		$this->addColumn('status', 
					array(
							'index'		=>	'status',
							'align'		=>	'center',
							'header'	=>	Mage::helper('sampath')->__('Status'),
							'width'		=>	'100px',
							'renderer' => new Maduranga_Sampath_Block_Adminhtml_Sampath_Renderer_Status(),
					)
				);
		$this->addColumn('ReferenceNo',
				array(
						'index'		=>	'reference_no',
						'align'		=>	'center',
						'header'	=>	Mage::helper('sampath')->__('Reference No'),
						'width'		=>	'100px',
				)
		);
		$this->addColumn('ReasonCodeDesc',
				array(
						'index'		=>	'reason_code_desc',
						'align'		=>	'left',
						'header'	=>	Mage::helper('sampath')->__('Reason Code Desc'),
				)
		);
		$this->addColumn('PaddedCardNo',
				array(
						'index'		=>	'card_no',
						'align'		=>	'center',
						'width'		=>	'175px',
						'header'	=>	Mage::helper('sampath')->__('Card Card No'),
						'renderer' => new Maduranga_Sampath_Block_Adminhtml_Sampath_Renderer_PaddedCardNo(),
				)
		);
		 
		return parent::_prepareColumns();
	}
	
}