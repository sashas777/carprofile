/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
define(
    [
        'Razoyo_CarProfile/js/model/url-builder',
        'Magento_Customer/js/customer-data',
        'Magento_Ui/js/model/messageList',
        'mage/translate',
        'mage/storage'
    ],
    function (
        urlBuilder,
        customerData,
        globalMessageList,
        $t,
        storage
    ) {
        'use strict';
        return function (ObservableObject, method, params= {}) {
            var serviceUrl;

            serviceUrl =  urlBuilder.createUrl('/razoyo/car/'+method, {});

            globalMessageList.clear();

            return storage.post(
                serviceUrl,
                JSON.stringify(params),
                true
            ).fail(
                function () {
                    globalMessageList.addErrorMessage({
                        'message': $t('Could not load car(s) information. Please try again later')
                    });
                }
            ).done(
                function (response) {
                    ObservableObject(response);
                }
            );
        };
    }
);
