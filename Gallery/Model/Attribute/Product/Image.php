<?php
namespace USMattress\Gallery\Model\Attribute\Product;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

class Image extends AbstractBackend
{
	protected $_file;

	protected $_logger;

	protected $_filesystem;

	protected $_fileUploaderFactory;

	public function __construct(
	    \Psr\Log\LoggerInterface $logger,
	    \Magento\Framework\Filesystem $filesystem,
	    \Magento\Framework\Filesystem\Driver\File $file,
	    \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
	) {
	    $this->_file = $file;
	    $this->_filesystem = $filesystem;
	    $this->_fileUploaderFactory = $fileUploaderFactory;
	    $this->_logger = $logger;
	}

	public function afterSave($object)
	{
	    $path = $this->_filesystem->getDirectoryRead(
	        DirectoryList::MEDIA
	    )->getAbsolutePath(
	        'custom/product/attachment/'
	    );
	    $delete = $object->getData($this->getAttribute()->getName() . '_delete');

	    if ($delete) {
	        $fileName = $object->getData($this->getAttribute()->getName());
	        $object->setData($this->getAttribute()->getName(), '');
	        $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
	        if ($this->_file->isExists($path.$fileName))  {
	            $this->_file->deleteFile($path.$fileName);
	        }

	    }

	    if (empty($_FILES['product']['tmp_name'][$this->getAttribute()->getName()])) {
	        return $this;
	    }
	    try {
	        $uploader = $this->_fileUploaderFactory->create(['fileId' => 'product['.$this->getAttribute()->getName().']']);
	    
	        $uploader->setAllowRenameFiles(true);
	        $result = $uploader->save($path);
	        $object->setData($this->getAttribute()->getName(), $result['file']);
	        $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
	    } catch (\Exception $e) {
	        if ($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY) {
	            $this->_logger->critical($e);
	        }
	    }
	    return $this;
	}
}