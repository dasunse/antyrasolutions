<?php
/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:26 AM
 */
class Maduranga_Wall_Model_Mysql4_Visualizer_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('maduranga_wall/visualizer');
    }
}