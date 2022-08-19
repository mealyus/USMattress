<?php
namespace USMattress\Gallery\Controller\Index;

use Magento\Framework\App\Action\Action;

class Video extends Action
{
    protected $videoGalleryProcessor;
    protected $_product;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product,
        \USMattress\Gallery\Model\Product\Gallery\Video\Processor $videoGalleryProcessor
    ){
        parent::__construct($context);
        $this->_product = $product;
        $this->videoGalleryProcessor = $videoGalleryProcessor;
    }

    public function execute()
    {
        $productId = 212305; // product id
        $product = $this->_product->load($productId);
        $product->setStoreId(0); //set store vise data

        // sample video data
        $videoData = [
            'video_id' => "ojxkbIKFNJM", //set your video id
            'video_title' => "title", //set your video title
            'video_description' => "description", //set your video description
            'thumbnail' => "image path", //set your video thumbnail path.
            'video_provider' => "youtube",
            'video_metadata' => null,
            'video_url' => "https://www.youtube.com/watch?v=ojxkbIKFNJM", //set your youtube video url
            'media_type' => \Magento\ProductVideo\Model\Product\Attribute\Media\ExternalVideoEntryConverter::MEDIA_TYPE_CODE,
        ];

        //download thumbnail image and save locally under pub/media
        $videoData['file'] = $videoData['video_id'] . 'filename.jpg';

        // Add video to the product
        if ($product->hasGalleryAttribute()) {
            $this->videoGalleryProcessor->addVideo(
                $product,
                $videoData,
                ['image', 'small_image', 'thumbnail'],
                false,
                true
            );
        }
        $product->save();
    }
}