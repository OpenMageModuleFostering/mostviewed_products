<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Block_Adminhtml_Mostviewed_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('mostviewed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle('Mostviewed Information');
    }
    
	protected function _beforeToHtml()
    {
	  	$this->addTab('product_section', array(
            'label'     => Mage::helper('mostviewed')->__('Products'),
            'title'     => Mage::helper('mostviewed')->__('Products'),
            'content'   => $this->getLayout()->createBlock('mostviewed/adminhtml_mostviewed_edit_tab_products')->toHtml(),
        ));
			
		return parent::_beforeToHtml();
    }
}