/**
 * BlackChacal_Prometheus
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Prometheus to newer
 * versions in the future. If you wish to customize Prometheus for your
 * needs please contact the author for more information.
 *
 * @category    BlackChacal
 * @package     BlackChacal_Prometheus
 * @copyright   Copyright (c) 2015 BlackChacal <ribeiro.tonet@gmail.com>
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */


/**
 * This file contains several Js functions necessary for the proper working of the BlackChacal_Prometheus extension.
 */

/**
 * Sets the default configuration tab name based on the configuration tab type value. It sets the value on page load and
 * when the configuration tab type value changes.
 *
 * @public
 *
 * @param {string} onloadName
 * @param {string} namespaceName
 * @param {string} namespaceLabel
 * @param {string} systemName
 * @param {string} systemLabel
 * @return {void}
 */
function setDefaultConfigTabName(onloadName, namespaceName, namespaceLabel, systemName, systemLabel)
{
    document.observe('dom:loaded', function() {
        $('config_system_tab_name').setValue(onloadName);
        $('config_tab_type').on('change', function () {
            var name,
                label,
                $tabType = $('config_tab_type').getValue();

            switch ($tabType) {
                case 'system':
                    name = systemName;
                    label = systemLabel;
                    break;
                case 'namespace':
                    name = namespaceName;
                    label = namespaceLabel;
                    break;
                default:
                    name = '';
                    label = '';
                    break;
            }
            $('config_tab_name').setValue(name);
            $('config_tab_label').setValue(label);
            $('config_system_tab_name').setValue(name);
        });
    });
}

/**
 * Sets the default configuration system tab label when the configuration tab name select changes.
 *
 * @public
 *
 * @return {void}
 */
function setDefaultConfigSystemTabLabel()
{
    document.observe('dom:loaded', function() {
        $('config_system_tab_name').on('change', function () {
            var label = this.options[this.selectedIndex].innerHTML;
            $('config_tab_label').setValue(label);
        });
    });
}