<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Block_Adminhtml_DefaultDirectCode
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		return '<div style="background:#efefef;border:1px solid #d8d8d8;padding:10px;margin-bottom:10px;"><span>&lt?php echo $this->getLayout()->createBlock("mostviewed/mostviewed")->toHtml(); ?&gt;</span></div>';
    }
	
}