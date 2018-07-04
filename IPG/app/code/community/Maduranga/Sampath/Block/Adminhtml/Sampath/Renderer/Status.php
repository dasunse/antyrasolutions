<?php
class Maduranga_Sampath_Block_Adminhtml_Sampath_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action{
	
	public function render(Varien_Object $row)
	{
		return $this->_getValue($row);
	}
	
	public function _getValue(Varien_Object $row)
	{
		$val = $row->getData($this->getColumn()->getIndex());  // row value
		
		if($val==1){
			return 'Approved.';
		}elseif ($val==2){
			return 'Declined.';
		}elseif ($val==3){
			return 'Error.';
		}else{
			return 'Undefined.';
		}
	
	}
}