<?php
namespace USMattress\Gallery\Observer;

use Magento\Framework\Event\ObserverInterface;

class ChangeTemplateObserver implements ObserverInterface
{
    /**
     * @param mixed $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $observer->getBlock()->setTemplate('USMattress_Gallery::helper/gallery.phtml');
    }
}
