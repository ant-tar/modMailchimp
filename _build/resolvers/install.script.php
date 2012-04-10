<?php
$modx =& $object->xpdo;
$success = false;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	case xPDOTransport::ACTION_INSTALL:
		// API Key (skip if already set)
		if ($modx->getOption('modmailchimp.api_key', null, '') == '') {
			$object = $modx->newObject('modSystemSetting');
			$object->set('name', 'MailChimp API Key');
			$object->set('key', 'modmailchimp.api_key');
			$object->set('value', $options['api_key']);
			$object->set('namespace', 'modmailchimp');
			$object->set('area', 'modmailchimp');
			$object->save();
		}

		if ($modx->getOption('modmailchimp.allow_override', null, '') == '') {
			$object = $modx->newObject('modSystemSetting');
			$object->set('name', 'MailChimp Allow API Key Override');
			$object->set('key', 'modmailchimp.allow_override');
			$object->set('value', $options['allow_override']);
			$object->set('namespace', 'modmailchimp');
			$object->set('area', 'modmailchimp');
			$object->set('xtype', 'combo-boolean');
			$object->save();
		}

		if ($modx->getOption('modmailchimp.recaptcha_public', null, '') == '') {
			$object = $modx->newObject('modSystemSetting');
			$object->set('name', 'Recaptcha Public Key');
			$object->set('key', 'modmailchimp.recaptcha_public');
			$object->set('value', $options['recaptcha_public']);
			$object->set('namespace', 'modmailchimp');
			$object->set('area', 'modmailchimp');
			$object->save();
		}

		if ($modx->getOption('modmailchimp.recaptcha_private', null, '') == '') {
			$object = $modx->newObject('modSystemSetting');
			$object->set('name', 'Recaptcha Private Key');
			$object->set('key', 'modmailchimp.recaptcha_private');
			$object->set('value', $options['recaptcha_private']);
			$object->set('namespace', 'modmailchimp');
			$object->set('area', 'modmailchimp');
			$object->save();
		}

		$success = true;
		break;
	case xPDOTransport::ACTION_UPGRADE:
		$success = true;
		break;
	case xPDOTransport::ACTION_UNINSTALL:
		$success = true;
		break;
}

return $success;