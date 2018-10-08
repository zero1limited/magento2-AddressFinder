define([
    'mage/storage',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Checkout/js/model/quote'
], function(
    mageStorage,
    urlBuilder,
    quote
){
   'use strict';

    var AddressService = function(){

        this.getAddresses = function(postcode, callback){
            //console.log('get addresses: %s', postcode);
            mageStorage.get(
                urlBuilder.createUrl(
                    '/address-lookup/:cartId/postcode/:postCode',
                    {
                        cartId: quote.getQuoteId(),
                        postCode: postcode
                    }
                ),
                false
            ).done(function(response) {
                //console.log(response);
                callback(response);
            });
        };

        this.getAddressById = function(id, callback) {
            //console.log('get address: %s', id);
            mageStorage.get(
                urlBuilder.createUrl(
                    '/address-lookup/:cartId/id/:Id',
                    {
                        cartId: quote.getQuoteId(),
                        Id: id
                    }
                ),
                false
            ).done(function (response) {
                //console.log(response);
                callback(response);
            });
        }
    };

    return new AddressService();
});