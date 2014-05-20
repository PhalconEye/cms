/*
 +------------------------------------------------------------------------+
 | PhalconEye CMS                                                         |
 +------------------------------------------------------------------------+
 | Copyright (c) 2013-2014 PhalconEye Team (http://phalconeye.com/)       |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file LICENSE.txt.                             |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to license@phalconeye.com so we can send you a copy immediately.       |
 +------------------------------------------------------------------------+
 | Author: Piotr Gasiorowski <p.gasiorowski@vipserv.org>                  |
 +------------------------------------------------------------------------+
 */

/**
 * Dynamic form field.
 *
 * @category  PhalconEye
 * @package   PhalconEye Core Module
 * @author    Piotr Gasiorowski <p.gasiorowski@vipserv.org>
 * @copyright Copyright (c) 2013-2014 PhalconEye Team
 * @license   New BSD License
 * @link      http://phalconeye.com/
 */

(function (window, $, root) {
    root.ns(
        'PhalconEye.form.dynamicField',
        {
            /**
             * Constants.
             */
            _const: {
                addButton: 'glyphicon-plus-sign',
                delButton: 'glyphicon-minus-sign',
                controls: 'form_dynamic_controls'
            },

            /**
             * Init dynamic field.
             *
             * @param scope Element object.
             */
            init: function (scope) {

                scope = $(scope);

                var $this = this,
                    controls = scope.find('.' + this._const.controls);

                // Init controls area
                if (controls.length == 0) {
                    controls = $('<div></div>').addClass(this._const.controls).appendTo(scope);
                }

                // Create Add button if possible
                if (this._canAdd(scope)) {
                    if (controls.find('.' + this._const.addButton).length == 0) {
                        $('<span></span>')
                            .addClass(this._const.addButton)
                            .appendTo(controls)
                            .on('click', function() {
                                $this.addElementTo(scope);
                            });
                    }
                } else {
                    controls.find('.' + this._const.addButton).remove();
                }

                // Create Remove button if possible
                if (this._canRemove(scope)) {
                    if (controls.find('.' + this._const.delButton).length == 0) {
                        $('<span></span>')
                            .addClass(this._const.delButton)
                            .appendTo(controls)
                            .on('click', function() {
                                $this.removeElementFrom(scope);
                            });
                    }
                } else {
                    controls.find('.' + this._const.delButton).remove();
                }
            },

            /**
             * Adds new element into the scope
             *
             * @param scope Element object.
             */
            addElementTo: function (scope) {

                if (this._canAdd(scope) == false) {
                    return;
                }

                var name = scope.data('dynamic-field'),
                    element = $('[name="'+ name +'\\[\\]"]', scope).first();

                // Clone first available element and append at the end
                element
                    .clone()
                    .val('')
                    .insertBefore(scope.find('.' + this._const.controls));

                this.init(scope)
            },

            /**
             * Removes last element from the scope
             *
             * @param scope Element object.
             */
            removeElementFrom: function (scope) {

                if (this._canRemove(scope) == false) {
                    return;
                }

                var name = scope.data('dynamic-field'),
                    element = $('[name="'+ name +'\\[\\]"]', scope).last();

                // Remove the last element
                element.remove();

                this.init(scope);
            },

            /**
             * Get current count of elements within scope
             *
             * @param scope Element object.
             *
             * @private
             * @returns bool
             */
            _getCurrentCount: function(scope) {
                return $('[name="'+ scope.data('dynamic-field') +'\\[\\]"]', scope).length;
            },

            /**
             * Check if a new element can be added
             *
             * @param scope Element object.
             *
             * @private
             * @returns bool
             */
            _canAdd: function(scope) {
                return (this._getCurrentCount(scope) < (scope.data('dynamic-max') || 2));
            },

            /**
             * Check if the last element can be removed from scope
             *
             * @param scope Element object.
             *
             * @private
             * @returns bool
             */
            _canRemove: function(scope) {
                return (this._getCurrentCount(scope) > (scope.data('dynamic-min') || 1));
            }
        }
    );
}(window, jQuery, PhalconEye));
