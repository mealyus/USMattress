<?php

namespace USMattress\Gallery\Plugin\Product;

class Gallery
{
    public function afterCreateBatchBaseSelect(
        \Magento\Catalog\Model\ResourceModel\Product\Gallery $subject,
        \Magento\Framework\DB\Select $select
    ) {
        $select->columns('image_caption');
        $select->columns('caption_class');

        return $select;
    }
}