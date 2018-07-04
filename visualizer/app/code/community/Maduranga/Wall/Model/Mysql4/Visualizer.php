<?php
/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:25 AM
 */
class Maduranga_Wall_Model_Mysql4_Visualizer extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('maduranga_wall/visualizer', 'id');
    }
}