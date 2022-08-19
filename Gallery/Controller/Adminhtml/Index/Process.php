<?php
namespace USMattress\Gallery\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\Model\Session as BackendSession;
use \Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;

class Process extends Action
{

  protected $_backendSession;
  protected $_productRepository;

  /**
   * @var Filesystem
   */
  protected $_filesystem;

  /**
   * CSV Processor
   *
   * @var Csv
   */
  protected $csvProcessor;


	/**
	* @param Context $context
	*/
	public function __construct(
		Context $context,
    JsonFactory $resultJsonFactory,
    Filesystem $filesystem,
    Csv $csvProcessor,
    BackendSession $backendSession,
    ProductRepository $productRepository){
      $this->_resultJsonFactory = $resultJsonFactory;
      $this->_filesystem = $filesystem;
      $this->_csvProcessor = $csvProcessor;
      $this->_backendSession = $backendSession;
      $this->_productRepository = $productRepository;
		  parent::__construct($context);
	}

	public function execute()
	{
    $mediaPath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath()
                     . 'usmgallery/youtubevideopopup/products.csv';
    $importProductRawData = $this->_csvProcessor->getData($mediaPath);
    if($this->_backendSession->getCsvFileLineNumber()){
      $start = $this->_backendSession->getCsvFileLineNumber();
    }else{
      $start = 0;
    }
    $finish = $start + 100;
    $this->_backendSession->setCsvFileLineNumber($finish);

    $count = 0;
    foreach ($importProductRawData as $rowIndex => $dataRow)
    {
        if($count > $start && $count <= $finish){
            if($rowIndex > 0)
            {
                for($i = 2; $i<=2; $i++ ){
                    if($dataRow[$i] && $dataRow[$i] != ''){
                        $product = $this->_productRepository->get($dataRow[1]);
                        if (trim($dataRow[0]) == 'video') {
                            $product->setYoutubeVideo($dataRow[$i]);
                        } else {
                            $product->setCmsSlideId($dataRow[$i]);
                        }
                        $this->_productRepository->save($product);
                    }
                }
            }
        }
        $count++;
    }
    $line = $this->_backendSession->getCsvFileLineNumber();
    if($finish >= $count){
      $secondResponse['finish'] = true;
      $this->_backendSession->setCsvFileLineNumber(false);
      $this->_backendSession->setCsvFileUploaded(false);
    }else{
      $secondResponse['finish'] = false;
    }
    $secondResponse['count'] = $count;
    $secondResponse['line'] = $line;
    $secondResponse['success'] = true;
    $result = $this->_resultJsonFactory->create();
    return $result->setData($secondResponse);
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
