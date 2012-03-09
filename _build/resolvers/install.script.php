<?php
/**
 * Adds system settings
 *
 * @package mailchimp
 * @subpackage build
 * @author Dave Shoreman <codeM0nK3Y@me.com>
 */

$modx =& $object->xpdo;
$success = false;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	case xPDOTransport::ACTION_INSTALL: 
	case xPDOTransport::ACTION_UPGRADE:
		// API Key
		$object = $modx->newObject('modSystemSetting');
		$object->set('name', 'MailChimp API Key');
		$object->set('key', 'mailchimp.api_key');
		$object->set('value', $options['api_key']);
		$object->set('namespace', 'mailchimp');
		$object->set('area', 'mailchimp');
		$object->save();


		$success = true;
		break;
	case xPDOTransport::ACTION_UNINSTALL:
		$success = true;
		break;
}

return $success;