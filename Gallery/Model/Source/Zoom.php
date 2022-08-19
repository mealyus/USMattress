<?php
namespace USMattress\Gallery\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Zoom implements OptionSourceInterface
{

     /**
     * Constructor
     *
     */
    public function __construct()
    {
    }   
    public function toOptionArray()
    {

            $options = array(
            array(
                'label' => 'Use Default',
                'value' => 0,
            ),
            array(
                'label' => 'USMattress Gallery Zooom',
                'value' => 1,
            )
            );
  
        return $options;
    }
}
