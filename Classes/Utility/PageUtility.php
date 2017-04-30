<?php

namespace Codemonkey1988\FlexGrid\Utility;

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

/**
 * Class PageUtility
 *
 * @package    Codemonkey1988\FlexGrid
 * @subpackage Utility
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class PageUtility
{
    /**
     * @param int $default
     * @return int
     */
    public static function getCurrentPageUid($default = 1)
    {
        $pageUid = 0;

        if (TYPO3_MODE === 'FE') {
            if (array_key_exists('TSFE', $GLOBALS) && is_object($GLOBALS['TSFE'])) {
                $pageUid = $GLOBALS['TSFE']->id;
            }
        } elseif (TYPO3_MODE === 'BE') {
            if (isset($GLOBALS['_GET']['id'])) {
                $pageUid = (int)$GLOBALS['_GET']['id'];
            } else if (isset($GLOBALS['SOBE'])) {
                $data = $GLOBALS['SOBE']->data;

                if (isset($data['tt_content']) && is_array($data['tt_content'])) {
                    $pageUid = (int)$data['tt_content'][array_keys($data['tt_content'])[0]]['pid'];
                }
            }
        }

        return max($pageUid, $default);
    }
}