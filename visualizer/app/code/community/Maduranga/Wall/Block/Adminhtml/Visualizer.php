<?php
/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:27 AM
 */
class Maduranga_Wall_Block_Adminhtml_Visualizer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. maduranga_wall/adminhtml_visualizer
        $this->_blockGroup = 'maduranga_wall';
        $this->_controller = 'adminhtml_visualizer';
        $this->_headerText = $this->__('Visualizer');

        parent::__construct();
    }
}