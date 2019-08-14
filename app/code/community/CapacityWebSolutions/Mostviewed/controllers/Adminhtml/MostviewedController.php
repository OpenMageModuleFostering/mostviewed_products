<?php
/***************************************************************************
 Extension Name	: Mostviewed Products
 Extension URL	: http://www.magebees.com/magento-mostviewed-products-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Mostviewed_Adminhtml_MostviewedController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('cws');
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('mostviewed/mostviewed')->load($id);
		
		if ($model->getMostviewedId() || $id == 0) {
			
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('mostviewed_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cws');
			
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Mostviewed Products'), Mage::helper('adminhtml')->__('mostviewed Products'));
			
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('mostviewed/adminhtml_mostviewed_edit'))
				->_addLeft($this->getLayout()->createBlock('mostviewed/adminhtml_mostviewed_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mostviewed')->__('Mostviewed Products does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	public function getSkusArr($element){
		return $element['sku'];
	}
 
	public function getProductSkus($store_id=0){
		$featuredCollection = Mage::getModel('mostviewed/mostviewed')->getCollection()->addFieldToFilter('store_id', array(array('finset' => $store_id)));
		$product_skus=array_map(array($this,"getSkusArr"), $featuredCollection->getData());
		return $product_skus;
	}
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$id = $this->getRequest()->getParam('id');	
			$store_id = $data['store_id'];
			$product_skus = array();
			if (isset($data['product_sku'])) {
				$product_skus  = explode(', ',$data['product_sku']);
			}
			
			try {
				$store_ids_arr = array();
				
				//if uncheck product sku
				$old_skus_arr = $this->getProductSkus($store_id);
				$sku_for_remove_arr = array();
				$sku_for_remove_arr = array_diff($old_skus_arr,$product_skus);
				if($sku_for_remove_arr){
					
					foreach ($sku_for_remove_arr as $sku) {
						$mostviewed = Mage::getModel('mostviewed/mostviewed')->load($sku,'sku');
						
						if(!$store_id){//for all store views
							$mostviewed->delete();
						}else{
							$new_store_ids_arr = array();
							$old_store_ids = $mostviewed->getData('store_id');
							$old_store_ids_arr = explode(",",$old_store_ids);
							$new_store_ids_arr = array_diff($old_store_ids_arr,array($store_id));
							$new_store_ids = implode(",",$new_store_ids_arr);
													
							if(count($new_store_ids_arr)==1){
							
								$mostviewed->delete();
							}else{
								$mostviewed->setData('sku',$sku);
								$mostviewed->setData('store_id',$new_store_ids);
								$mostviewed->save();
							}
						}
					}
				}
				if(!empty($data['product_sku'])){
					if(!$store_id){//for save sku all store views
						$store_ids_arr = Mage::helper('mostviewed')->getStoreViewIds();//get all storeview ids;
						array_push($store_ids_arr,0);
						$store_ids = implode(",",$store_ids_arr);
						foreach($product_skus as $sku){
							if(!in_array($sku,$old_skus_arr)){
								$model = Mage::getModel('mostviewed/mostviewed')->load($sku,'sku');
								$model->setData('sku',$sku);
								$model->setData('store_id',$store_ids);
								$model->save();
							}
						}
					}else{//for specific storeview
						$store_ids_arr[] = $store_id;
						array_push($store_ids_arr,0);
						foreach($product_skus as $sku){
							$model = Mage::getModel('mostviewed/mostviewed')->load($sku,'sku');
							if($model->getId()){//sku exist
								$old_store_ids = $model->getData('store_id');
								$old_store_ids_arr = explode(",",$old_store_ids);
								$new_store_ids_arr = array_unique(array_merge($old_store_ids_arr,$store_ids_arr));
								$new_store_ids = implode(",",$new_store_ids_arr);
								$model->setData('sku',$sku);
								$model->setData('store_id',$new_store_ids);
								$model->save();
							}else{
								$store_ids = implode(",",$store_ids_arr);
								$model->setData('sku',$sku);
								$model->setData('store_id',$store_ids);
								$model->save();
							}
						}
					}
				}
								
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('mostviewed')->__('Mostviewed Products was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					//$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					$this->_redirectReferer();
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mostviewed')->__('Unable to find Mostviewed Products to save'));
        $this->_redirect('*/*/');
	}
 
	public function massDeleteAction() {
        $mostviewed_ids = $this->getRequest()->getParam('mostviewed');
		$store_id = $this->getRequest()->getParam('store');
		$skus_for_remove_arr = array();
				
		if(!is_array($mostviewed_ids)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
				foreach($mostviewed_ids as $id){
					$skus_for_remove_arr[$id] = Mage::getModel('catalog/product')->load($id)->getSku();
				}
				foreach ($skus_for_remove_arr as $sku) {
                    $mostviewed = Mage::getModel('mostviewed/mostviewed')->load($sku,'sku');
					if($mostviewed->isEmpty() && $store_id!=0){
						Mage::getSingleton('adminhtml/session')->addError("Cannot delete. Please switch to All Store Views.");
						$this->_redirectReferer();
						return;
					}
					if(!$store_id){//for all store views
						$mostviewed->delete();
					}else{
						$new_store_ids_arr = array();
						$old_store_ids = $mostviewed->getData('store_id');
						$old_store_ids_arr = explode(",",$old_store_ids);
						$new_store_ids_arr = array_diff($old_store_ids_arr,array($store_id));
						$new_store_ids = implode(",",$new_store_ids_arr);
												
						if(count($new_store_ids_arr)==1){
							$mostviewed->delete();
						}else{
							$mostviewed->setData('sku',$sku);
							$mostviewed->setData('store_id',$new_store_ids);
							$mostviewed->save();
						}
					}
				}
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted.', count($mostviewed_ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
		$this->_redirectReferer();
    }
	
	public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('mostviewed/adminhtml_mostviewed_grid')->toHtml()
        );
    }
	
}