<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Block_Adminhtml_Mostviewed_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'mostviewed';
		$this->_controller = 'adminhtml_mostviewed';
		$this->_updateButton('save', 'label','Save');
		$this->_updateButton('delete','label','Delete');
		$this->_addButton('save_and_continue', array(
             'label' => Mage::helper('mostviewed')->__('Save And Continue Edit'),
             'onclick' => 'saveAndContinueEdit()',
             'class' => 'save' 
         ), -100);
		
         $this->_formScripts[] = "
            function saveAndContinueEdit(){
				editForm.submit($('edit_form').action + 'back/edit/');
            }
			 
			 ";
		$this->setId('mostviewed_edit');
	}
	
	public function getHeaderText()
    {
        if( Mage::registry('mostviewed_data') && Mage::registry('mostviewed_data')->getNewproductsId() ) {
            return Mage::helper('mostviewed')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('mostviewed_data')->getSku()));
        } else {
            return Mage::helper('mostviewed')->__('Select Products');
        }
    }	
}