<?php
$snippet = 'message';
$in_snippet = true;
//include $modx->getOption('core_path') . 'components/modmailchimp/index.php';

$errorCode =  (isset($_SESSION['mailchimp_error_code']) ? $_SESSION['mailchimp_error_code'] : '');
$errorMessage =  (isset($_SESSION['mailchimp_error_message']) ? $_SESSION['mailchimp_error_message'] : '');
$success =  (isset($_SESSION['mailchimp_success']) ? $_SESSION['mailchimp_success'] : '');

$modx->setPlaceholder('errorCode', $errorCode);
$modx->setPlaceholder('errorMessage', $errorMessage);
$modx->setPlaceholder('success', $success);

$message = $errorMessage ? $errorCode.' '.$errorMessage : ($success ? $success : '');

if(isset($_SESSION['mailchimp_placeholder'])) $modx->setPlaceholder($_SESSION['mailchimp_placeholder'], $message);

unset($_SESSION['mailchimp_error_code'], $_SESSION['mailchimp_error_code'], $_SESSION['mailchimp_success'], $_SESSION['mailchimp_placeholder']);

return (!isset($type) || $type == NULL) ? $message : '';