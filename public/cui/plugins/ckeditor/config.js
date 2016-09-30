/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		//{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	//config.removeDialogTabs = 'image:advanced;image:info;';
	 // Make dialogs simpler.
    config.removeDialogTabs = 'image:advanced;image:Link;link:advanced;link:upload'; //;image:info';
    config.linkShowTargetTab = false;
	
	//config.extraPlugins = 'uploadimage';
	config.uploadUrl = '/uploader/upload.php';
	config.filebrowserImageWindowWidth = '640';
    config.filebrowserImageWindowHeight = '480';
   // config.uiColor = '#AADC6E';
    config.extraPlugins = 'divarea';
    
    config.removeButtons = 'Subscript,Superscript,Scayt,Anchor,Outdent,Indent,Blockquote,About,PasteFromWord,Source';
	config.removePlugins = 'elementspath';
	config.resize_enabled = false;
	config.toolbarCanCollapse = true;
	config.toolbarStartupExpanded = false;
	

};

