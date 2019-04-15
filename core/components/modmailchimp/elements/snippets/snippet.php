<?php
// MailChimp stuff
$apiKey = $modx->getOption('apiKey', $scriptProperties, NULL);
$action = $modx->getOption('action', $scriptProperties, 'subscribe');
$listId = $modx->getOption('listId', $scriptProperties, FALSE);
$mergeTags = $modx->getOption('mergeTags', $scriptProperties, 'EMAIL');

// Recaptcha
$recaptcha = $modx->getOption('recaptcha', $scriptProperties, NULL);

// All the templates
$subscribeTpl = $modx->getOption('subscribeTpl', $scriptProperties, 'mmc_subscribe');
$unsubscribeTpl = $modx->getOption('unsubscribeTpl', $scriptProperties, 'mmc_unsubscribe');
$rowTpl = $modx->getOption('rowTpl', $scriptProperties, 'mmc_row');
$formName = $modx->getOption('formName', $scriptProperties, 'mailchimp');

// Resource ID to redirect to on success/failure. Default displays message
$successId = $modx->getOption('successId', $scriptProperties, NULL);
$failureId = $modx->getOption('failureId', $scriptProperties, NULL);

// Custom placeholder for success/failure messages
$placeholder = $modx->getOption('placeholder', $scriptProperties, NULL);

// Lists stuff
$listLists = $modx->getOption('listLists', $scriptProperties, NULL);
$listLabel = $modx->getOption('listLabel', $scriptProperties, 'Which List?');
$listControl = $modx->getOption('listControl', $scriptProperties, 'select');

return include($modx->getOption('core_path') . 'components/modmailchimp/elements/snippets/snippet.modMailchimp.php');