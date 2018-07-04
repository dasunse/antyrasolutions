<?php
/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:28 AM
 */

class Maduranga_Wall_Block_Adminhtml_Visualizer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init class
     */
    public function __construct()
    {
        $this->_blockGroup = 'maduranga_wall';
        $this->_controller = 'adminhtml_visualizer';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Background Image'));
        $this->_updateButton('delete', 'label', $this->__('Delete Background Image'));
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('maduranga_wall')->getId()) {
            return $this->__('Edit Background Image');
        }
        else {
            return $this->__('New Background Image');
        }
    }
}