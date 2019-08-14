<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Adminhtml_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Prepare block for chooser
     *
     * @return void
     */
    public function chooserAction()
    {
		$request = $this->getRequest();
		$block = $this->getLayout()->createBlock(
			'mostviewed/promo_widget_chooser_sku', 'promo_widget_chooser_sku',array('js_form_object' => $request->getParam('form'),
		));
               
        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cws');
    }

   
}
