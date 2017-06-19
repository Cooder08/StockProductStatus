<?php

namespace Cooder\ChildLayer\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * [controller_action_predispatch] 
 * Observer per abilitare e disabilitare i prodotti figli in base allo stock status
 * 
 * @author Alessandro Scavella <alessandro@cooder.it>
 */
class Status implements ObserverInterface
{
	
	/**
	 * Product Repository
	 * 
	 * @var \Magento\Catalog\Api\ProductRepositoryInterface
	 */
	protected $_productRepository;
	
	private $logger;
	
	/**
	 * Costruttore
	 * 
	 * @param \Magento\Customer\Model\Session $customerSession
	 */
	public function __construct(
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Psr\Log\LoggerInterface $logger
	)
	{
		$this->_productRepository = $productRepository;
		$this->logger = $logger;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Magento\Framework\Event\ObserverInterface::execute()
	 */
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$_item = $observer->getEvent()->getItem();
		try{
			$product = $this->_productRepository->getById($_item->getProductId());
			if($product->getVisibility() == \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE && $_item->getManageStock()){
				if($_item->getIsInStock() && $product->getStatus() == \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED){
		    		$product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
					$this->_productRepository->save($product);
				}else if(!$_item->getIsInStock() && $product->getStatus() == \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED){
					$product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
					$this->_productRepository->save($product);
				}
			}
		}catch(Exception $ex){
			$this->logger->critical($e->getMessage());
		}
	}
}