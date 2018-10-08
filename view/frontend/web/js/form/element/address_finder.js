/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'ko',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/post-code',
    'Magento_Checkout/js/checkout-data',
    'Zero1_AddressFinder/js/model/address_service'
], function (
    ko,
    _,
    registry,
    PostCode,
    checkoutData,
    addressService
) {
    'use strict';

    return PostCode.extend({

        hasTemplate: function(){
            return true;
        },

        getTemplate: function(){
            return 'Zero1_AddressFinder/form/element/address_finder';
        },

        addressFieldsHidden: ko.observable(false),
        selectedAddress: ko.observable(null),
        lookupError: ko.observable(''),
        addressOptions: ko.observableArray([]),
        state_Ready: 'ready',
        state_LookingUp: 'looking-up',
        state_LookedUpSuccessfully: 'looked-up-successfully',
        state_LookedUpError: 'looked-up-error',

        toggleFieldVisibility: function(isVisible){
            var self = this;
            var elements = registry.get(self.parentName).elems();
            _.each(
                _.filter(elements, function(element){ return _.indexOf(self.target_fields, element.index) > -1; }),
                function(element){

                    if(typeof element['setVisible'] === 'function'){
                        element.setVisible(isVisible);
                    }else if(typeof element['visible'] === 'function'){
                        element.visible(isVisible);
                    }else{
                        //console.log('unable to hide element');
                        //console.log(element);
                    }
                }
            );
            self.addressFieldsHidden(!isVisible);
        },

        initialize: function(){
            this._super();
            var self = this;

            registry.get(self.parentName).elems.subscribe(function(elements){
                //console.log('current value: %s', self.value());
                var data = checkoutData.getShippingAddressFromData();
                if(data && data.postcode && data.city){
                    self.toggleFieldVisibility(true);
                }else{
                    self.toggleFieldVisibility(false);
                }
            });

            this.currentLookupState = ko.observable(self.state_Ready);
            this.lookupStateIsReady = ko.pureComputed(function(){ return self.currentLookupState() == self.state_Ready; });
            this.setLookupStateReady = function(){ self.currentLookupState(self.state_Ready); };
            this.lookupStateIsLookingUp = ko.pureComputed(function(){ return self.currentLookupState() == self.state_LookingUp; });
            this.setLookupStateLookingUp = function(){ self.currentLookupState(self.state_LookingUp); };
            this.lookupStateIsLookedUpSuccessfully = ko.pureComputed(function(){ return self.currentLookupState() == self.state_LookedUpSuccessfully; });
            this.setLookupStateLookedUpSuccessfully = function(){ self.currentLookupState(self.state_LookedUpSuccessfully); };
            this.lookupStateIsLookedUpError = ko.pureComputed(function(){ return self.currentLookupState() == self.state_LookedUpError; });
            this.setLookupStateLookedUpError = function(){ self.currentLookupState(self.state_LookedUpError); };

            this.selectedAddress.subscribe(function(value){
                if(self.lookupStateIsLookedUpSuccessfully()){
                    self.lookupAddressById(value);
                }
            });

            this.canLookup = ko.pureComputed(function(){
                return self.lookupStateIsReady() && self.value() != null && self.value().length > 0
            });

            this.value.subscribe(function(value){
                //console.log('value update: %s', value);
                if(self.lookupStateIsLookedUpError()){
                    self.lookupError('');
                    self.setLookupStateReady();
                }
                if(self.lookupStateIsLookedUpSuccessfully()){
                    self.setLookupStateReady();
                }
            });
            this.value.extend({notify: 'always'});

            this.handleResponse = function(response){
                if(response.result){
                    _.each(response.addresses, function(addressOption){
                        self.addressOptions.push({
                            value: addressOption.id,
                            label: addressOption.description
                        });
                    });
                    self.setLookupStateLookedUpSuccessfully();
                }else{
                    this.lookupError(response.message);
                    this.setLookupStateLookedUpError();
                }
            };

            this.updateAddressData = function(response){
                if(response.result){
                    var elements = registry.get(self.parentName).elems();
                    var street = _.find(elements, function(element){ return element.index == 'street'; });
                    var lineCounter = 0;
                    _.each(street.elems(), function(line){
                        lineCounter++;
                        if(response.address['line'+lineCounter] !== undefined){
                            line.value(response.address['line'+lineCounter]);
                        }else{
                            line.value('');
                        }
                    });

                    var city = _.find(elements, function(element){ return element.index == 'city'; });
                    city.value(response.address.city);

                    var region = _.find(elements, function(element){ return element.index == 'region_id_input'; });
                    region.value(response.address.county);


                    self.toggleFieldVisibility(true);
                }else{
                    this.lookupError(response.content);
                    this.setLookupStateLookedUpError();
                }
            };
            return this;
        },

        lookupAddressClick: function(){
            this.setLookupStateLookingUp();
            this.selectedAddress(null);
            this.addressOptions.removeAll();

            // do some call away
            addressService.getAddresses(this.value(), this.handleResponse);
            return true;
        },

        inputAddressManuallyClick: function(){
            this.toggleFieldVisibility(true);
            return true;
        },

        lookupAddressById: function(id){
            this.setLookupStateReady();
            addressService.getAddressById(id, this.updateAddressData);
        }
    });
});

