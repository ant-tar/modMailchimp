<?php
/**
 *
 * transport.settings.php
 * @package modMailchimp
 *
 * Created by JetBrains PhpStorm
 * Date: 14/04/12 8:35 PM
 *
 * http://www.bigblockstudios.ca
 * https://github.com/BigBlockStudios
 *
 */

$object = array();

$object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.mmc_core_path'));
    if (!$object) {
        $object = $modx->newObject('modSystemSetting');
    }
    $object->fromArray(array(
        'key' => 'modmailchimp.mmc_core_path',
        'value' => $modx->getOption('core_path') . 'components/modmailchimp/',
        'xtype' => 'textfield',
        'namespace' => 'modmailchimp',
        'area' => 'modmailchimp',
    ), '', true);

    $object->save();


return $object;