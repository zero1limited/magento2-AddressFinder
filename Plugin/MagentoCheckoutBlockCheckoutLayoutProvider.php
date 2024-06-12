<?php
namespace Zero1\AddressFinder\Plugin;

use Magento\Framework\Stdlib\ArrayManager;

class MagentoCheckoutBlockCheckoutLayoutProvider
{
    protected $fieldsToHide = [
        'region' => [
            'new_sort_order' => 530
        ],
        'region_id' => [
            'new_sort_order' => 530
        ],
        'country_id' => [
            'new_sort_order' => 540
        ],
        'street' => [
            'new_sort_order' => 510
        ],
        'city' => [
            'new_sort_order' => 520
        ],
    ];

    /** @var \Zero1\AddressFinder\Helper\Config */
    protected $config;

    /** @var ArrayManager */
    protected $arrayManager;

    public function __construct(
        \Zero1\AddressFinder\Helper\Config $config,
        ArrayManager $arrayManager
    ){
        $this->config = $config;
        $this->arrayManager = $arrayManager;
    }

    /**
     * @param $layoutProccessor \Magento\Checkout\Block\Checkout\LayoutProcessor
     * @param $jsLayout []
     * @return []
     */
    public function afterProcess($layoutProccessor, $jsLayout)
    {
        if(!$this->config->isEnabled()){
            return $jsLayout;
        }
        foreach($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] as $fieldId => &$fieldConfig){
            if(in_array($fieldId, array_keys($this->fieldsToHide))){
                $this->fieldsToHide[$fieldId]['original_visible'] = (array_key_exists('visible', $fieldConfig)? $fieldConfig['visible'] : 0);

                if(isset($this->fieldsToHide[$fieldId]['new_sort_order'])){
                    $fieldConfig['sortOrder'] = $this->fieldsToHide[$fieldId]['new_sort_order'];
                }

                if(isset($fieldConfig['component']) && $fieldConfig['component'] == 'Magento_Ui/js/form/components/group'){
                    $fieldConfig['config']['template'] = 'Zero1_AddressFinder/ui/group/group';
                }
            }
        }
        
        $jsLayout = $this->arrayManager->merge(
            'components/checkout/children/steps/children/shipping-step/children/shippingAddress/children/shipping-address-fieldset/children/postcode', 
            $jsLayout,
            [
                'visible' => 1,
                'component' => 'Zero1_AddressFinder/js/form/element/address_finder',
                'template' => 'Zero1_AddressFinder/form/element/address_finder',
                'sortOrder' => 500,
                'target_fields' => [
                    'region_id_input',
                    'country_id',
                    'street',
                    'city',
                ],
            ]
        );

        return $jsLayout;
    }
}