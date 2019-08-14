<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Block_Adminhtml_Mostviewed extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_mostviewed';
		$this->_blockGroup = 'mostviewed';
		$this->_headerText = Mage::helper('mostviewed')->__('Manage Most Viewed Products');
		$this->_addButtonLabel = Mage::helper('mostviewed')->__('Select Products');
		parent::__construct();
	}
	
	protected function _prepareLayout() {
        $this->setChild('store_switcher', $this->getLayout()->createBlock('adminhtml/store_switcher', 'store_switcher')->setUseConfirm(false)
        );
        return parent::_prepareLayout();
    }

    public function getGridHtml() {

        return $this->getChildHtml('store_switcher') . $this->getChildHtml('grid');
    }
}