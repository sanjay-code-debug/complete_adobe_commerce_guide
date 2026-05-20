define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'orico',
                component: 'CasioJP_Orico/js/view/payment/method-renderer/orico'
            }
        );
        return Component.extend({});
    }
);
