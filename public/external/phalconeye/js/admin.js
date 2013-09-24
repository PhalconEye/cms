/**
 * PhalconEye
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to phalconeye@gmail.com so we can send you a copy immediately.
 *
 */

var PE = PE || {};

PE.admin = {

};


PE.admin.performance = {
    init:function () {
        if (!$('#adapter')) return;
        $('#adapter').change(function () {
            PE.admin.performance.checkAdapter();
        });
        PE.admin.performance.checkAdapter();
    },
    hideOptions:function () {
        $('#cacheDir').parent().parent().hide();
        $('#host').parent().parent().hide();
        $('#port').parent().parent().hide();
        $('#persistent').parent().parent().hide();
        $('#server').parent().parent().hide();
        $('#db').parent().parent().hide();
        $('#collection').parent().parent().hide();
    },

    fileOptions:function () {
        $('#cacheDir').parent().parent().show();
    },

    memcachedOptions:function () {
        $('#host').parent().parent().show();
        $('#port').parent().parent().show();
        $('#persistent').parent().parent().show();
    },

    mongoOptions:function () {
        $('#server').parent().parent().show();
        $('#db').parent().parent().show();
        $('#collection').parent().parent().show();
    },

    checkAdapter:function () {
        var value = $('#adapter').val();
        PE.admin.performance.hideOptions();
        switch (value) {
            case '0':
                PE.admin.performance.fileOptions();
                break;
            case '1':
                PE.admin.performance.memcachedOptions();
                break;
            case '3':
                PE.admin.performance.mongoOptions();
                break;
        }
    }
};


$(document).ready(function () {
    PE.admin.performance.init();
    PE.modal.init('[data-toggle="modal"]');
});