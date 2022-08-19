<?php

namespace USMattress\Gallery\Block\Adminhtml\Import\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\ImportExport\Model\Import $importModel,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Add fieldsets
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('usmattress_gallery/index/upload'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );
        // fieldset for file uploading
        $fieldset  = $form->addFieldset(
            'upload_file_fieldset',
            ['legend' => __('File to Upload')]
        );
        $fieldset->addField(
            'csv-import-file',
            'file',
            [
                'name' => 'csv-import-file',
                'label' => __('Select File to Upload'),
                'title' => __('Select File to Upload'),
                'required' => true,
                'class' => 'input-file',
                'after_element_html' => $this->getDownloadSampleFileHtml()
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get download sample file html
     *
     * @return string
     */
    protected function getDownloadSampleFileHtml()
    {
        $html = '<span id="sample-file-span"><a id="sample-file-link" href="'. $this->getSampleFileUrl() .'" target="_blank">'
            . __('Download Sample File')
            . '</a></span>';
        return $html;
    }
    /**
     * Get download sample file html
     *
     * @return string
     */
    public function getSampleFileUrl(){
      return $this->getUrl('usmattress_gallery/index/sample');
    }
}
