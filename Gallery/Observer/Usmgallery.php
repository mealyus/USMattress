<?php

namespace USMattress\Gallery\Observer;

use Magento\Framework\Event\ObserverInterface;

class Usmgallery implements ObserverInterface {

    protected $request;
    protected $resource;

    /**
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResourceConnection $resource\
     */
    public function __construct (
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->request = $request;
        $this->resource = $resource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $this->request->getPostValue();
        if (isset($data['product']['media_gallery']['images'])) {
            $connection = $this->resource->getConnection();
            $tableName = 'catalog_product_entity_media_gallery';
            $product = $observer->getProduct();
            $mediaGallery = $product->getMediaGallery();

            if (isset($mediaGallery['images'])) {
                foreach ($mediaGallery['images'] as $image) {
                    $image_caption = !empty($image['image_caption']) ? $image['image_caption'] : 'null';
                    if ($image_caption != 'null') {
                        $sql = "UPDATE " . $tableName . " SET image_caption = '" . $image_caption . "' WHERE value_id = " . $image['value_id'];
                        $connection->query($sql);
                    }
                    $caption_class = !empty($image['caption_class']) ? $image['caption_class'] : 'null';
                    if ($caption_class != 'null') {
                        $sql = "UPDATE " . $tableName . " SET caption_class = '" . $caption_class . "' WHERE value_id = " . $image['value_id'];
                        $connection->query($sql);
                    }
                }
            }
        }
    }

}