<?php
namespace USMattress\Gallery\Block;

class Product extends \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Image
{
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = $this->_urlBuilder->getBaseUrl(['_type' => 
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ]) . 'catalog/product/' . $this->getValue();
        }
        return $url;
    }

    protected function _toHtml()
    {
        $this->setModuleName($this->extractModuleName('Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Image'));
        return parent::_toHtml();
    }
}