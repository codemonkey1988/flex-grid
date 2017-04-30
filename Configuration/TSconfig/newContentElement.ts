mod.wizards.newContentElement.wizardItems {
	special {
		elements {
			flex_grid {
				iconIdentifier = flexgrid-default
				title = LLL:EXT:flex_grid/Resources/Private/Language/locallang_be.xlf:grid.title
				description = LLL:EXT:flex_grid/Resources/Private/Language/locallang_be.xlf:grid.description
				tt_content_defValues {
					CType = flex-grid
				}
			}
		}
		show := addToList(flex_grid)
	}
}