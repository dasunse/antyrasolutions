<?php

/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:29 AM
 */
class Maduranga_Wall_Block_Adminhtml_Visualizer_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('maduranga_wall_visualizer_form');
        $this->setTitle($this->__('Visualizer Information'));
    }

    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('maduranga_wall');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => $this->__('Visualizer Background Information'),
            'class' => 'fieldset-wide',
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => $this->__('Name'),
            'title' => $this->__('Name'),
            'required' => true,
        ));

        $fieldset->addField('image', 'file', array(
            'name' => 'image',
            'label' => $this->__('Background Image'),
            'title' => $this->__('Background Image'),
            'required' => true,
        ));

        $fieldset->addField('status', 'select', array(
            'label'     => $this->__('Status'),
            'name'      => 'status',
            'required'  => true,
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => $this->__('Enable'),
                ),

                array(
                    'value'     => 0,
                    'label'     => $this->__('Disable'),
                ),
            ),
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}