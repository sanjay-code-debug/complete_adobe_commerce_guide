define(
    [
        'jquery',
        'mage/translate',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote'
    ],
    function ($,$t,Component,quote) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'CasioJP_Orico/payment/orico'
            },
            /**
             * Get payment method description.
             */
            getDescriptionForOrico: function () {
                var quoteData = quote.totals();
                var amount = Math.round(quoteData.grand_total);
                var oneInstall = amount/12;
                var result =  this.toCommas(Math.ceil(oneInstall));
                return $t('¥ %1 / month').replace('%1', result)+window.checkoutConfig.myCustomData;
            },
            toCommas: function (oneInstall) {
                return oneInstall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });
    }
);
