<?php
$type = $modx->getOption('type', $scriptProperties, NULL);

return include($modx->getOption('core_path') . 'components/modmailchimp/elements/snippets/snippet.modMailchimpMessage.php');