<?php
/**
 *
 * default.inc.php
 *
 * Created using: JetBrains PhpStorm
 * User: Sean Kimball sean@bigblockstudios.ca
 * BigBlock Studios http://www.bigblockstudios.ca
 * http://github.com/BigBlockStudios
 * Date: 15/04/12, 3:59 PM
 *
 */

// for testing lexicon strings, just unset when done testing:
$lex = 'Lexicon said: ';


$_lang['modmailchimp'] = $lex . 'MailChimp';

// api messages
$_lang['modmailchimp.apikey_missing'] = $lex . 'Please specify a MailChimp api key.';
$_lang['modmailchimp.apikey_invalid'] = $lex . 'Please specify a valid MailChimp api key.';

// list errors
$_lang['modmailchimp.list_id_invalid'] = $lex . 'Please specify a valid MailChimp list ID.';

// recaptcha errors
$_lang['modmailchimp.recaptcha_incorrect'] = $lex . 'The reCAPTCHA wasn\'t entered correctly. Go back and try it again. (reCAPTCHA said: ';
$_lang['modmailchimp.recaptcha_enter_text'] = $lex . 'Enter this text.';

// email address messages
$_lang['modmailchimp.email_address_missing'] = $lex . 'Please enter an email address.';
$_lang['modmailchimp.email_address_pending'] = $lex . 'This address is currently pending, please check for your confirmation email.';

// misc errors
$_lang['modmailchimp.error_unknown'] = $lex . 'Unknown error';

// misc success messages
$_lang['modmailchimp.success_subscription'] = $lex . 'Thanks! Please check your email to confirm your subscription.';
$_lang['modmailchimp.success_unsubscribe'] = $lex . 'Unsubscribe successful.';

