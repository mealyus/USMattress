<?php

namespace USMattress\Gallery\Block\Adminhtml\Import;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->buttonList->remove('back');
        $this->buttonList->remove('reset');
        $this->buttonList->update('save', 'label', __('Upload'));
        $this->buttonList->update('save', 'id', 'upload_button');
        $this->buttonList->add(
            'import',
            [
                'label' => __('Import'),
                'id'    => 'import-button',
                'class' => 'no-display'
            ]
        );

        $this->_objectId = 'import_id';
        $this->_blockGroup = 'USMattress_Gallery';
        $this->_controller = 'adminhtml_import';
    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Import');
    }
}
