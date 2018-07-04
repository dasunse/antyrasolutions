<?php

/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 10:19 AM
 */
class Maduranga_Wall_Block_Adminhtml_Template_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $fileDir = $row->getImage();
        $fileUrl = str_replace(Mage::getBaseDir('media'),'', $fileDir);

        $html = '<img ';
        $html .= 'width="250" ';
        $html .= 'id="' . $this->getColumn()->getId() . '" ';
        $html .= 'src="' . Mage::getBaseUrl('media') . $fileUrl. '"';
        $html .= 'class="grid-image ' . $this->getColumn()->getInlineCss() . '"/>';
        return $html;
    }
}