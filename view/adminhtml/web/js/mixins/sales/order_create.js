define(
    [
        'mage/utils/wrapper'
    ],
    function (wrapper) {
        'use strict';

        return function () {
            window.AdminOrder.prototype.switchPaymentMethod = wrapper.wrap(window.AdminOrder.prototype.switchPaymentMethod, function (originalAction, method) {
                var elem = jQuery('#edit_form');
                elem.off('submitOrder')
                    .on('submitOrder', function(){
                        jQuery(this).trigger('realOrder');
                    });
                elem.trigger('changePaymentMethod', [method]);
                this.setPaymentMethod(method);
                var data = {};
                data['order[payment_method]'] = method;
                this.loadArea(['card_validation', 'totals'], true, data);
            });
        }
    }
);
