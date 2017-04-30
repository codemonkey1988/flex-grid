
define(['jquery', 'TYPO3/CMS/FlexGrid/ColumnConfigurator/ActionButtons'], function($, $buttons) {
	var ColumnConfigurator = {};

	ColumnConfigurator.init = function(globalConfig, columnConfig) {
		"use strict";
		console.log(globalConfig, columnConfig);
		$buttons.init();
	};

	ColumnConfigurator.process = function() {
		"use strict";

	};

	return ColumnConfigurator;
});