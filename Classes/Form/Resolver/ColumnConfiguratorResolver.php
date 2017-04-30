<?php

namespace Codemonkey1988\FlexGrid\Form\Resolver;

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

use Codemonkey1988\FlexGrid\Form\Element\ColumnConfiguratorElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Backend\Form\NodeResolverInterface;

/**
 * Class ColumnConfiguratorResolver
 *
 * @package    Codemonkey1988\FlexGrid
 * @subpackage Form\Resolver
 * @author     Tim Schreiner <schreiner.tim@gmail.com>
 */
class ColumnConfiguratorResolver implements NodeResolverInterface
{
    /**
     * Global options from NodeFactory
     *
     * @var array
     */
    protected $data;

    /**
     * Default constructor receives full data array
     *
     * @param NodeFactory $nodeFactory
     * @param array       $data
     */
    public function __construct(NodeFactory $nodeFactory, array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function resolve()
    {
        $parameterArray = $this->data['parameterArray'];

        if (isset($parameterArray['fieldConf']['defaultExtras']) && $parameterArray['fieldConf']['defaultExtras'] == 'column_configurator') {
            return ColumnConfiguratorElement::class;
        }

        return null;
    }
}