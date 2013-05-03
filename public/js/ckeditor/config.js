/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.filebrowserBrowseUrl = '/external/ajaxplorer/?external_selector_type=ckeditor&relative_path=/public/files';
	config.filebrowserImageBrowseUrl = '/external/ajaxplorer/?external_selector_type=ckeditor&relative_path=/public/files';
    config.filebrowserUploadUrl = '/external/ajaxplorer/?external_selector_type=ckeditor&relative_path=/public/files';
};
