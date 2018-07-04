<?php
class Maduranga_Sampath_Block_Adminhtml_Sampath_Renderer_PaddedCardNo extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action{

	public function render(Varien_Object $row)
	{
		return $this->_getValue($row);
	}

	public function _getValue(Varien_Object $row)
	{
		$val = $row->getData($this->getColumn()->getIndex());
        
        $val_array = explode("-",$val);
        //print_r($val_array);
        $card_logo = null;
        if(trim($val_array[1])==trim('(VISA)')){
            $card_logo = '<img src="'.$this->getSkinUrl('images/sampath/visa.jpg', array('_secure'=>true, '_area'=>'frontend')).'" />';
        }else if(trim($val_array[1])==trim('(MASTERCARD)')){
            $card_logo = '<img src="'.$this->getSkinUrl('images/sampath/master.jpg', array('_secure'=>true, '_area'=>'frontend')).'" />';
        }else{
            $card_logo = $val_array[1];
        }
        
		return $val_array[0].' '.$card_logo;
	}
}