<?php

namespace USMattress\Gallery\Block\Adminhtml\Import\Edit;

use Magento\Backend\Model\Session as BackendSession;

class After extends \Magento\Backend\Block\Template
{

    protected $backendSession;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        BackendSession $backendSession,
        array $data = []
    ) {
        $this->backendSession = $backendSession;
        parent::__construct($context, $data);
    }

    public function getUploadUrl(){
      return $this->getUrl('usmattress_gallery/index/upload');
    }

    public function getProcessUrl(){
      return $this->getUrl('usmattress_gallery/index/process');
    }

    public function IsCsvFileUploaded(){
      return $this->backendSession->getCsvFileUploaded();
    }

}
