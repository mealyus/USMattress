<?php

namespace USMattress\Gallery\Block\Product\View;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product\Gallery\ImagesConfigFactoryInterface;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Framework\DataObject;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;
    
    protected $_filesystem;

    protected $_storeManager;
    
    
    /**
     * @var USMattress\Gallery\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param Context $context
     * @param ArrayUtils $arrayUtils
     * @param EncoderInterface $jsonEncoder
     * @param array $data
     * @param ImagesConfigFactoryInterface|null $imagesConfigFactory
     * @param array $galleryImagesConfig
     * @param UrlBuilder|null $urlBuilder
     */
    public function __construct(
        \USMattress\Gallery\Helper\Data $dataHelper,
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storemanager,
        array $data = [],
        ImagesConfigFactoryInterface $imagesConfigFactory = null,
        array $galleryImagesConfig = [],
        UrlBuilder $urlBuilder = null
    ) {

        $this->dataHelper = $dataHelper;
        $this->_filesystem = $filesystem;
        $this->_storeManager =  $storemanager;
        parent::__construct($context, $arrayUtils, $jsonEncoder,$data,$imagesConfigFactory,$galleryImagesConfig,$urlBuilder);

    }
    
    public function getConfigValue($path){
        return $this->dataHelper->getValue($path, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }
    public function getTemplate()
    {
        return 'USMattress_Gallery::product/view/gallery.phtml';
    }

    /**
     * Retrieve product images in JSON format
     *
     * @return string
     */
    public function getGalleryImagesJson()
    {
        $imagesItems = [];
        /** @var DataObject $image */
        foreach ($this->getGalleryImages() as $image) {
            $imageItem = new DataObject(
                [
                    'thumb' => $image->getData('small_image_url'),
                    'img' => $image->getData('medium_image_url'),
                    'full' => $image->getData('large_image_url'),
                    'caption' => ($image->getLabel() ?: $this->getProduct()->getName()),
                    'image_caption' => ($image->getImageCaption() ?: ''),
                    'caption_class' => ($image->getCaptionClass() ?: ''),
                    'position' => $image->getData('position'),
                    'isMain'   => $this->isMainImage($image),
                    'type' => str_replace('external-', '', $image->getMediaType()),
                    'videoUrl' => $image->getVideoUrl(),
                ]
            );
            foreach ($this->getGalleryImagesConfig()->getItems() as $imageConfig) {
                $imageItem->setData(
                    $imageConfig->getData('json_object_key'),
                    $image->getData($imageConfig->getData('data_object_key'))
                );
            }
            $imagesItems[] = $imageItem->toArray();
        }
        if (empty($imagesItems)) {
            $imagesItems[] = [
                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'caption' => '',
                'image_caption' => '',
                'caption_class' => '',
                'position' => '0',
                'isMain' => true,
                'type' => 'image',
                'videoUrl' => null,
            ];
        }
        return json_encode($imagesItems);
    }

    public function getYoutubeVideos()
    {
        $youtubeVideos = [];
        $videoUrl = $this->getProduct()->getYoutubeVideo();
        if (null !== $videoUrl) {
            if (strpos($videoUrl, 'https://www.youtube.com/embed/') !== false) {
                $youtubeVideos[] = $videoUrl;
            }
            else 
            {
                if (strpos($videoUrl, 'https://www.youtube.com/watch?v=') !== false) {
                    $videoUrl = str_replace('https://www.youtube.com/watch?v=', '', $videoUrl);
                }
                if (strpos($videoUrl, 'https://youtu.be/') !== false) {
                    $videoUrl = str_replace('https://youtu.be/', '', $videoUrl);
                }
                $videoUrl = 'https://www.youtube.com/embed/' . $videoUrl;
                $youtubeVideos[] = $videoUrl;
            }
        }
        return $youtubeVideos;
    }

    public function getYoutubeThumbnail () {
        $store = $this->_storeManager->getStore();
        $thumbnail = $this->getProduct()->getYoutubeThumbnail();
        $path = $this->_filesystem->getDirectoryRead (
            DirectoryList::MEDIA
        )->getAbsolutePath (
            'custom/product/attachment/'
        );
        $thumbDir = $path.$thumbnail;
        if (file_exists($thumbDir)) 
        {
            $videoThumbnail = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'custom/product/attachment/' .$thumbnail;
            return $videoThumbnail;
        }
        return false;
    }

    public function getHtmlThumbnail () {
        $store = $this->_storeManager->getStore();
        $thumbnail = $this->getProduct()->getHtmlThumbnail();
        $path = $this->_filesystem->getDirectoryRead (
            DirectoryList::MEDIA
        )->getAbsolutePath (
            'custom/product/attachment/'
        );
        $thumbDir = $path.$thumbnail;
        if (file_exists($thumbDir)) 
        {
            $videoThumbnail = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'custom/product/attachment/' .$thumbnail;
            return $videoThumbnail;
        }
        return false;
    }

    public function getCmsSlideId () {
        $store = $this->_storeManager->getStore();
        $cmsSlideId = $this->getProduct()->getCmsSlideId();
        if (!empty($cmsSlideId) && $cmsSlideId > 0) {
            return $cmsSlideId;
        }
        return false;
    }
}
