<?php
class Maduranga_Sampath_Block_Adminhtml_Sampath extends Mage_Adminhtml_Block_Widget_Grid_Container{
	
	//protected $_addButtonLabel = 'Add New Supplier';
	
	public function _construct(){
		parent::_construct();
		$this->_removeButton('add');
		$this->_controller = 'adminhtml_sampath';
		$this->_blockGroup = 'sampath';
		$this->_headerText = Mage::helper('sampath')->__('Sampath Payment Details');
	}
	
	protected function _prepareLayout()
	{
			
		$this->setChild( 'grid',
				$this->getLayout()->createBlock( $this->_blockGroup.'/' . $this->_controller . '_grid',
						$this->_controller . '.grid')->setSaveParametersInSession(true) );
		return parent::_prepareLayout();
	}
}