<?php

/**
 * This file is part of contao-community-alliance/dc-general.
 *
 * (c) 2013-2018 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/dc-general
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2013-2018 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/dc-general/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace ContaoCommunityAlliance\DcGeneral\Contao\Dca\Populator;

use ContaoCommunityAlliance\DcGeneral\Contao\RequestScopeDeterminator;
use ContaoCommunityAlliance\DcGeneral\Contao\RequestScopeDeterminatorAwareTrait;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;

/**
 * Compatibility class for Contao to have the $GLOBALS['TL_DCA'] populated for pickers as those are hardcoded within
 * the handler files TL_ROOT/contao/*.php.
 */
class PickerCompatPopulator extends AbstractEventDrivenBackendEnvironmentPopulator
{
    use RequestScopeDeterminatorAwareTrait;

    /**
     * PickerCompatPopulator constructor.
     *
     * @param RequestScopeDeterminator $scopeDeterminator The request mode determinator.
     */
    public function __construct(RequestScopeDeterminator $scopeDeterminator)
    {
        $this->setScopeDeterminator($scopeDeterminator);
    }

    /**
     * Create a controller instance in the environment if none has been defined yet.
     *
     * @param EnvironmentInterface $environment The environment to populate.
     *
     * @return void
     *
     * @internal
     */
    public function populate(EnvironmentInterface $environment)
    {
        if (!$this->scopeDeterminator->currentScopeIsBackend()) {
            return;
        }

        $this->populateFilePickers($environment);
    }

    /**
     * Populate the file picker $GLOBALS['TL_DCA'] to make the contao/file.php happy.
     *
     * The backend file contao/file.php is using hard coded direct array access on the TL_DCA array therefore we need to
     * dump all of this there.
     *
     * @param EnvironmentInterface $environment The environment.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function populateFilePickers(EnvironmentInterface $environment)
    {
        $definition = $environment->getDataDefinition();
        $name       = $definition->getName();
        if (!isset($GLOBALS['TL_DCA'])) {
            $GLOBALS['TL_DCA'] = [];
        }

        if (!isset($GLOBALS['TL_DCA'][$name])) {
            $GLOBALS['TL_DCA'][$name] = [];
        }

        if (!isset($GLOBALS['TL_DCA'][$name]['fields'])) {
            $GLOBALS['TL_DCA'][$name]['fields'] = [];
        }

        $dca = &$GLOBALS['TL_DCA'][$name]['fields'];

        foreach ($environment->getDataDefinition()->getPropertiesDefinition()->getProperties() as $property) {
            if ($property->getWidgetType() == 'fileTree') {
                $dca[$property->getName()]['eval'] = $property->getExtra();
            }
        }
    }
}