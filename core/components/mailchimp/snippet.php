<?php
/**
 * MailChimp Snippet
 *
 * @package mailchimp
 * @subpackage snippet
 * @author Dave Shoreman <codeM0nK3Y@me.com>
 */

// MailChimp stuff
$apiKey = $modx->getOption('apiKey', $scriptProperties, NULL);
$listId = $modx->getOption('listId', $scriptProperties, FALSE);
$mergeTags = $modx->getOption('mergeTags', $scriptProperties, 'EMAIL');

// All the templates
$subscribeTpl = $modx->getOption('subscribeTpl', $scriptProperties, 'mc_subscribe');
$rowTpl = $modx->getOption('rowTpl', $scriptProperties, 'mc_row');

// Resource ID to redirect to on success/failure. Default displays message
$successId = $modx->getOption('successId', $scriptProperties, NULL);
$failureId = $modx->getOption('failureId', $scriptProperties, NULL);

return require_once($modx->getOption('core_path') . 'components/mailchimp/elements/snippets/snippet.mailchimp.php');