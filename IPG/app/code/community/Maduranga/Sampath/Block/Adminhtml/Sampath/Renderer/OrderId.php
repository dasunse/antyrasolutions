<?php
class Maduranga_Sampath_Block_Adminhtml_Sampath_Renderer_OrderId extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action{

	public function render(Varien_Object $row)
	{
		return $this->_getValue($row);
	}

	public function _getValue(Varien_Object $row)
	{
		$val = $row->getData($this->getColumn()->getIndex());
		
		$order = Mage::getModel('sales/order')->loadByIncrementId($val);
		$order_id = $order->getId();
		
		$link = Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=> $order_id));

		return '<a href="'.$link.'" target="_blank" >'.$val.'</a>';

	}
}