<?php

/**
 * This file is part of contao-community-alliance/dc-general.
 *
 * (c) 2013-2019 Contao Community Alliance.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contao-community-alliance/dc-general
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2013-2019 Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/dc-general/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace ContaoCommunityAlliance\DcGeneral\Contao\Callback;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\ParentViewChildRecordEvent;

/**
 * Class ModelChildRecordCallbackListener.
 *
 * Handler for the child record callbacks.
 */
class ModelChildRecordCallbackListener extends AbstractReturningCallbackListener
{
    /**
     * Retrieve the arguments for the callback.
     *
     * @param ParentViewChildRecordEvent $event The event being emitted.
     *
     * @return array
     */
    public function getArgs($event)
    {
        return [
            $event->getModel()->getPropertiesAsArray()
        ];
    }

    /**
     * Set the HTML code for the button.
     *
     * @param ParentViewChildRecordEvent $event The event being emitted.
     * @param string                     $value The value returned by the callback.
     *
     * @return void
     */
    public function update($event, $value)
    {
        if (null === $value) {
            return;
        }

        $event->setHtml($value);
        $event->stopPropagation();
    }
}
