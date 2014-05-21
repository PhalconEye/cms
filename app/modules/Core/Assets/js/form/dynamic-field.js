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
             * Elements
             */
            _elements: {
                controls: $('<div></div>').addClass('dynamic-controls'),
                addControl: $('<a></a>').addClass('dynamic-add btn btn-primary'),
                delControl: $('<a></a>').addClass('dynamic-del btn btn-danger'),
                addButton: $('<i></i>').addClass('glyphicon glyphicon-plus-sign'),
                delButton: $('<i></i>').addClass('glyphicon glyphicon-minus-sign')
            },

            /**
             * Init dynamic field.
             *
             * @param scope Element object.
             */
            init: function (scope) {

                scope = $(scope);

                var $this = this,
                    controls = scope.find('.dynamic-controls');

                // Init controls area
                if (controls.length == 0) {
                    controls = this._elements.controls.clone().appendTo(scope);
                }

                // Create Add button if possible
                if (this._canAdd(scope)) {
                    if (controls.find('.dynamic-add').length == 0) {

                        $this._elements.addControl
                            .clone()
                            .append($this._elements.addButton.clone())
                            .append(root.i18n._('Add'))
                            .prependTo(controls)
                            .on('click', function() {
                                $this.addElementTo(scope);
                            });
                    }
                } else {
                    controls.find('.dynamic-add').remove();
                }

                // Create Remove button if possible
                if (this._canRemove(scope)) {
                    if (controls.find('.dynamic-del').length == 0) {

                        $this._elements.delControl
                            .clone()
                            .append($this._elements.delButton.clone())
                            .append(root.i18n._('Delete'))
                            .appendTo(controls)
                            .on('click', function() {
                                $this.removeElementFrom(scope);
                            });
                    }
                } else {
                    controls.find('.dynamic-del').remove();
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

                var name = scope.data('dynamic'),
                    element = $('[name="'+ name +'"]', scope).first();

                // Clone first available element and append at the end
                element
                    .clone()
                    .val('')
                    .insertBefore(scope.find('.dynamic-controls'));

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

                var name = scope.data('dynamic'),
                    element = $('[name="'+ name +'"]', scope).last();

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
                return $('[name="'+ scope.data('dynamic') +'"]', scope).length;
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
