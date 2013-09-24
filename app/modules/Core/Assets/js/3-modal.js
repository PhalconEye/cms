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

// Support for AJAX loaded modal window.
PE.modal = {
    init:function (elements) {
        $(elements).unbind('click');
        $(elements).click(function (e) {
            var url = $(this).attr('href');
            if (url.indexOf('#') == 0) {
                $(url).modal('open');
            } else {
                PE.modal.open(url, {});
            }

            return false;
        });
    },

    open:function (url, data) {
        $.get(url, data)
            .done(function (html) {
                var modal = $('<div id="modal" class="modal hide fade" tabindex="1" role="dialog" aria-labelledby="modal_label" aria-hidden="true">' + html + '</div>').filter('.modal');
                modal.modal({
                    keyboard:false
                });

                modal.on('focus', function(e){ // focus bug workaround
                    e.preventDefault();
                });

                modal.on('shown', function () {
                    // set removing
                    $('#modal').on('hidden', function () {
                        $(this).remove();
                    });

                    // prevent background from simple closing
                    $('.modal-backdrop').unbind('click');
                    $('.modal-backdrop').click(function (e) {
                        e.preventDefault();
                        if (confirm('Close this window?')) {
                            $('#modal').modal('hide');
                        }
                        return false;
                    });


                    PE.modal._bindSubmit();
                    PE.core.initAutocomplete();

                    // Evaluate js
                    $(html).filter("script").each(function() {
                        var scriptContent = $(this).html(); //Grab the content of this tag
                        eval(scriptContent); //Execute the content
                    });
                })

            });
    },

    _bindSubmit:function () {
        // set submiting
        $('#modal .btn-save').click(function () {
            if ($('#modal form').length == 1) {
                // check ckeditor
                if (Object.keys(CKEDITOR.instances).length > 0){
                    for (var instance in CKEDITOR.instances) {
                        var elementId = '#'+CKEDITOR.instances[instance].name;
                        $(elementId).val(CKEDITOR.instances[instance].getData());
                    }
                }


                $.post($('#modal form').attr('action'), $('#modal form').serialize())
                    .done(function (postHTML) {
                        $('#modal').html(postHTML);
                        PE.modal._bindSubmit();
                        PE.core.initAutocomplete();
                    });
            }
            else {
                $('#modal').modal('hide');
            }
        });

        $('#modal form').submit(function() {
            $('#modal .btn-save').click();
            return false;
        });

        // chkeditor save button
        setTimeout((function(){
            if (Object.keys(CKEDITOR.instances).length > 0){
                for (var instance in CKEDITOR.instances) {
                    if (CKEDITOR.instances[instance].commands.save){
                        CKEDITOR.instances[instance].commands.save.exec = function(){
                            $('#modal .btn-save').click();
                        }
                    }
                }
            }
        }), 1000);
    }



};
