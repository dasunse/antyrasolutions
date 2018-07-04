<?php
/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 12:23 PM
 */

class Maduranga_Wall_Block_Adminhtml_Template_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $status = $row->getStatus();

        $labels =[
            0 => 'Disable',
            1 => 'Enable'
        ];

        return $labels[$status];
    }
}