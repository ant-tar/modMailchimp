<?php
/**
 *
 * install.script.php
 * @package
 *
 * Created by JetBrains PhpStorm
 * Date: 14/04/12 4:17 PM
 *
 * http://www.bigblockstudios.ca
 * https://github.com/BigBlockStudios
 *
 */

$modx =& $object->xpdo;

$success = false;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {

    // actions on install or upgrade
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        // set our api key
        $object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.api_key'));
            if (!$object) {
                $object = $modx->newObject('modSystemSetting');
            }
            $object->fromArray(array(
                'key'=> 'modmailchimp.api_key',
                'name' => 'MailChimp API Key',
                'namespace' => 'modmailchimp',
                'area' => 'modmailchimp',
                'value' => $options['api_key'],
            ), '', true);
            $object->save();


        // set whether to allow overrides in the mailchimp tags
        $object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.allow_override'));
            if (!$object) {
                $object = $modx->newObject('modSystemSetting');
            }
            $object->fromArray(array(
                'key' => 'modmailchimp.allow_override',
                'name' => 'MailChimp Allow API Key Override',
                'value' => $options['allow_override'],
                'namespace' => 'modmailchimp',
                'area' => 'modmailchimp',
                'xtype' => 'combo-boolean',
            ), '', true);
            $object->save();


        // set the recaptcha public key
        $object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.recaptcha_public'));
            if (!$object) {
                $object = $modx->newObject('modSystemSetting');
            }
            $object->fromArray(array(
                'key' => 'modmailchimp.recaptcha_public',
                'name' => 'Recaptcha Public Key',
                'value' => $options['recaptcha_public'],
                'namespace' => 'modmailchimp',
                'area' => 'modmailchimp',
            ), '', true);
            $object->save();


        // set the recaptcha private key
        $object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.recaptcha_private'));
            if (!$object) {
                $object = $modx->newObject('modSystemSetting');
            }
            $object->fromArray(array(
                'key' => 'modmailchimp.recaptcha_private',
                'name' => 'Recaptcha Private Key',
                'value' => $options['recaptcha_private'],
                'namespace' => 'modmailchimp',
                'area' => 'modmailchimp',
            ), '', true);
            $object->save();


        // set whether recaptcha should use ssl
        /*
        $object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.recaptcha_use_ssl'));
            if (!$object) {
                $object = $modx->newObject('modSystemSetting');
            }
            $object->fromArray(array(
                'key' => 'modmailchimp.recaptcha_use_ssl',
                'name' => 'Recaptcha uses ssl?',
                'value' => $options['recaptcha_use_ssl'],
                'namespace' => 'modmailchimp',
                'area' => 'modmailchimp',
                'xtype' => 'combo-boolean',
            ), '', true);
            $object->save();
        */


        // set whether mailchimp should use ssl
        /*
        $object = $modx->getObject('modSystemSetting',array('key' => 'modmailchimp.mailchimp_use_ssl'));
            if (!$object) {
                $object = $modx->newObject('modSystemSetting');
            }
            $object->fromArray(array(
                'key' => 'modmailchimp.mailchimp_use_ssl',
                'name' => 'MailChimp uses ssl?',
                'value' => $options['mailchimp_use_ssl'],
                'namespace' => 'modmailchimp',
                'area' => 'modmailchimp',
                'xtype' => 'combo-boolean',
            ), '', true);
            $object->save();
        */


        $success = true;
        break;


    // actions on uninstall
    case xPDOTransport::ACTION_UNINSTALL:

        $success = true;
        break;
}

return $success;