<?php

namespace Codemonkey1988\FlexGrid\Form\Element;

/***************************************************************
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FrontendEditing\FrontendEditingController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Codemonkey1988\FlexGrid\Utility\PageUtility;

/**
 * Class ColumnConfiguratorResolver
 *
 * @package    Codemonkey1988\FlexGrid
 * @subpackage Form\Element
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class ColumnConfiguratorElement extends AbstractFormElement
{
    /**
     * Main result array as defined in initializeResultArray() of AbstractNode
     *
     * @var array
     */
    protected $resultArray;

    /**
     * An unique identifier based on field name to have id attributes in HTML referenced in javascript.
     *
     * @var string
     */
    protected $domIdentifier;

    /**
     * @return array
     */
    public function render(): array
    {
        $parameterArray = $this->data['parameterArray'];

        $this->domIdentifier = preg_replace('/[^a-zA-Z0-9_:.-]/', '_', $parameterArray['itemFormElName']);
        $this->domIdentifier = htmlspecialchars(preg_replace('/^[^a-zA-Z]/', 'x', $this->domIdentifier));

        $resultArray                                 = $this->initializeResultArray();
        $resultArray['stylesheetFiles'][]            = $this->getFullFileName('EXT:flex_grid/Resources/Public/Css/column-configurator.css');
        $resultArray['requireJsModules'][]           = 'TYPO3/CMS/FlexGrid/ColumnConfigurator';
        $resultArray['additionalJavaScriptSubmit'][] = $this->getSubmitJsCode();
        $resultArray['additionalJavaScriptPost'][]   = $this->getConfiguratorInitJsCode();

        $resultArray['html'] = $this->getMainHtml();

        return $resultArray;
    }

    /**
     * Make a file name relative to the PATH_site or to the PATH_typo3
     *
     * @param string $filename : a file name of the form EXT:.... or relative to the PATH_site
     * @return string the file name relative to the PATH_site if in frontend or relative to the PATH_typo3 if in backend
     */
    protected function getFullFileName($filename): string
    {
        if (substr($filename, 0, 4) === 'EXT:') {
            // extension
            list($extKey, $local) = explode('/', substr($filename, 4), 2);
            $newFilename = '';
            if ((string)$extKey !== '' && ExtensionManagementUtility::isLoaded($extKey) && (string)$local !== '') {
                $newFilename = ($this->isFrontendEditActive()
                        ? ExtensionManagementUtility::siteRelPath($extKey)
                        : ExtensionManagementUtility::extRelPath($extKey))
                    . $local;
            }
        } else {
            $path        = ($this->isFrontendEditActive() ? '' : '../');
            $newFilename = $path . ($filename[0] === '/' ? substr($filename, 1) : $filename);
        }

        return GeneralUtility::resolveBackPath($newFilename);
    }

    /**
     * Checks if frontend editing is active.
     *
     * @return bool TRUE if frontend editing is active
     */
    protected function isFrontendEditActive(): bool
    {
        return is_object($GLOBALS['TSFE']) && $GLOBALS['TSFE']->beUserLogin && $GLOBALS['BE_USER']->frontendEdit instanceof FrontendEditingController;
    }

    /**
     * @return string
     */
    protected function getSubmitJsCode(): string
    {
        $itemFormElementName = $this->data['parameterArray']['itemFormElName'];
        $itemFormElementId   = $this->data['parameterArray']['itemFormElID'];

        $result   = [];
        $result[] = "document.editform['" . $itemFormElementName . "'].value = document.getElementById('" . $itemFormElementId . "').value;";

        return implode(LF, $result);
    }

    /**
     * @return string
     */
    protected function getConfiguratorInitJsCode(): string
    {
        $globalConfig  = $this->getGlobalConfiguration();
        $currentConfig = $this->getCurrentColumnConfiguration();

        return 'require(["TYPO3/CMS/FlexGrid/ColumnConfigurator"], function (ColumnConfigurator) {
			ColumnConfigurator.init(' . json_encode($globalConfig) . ', ' . json_encode($currentConfig) . ');
			ColumnConfigurator.process();
		});';
    }

    /**
     * Return the global configuration that defines how many columns exists or each column configuration possibilities.
     *
     * @return array
     */
    protected function getGlobalConfiguration(): array
    {
        $config   = [];
        $tsconfig = BackendUtility::getPagesTSconfig(PageUtility::getCurrentPageUid());

        if (isset($tsconfig['mod.']['flex_grid.'])) {
            $config = GeneralUtility::removeDotsFromTS($tsconfig['mod.']['flex_grid.']);
            $config = $config['config'];
        }

        return $config;
    }

    /**
     * Return the current column configuration for this element.
     *
     * @return array
     */
    protected function getCurrentColumnConfiguration(): array
    {
        $config = [];

        if (!empty($this->data['databaseRow']['tx_flexgrid_config'])) {
            $config = json_decode($this->data['databaseRow']['tx_flexgrid_config'], true);

            if ($config === null) {
                $config = [];
            }
        }

        return $config;
    }

    /**
     * @return string
     */
    protected function getMainHtml(): string
    {
        $itemFormElementName     = $this->data['parameterArray']['itemFormElName'];
        $itemFormElementId       = $this->data['parameterArray']['itemFormElID'];
        $itemFormElementOnchange = $this->data['parameterArray']['fieldChangeFunc']['TBE_EDITOR_fieldChanged'];
        $rteDivStyle             = 'position:relative; left:0px; top:0px; height:200px; width:400px; border: 1px solid black; padding: 2;';

        $result   = [];
        $result[] = '<div id="column-configurator-' . $this->domIdentifier . '" class="column-configurator"></div>';
        $result[] = '<textarea ' . $this->getValidationDataAsDataAttribute($this->data['parameterArray']['fieldConf']['config']) . ' id="' . htmlspecialchars($itemFormElementId) . '" name="' . htmlspecialchars($itemFormElementName) . '" data-formengine-input-name="' . htmlspecialchars($itemFormElementName) . '" rows="0" cols="0" style="' . htmlspecialchars($rteDivStyle) . '" onchange="' . htmlspecialchars($itemFormElementOnchange) . '">';
        $result[] = htmlspecialchars($this->data['parameterArray']['itemFormElValue']);
        $result[] = '</textarea>';

        return implode(LF, $result);
    }
}