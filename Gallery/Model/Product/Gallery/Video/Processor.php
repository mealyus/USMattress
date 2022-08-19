<?php
namespace USMattress\Gallery\Model\Product\Gallery\Video;

use Magento\Framework\App\Filesystem\DirectoryList;
 
class Processor extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_file;
 
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
 
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;
 
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
     
 
    /**
     * Construct
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
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
            'catalog/product/file/'
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
 
        if (empty($_FILES)) {
            return $this;// if no image is set then nothing to do
        }
 
        try {
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'product['.$this->getAttribute()->getName().']']);
            $uploader->setAllowedExtensions(['jpg']);
            $uploader->setAllowedExtensions(['jpeg']);
            $uploader->setAllowedExtensions(['png']);
            $uploader->setAllowedExtensions(['webp']);
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($path);
            $object->setData($this->getAttribute()->getName(), $result['media_image']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (\Exception $e) {
            if ($e->getCode() != \Magento\MediaStorage\Model\File\Uploader::TMP_NAME_EMPTY) {
                $this->_logger->critical($e);
            }
        }
         
        return $this;
    }
}