<?php
// MailChimp stuff
$apiKey = $modx->getOption('apiKey', $scriptProperties, NULL);
$lists = $modx->getOption('lists', $scriptProperties, NULL);
$control = $modx->getOption('control', $scriptProperties, 'select');

// All the templates
$rowTpl = $modx->getOption('rowTpl', $scriptProperties, 'mmc_row');
$label = $modx->getOption('label', $scriptProperties, 'Which list?');

return include($modx->getOption('core_path') . 'components/modmailchimp/elements/snippets/snippet.modMailchimpLists.php');