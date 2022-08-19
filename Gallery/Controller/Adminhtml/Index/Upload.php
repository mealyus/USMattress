<?php

namespace USMattress\Gallery\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Backend\Model\Session as BackendSession;

class Upload extends Action
{
    protected $fileSystem;

    protected $uploaderFactory;

    protected $allowedExtensions = ['csv'];

    protected $fileId = 'csv-import-file';

    protected $backendSession;

    public function __construct(
        Action\Context $context,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory,
        BackendSession $backendSession
    ) {
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->backendSession = $backendSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $destinationPath = $this->getDestinationPath();
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $this->fileId])
                ->setAllowCreateFolders(true)
                ->setAllowedExtensions($this->allowedExtensions);
            $uploader->save($destinationPath, 'products.csv');
            $this->backendSession->setCsvFileUploaded(true);
            $this->messageManager->addSuccess(__('The file has been uploaded.'));
            return $this->_redirect('*/*/import');
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
            return $this->_redirect('*/*/import');
        }
    }

    public function validateFile($filePath)
    {
        // @todo
        // your custom validation code here
    }

    public function getDestinationPath()
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath('/usmgallery/youtubevideopopup');
    }

    /**
  	* Topic access rights checking
  	*
  	* @return bool
  	*/
  	protected function _isAllowed()
  	{
        return true;
  		return $this->_authorization->isAllowed('USMattress_Gallery::youtube_videos_import');
  	}
}
