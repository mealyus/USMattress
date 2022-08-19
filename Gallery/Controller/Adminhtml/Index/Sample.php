<?php
namespace USMattress\Gallery\Controller\Adminhtml\Index;

class Sample extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->resultRawFactory      = $resultRawFactory;
        $this->fileFactory           = $fileFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        return $this->fileFactory->create(
            'products.csv',
            'type,sku,value
video,simmons-beautyrest-harmony-lux-hld-2000-plush-pillow-top-mattress-twin-xl,https://www.youtube.com/embed/iOP-RNIS2aE
video,simmons-beautyrest-harmony-lux-hld-2000-plush-pillow-top-mattress-twin-xl,https://www.youtube.com/embed/iOP-RNIS2aE
block,simmons-beautyrest-harmony-lux-hld-2000-plush-pillow-top-mattress-twin-xl,13
block,simmons-beautyrest-harmony-lux-hld-2000-plush-pillow-top-mattress-twin-xl,14',
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA,
            'application/octet-stream',
            null
        );
    }
}
