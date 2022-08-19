<?php
namespace USMattress\Gallery\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Import extends Action
{
  /**
	* Result page factory
	*
	* @var PageFactory
	*/
	protected $_resultPageFactory;

	/**
	* @param Context $context
  * @param PageFactory $resultPageFactory
	*/
	public function __construct(
		Context $context,
    PageFactory $resultPageFactory){
      $this->_resultPageFactory = $resultPageFactory;
		  parent::__construct($context);
	}


	public function execute()
	{
    $resultPage = $this->_resultPageFactory->create();
		$resultPage->setActiveMenu('USMattress_Gallery::youtube_videos_import');
		$resultPage->getConfig()->getTitle()->prepend(__('Youtube videos import'));
		return $resultPage;
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
