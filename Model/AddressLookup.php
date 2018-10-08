<?php

namespace Zero1\AddressFinder\Model;

use Zero1\AddressFinder\Api\AddressLookupInterface;
use Magento\Quote\Model\QuoteIdMask;
use Zero1\AddressFinder\Api\Data\Address;
use Zero1\AddressFinder\Api\Data\AddressResult;
use Zero1\AddressFinder\Api\Data\AddressSummary;
use Zero1\AddressFinder\Api\Data\AddressSummaryResults;
use Zero1\AddressFinder\Model\AddressLookup\Exception;

class AddressLookup implements AddressLookupInterface
{
    const BASE_URL = 'http://services.postcodeanywhere.co.uk/xml.aspx?';

    protected $quoteRepository;

    protected $maskedQuoteIdFactory;

    /** @var \Zero1\AddressFinder\Helper\Config */
    protected $config;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Model\QuoteIdMaskFactory $maskedQuoteIdFactory,
        \Zero1\AddressFinder\Helper\Config $config
    ){
        $this->quoteRepository = $quoteRepository;
        $this->maskedQuoteIdFactory = $maskedQuoteIdFactory;
        $this->config = $config;
    }

    /**
     * @param $cartId string
     * @param $postcode string
     * @return \Zero1\AddressFinder\Api\Data\AddressSummaryResultsInterface
     */
    public function getAddresses($cartId = null, $postcode)
    {
        $result = new AddressSummaryResults();

        try{
            if($cartId){
                $quoteIdMask = $this->maskedQuoteIdFactory->create()->load($cartId, 'masked_id');
                /** @var \Magento\Quote\Model\Quote $quote */
                $quote = $this->quoteRepository->getActive($quoteIdMask->getQuoteId());
            }
        }catch(\Exception $e){
            $result->setResult(false)
                ->setMessage('Invalid cart id');
            return $result;
        }

        try{
            $result->setAddresses($this->getAddressesByPostCode($postcode))
                ->setResult(true);
        }catch(Exception $e){
            $result->setResult(false)
                ->setMessage($e->getMessage());
        }
        return $result;
    }

    /**
     * @param $cartId string
     * @param $addressId string
     * @return \Zero1\AddressFinder\Api\Data\AddressResultInterface
     */
    public function getAddress($cartId = null, $addressId)
    {
        $result = new AddressResult();

        try{
            if($cartId){
                $quoteIdMask = $this->maskedQuoteIdFactory->create()->load($cartId, 'masked_id');
                /** @var \Magento\Quote\Model\Quote $quote */
                $quote = $this->quoteRepository->getActive($quoteIdMask->getQuoteId());
            }
        }catch(\Exception $e){
            $result->setResult(false)
                ->setMessage('Invalid cart id');
            return $result;
        }

        try{
            $result->setAddress($this->getAddressById($addressId))
                ->setResult(true);
        }catch(Exception $e){
            $result->setResult(false)
                ->setMessage($e->getMessage());
        }
        return $result;
    }

    protected function getAddressesByPostCode($postcode, $accountCode = null, $key = null, $machineCode = null)
    {
        $data = $this->call(
            $this->getUrl([
                'action' => 'lookup',
                'type' => 'by_postcode',
                'postcode' => $postcode,
                'account_code' => $accountCode,
                'license_code' => $key,
                'machine_id' => $machineCode
            ])
        );
        if($data['Schema']['@attributes']['Items'] == 2){
            throw new Exception($data['Data']['Item']['@attributes']['message']);
        }

        $results = [];
        if(isset($data['Data']['Item'])){
            foreach($data['Data']['Item'] as $itemData){
                $results[] = new AddressSummary(
                    $itemData['@attributes']['id'],
                    $itemData['@attributes']['description']
                );
            }
        }

        return $results;
    }

    protected function getAddressById($id, $accountCode = null, $key = null, $machineCode = null)
    {
        $data = $this->call(
            $this->getUrl([
                'action' => 'fetch',
                'id' => $id,
                'language' => 'english',
                'style' => 'simple',
                'account_code' => $accountCode,
                'license_code' => $key,
                'machine_id' => $machineCode,
                'options' => '',
            ])
        );

        if($data['Schema']['@attributes']['Items'] == 2){
            throw new Exception($data['Data']['Item']['@attributes']['message']);
        }

        return new Address(
            $data['Data']['Item']['@attributes']['id'],
            isset($data['Data']['Item']['@attributes']['line1'])? $data['Data']['Item']['@attributes']['line1'] : '',
            isset($data['Data']['Item']['@attributes']['line2'])? $data['Data']['Item']['@attributes']['line2'] : '',
            isset($data['Data']['Item']['@attributes']['post_town'])? $data['Data']['Item']['@attributes']['post_town'] : '',
            isset($data['Data']['Item']['@attributes']['county'])? $data['Data']['Item']['@attributes']['county'] : '',
            isset($data['Data']['Item']['@attributes']['postcode'])? $data['Data']['Item']['@attributes']['postcode'] : ''
        );
    }

    protected function getUrl($params)
    {
        if(!isset($params['account_code'])){
            $params['account_code'] = $this->config->getAccountCode();
        }
        if(!isset($params['license_code'])){
            $params['license_code'] = $this->config->getPublicKey();
        }
        if(!isset($params['machine_id'])){
            $params['machine_id'] = '';
        }

        $builtParams = [];
        foreach($params as $k => $v){
            $builtParams[] = $k.'='.urlencode($v);
        }
        return self::BASE_URL.implode('&', $builtParams);
    }

    protected function call($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode(json_encode(simplexml_load_string($data)), true);
    }
}