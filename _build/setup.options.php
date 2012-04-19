<?php
/**
*
* setup.options.php
* @package
*
* Created by JetBrains PhpStorm
* Date: 14/04/12 5:07 PM
*
* http://www.bigblockstudios.ca
* https://github.com/BigBlockStudios
*
*/


// grab an array of system options, note some are for future use
$options = array(
'apikey' => $modx->getOption('modmailchimp.api_key', null, ''),
'override' => $modx->getOption('modmailchimp.allow_override', null, ''),
'publickey' => $modx->getOption('modmailchimp.recaptcha_public', null, ''),
'privatekey' => $modx->getOption('modmailchimp.recaptcha_private', null, ''),
'rcssl' => $modx->getOption('modmailchimp.recaptcha_use_ssl', null, ''),
'mcssl' => $modx->getOption('modmailchimp.mailchimp_use_ssl', null, ''),
);

$output = '';

//this html will get spat out to the options popup on install
$output .= '<label for="api_key">MailChimp API Key:</label>
	<input type="text" name="api_key" id="api_key" width="300" value="'.$options['apikey'].'" />
	<br><br>';

if($options["override"] == "true"){$tr=' checked';$fls='';}else{$tr='';$fls=' checked';}
$output .= '<label for="allow_override">MailChimp Allow API Key Override:</label>
	<input type="radio" name="allow_override" id="allow_override_no" value="0" style="display: inline" '.$fls.' />
	<label for="allow_override_no" style="display: inline">No</label>

	<input type="radio" name="allow_override" id="allow_override_yes" value="1" style="display: inline" '.$tr.' />
	<label for="allow_override_yes" style="display: inline">Yes</label>
	<br><br>';

$output .= '<label for="recaptcha_public">Recaptcha Public Key:</label>
	<input type="text" name="recaptcha_public" id="recaptcha_public" width="300" value="'.$options['publickey'].'" />
	<br><br>';

$output .= '<label for="recaptcha_private">Recaptcha Private Key:</label>
	<input type="text" name="recaptcha_private" id="recaptcha_private" width="300" value="'.$options['privatekey'].'" />
	<br><br>';

// option for reCAPTCHA to use SSL ~ Not Implemented
/*
if($options["rcssl"] == "true"){$tr=' checked';$fls='';}else{$tr='';$fls=' checked';}
$output .= '<label for="allow_override">Use SSL for reCAPTCHA? [not implemented]:</label>
	<input type="radio" name="recaptcha_use_ssl" id="recaptcha_use_ssl_no" value="false" style="display: inline" '.$fls.' />
	<label for="recaptcha_use_ssl_no" style="display: inline">No</label>

	<input type="radio" name="recaptcha_use_ssl" id="recaptcha_use_ssl_yes" value="true" style="display: inline"'.$tr.' />
	<label for="recaptcha_use_ssl_yes" style="display: inline">Yes</label>
	<br><br>';
*/

// option for MailChimp to use SSL ~ Not Implemented
/*
if($options["mcssl"] == "true"){$tr=' checked';$fls='';}else{$tr='';$fls=' checked';}
$output .= '<label for="allow_override">Use SSL for MailChimp? [not implemented]:</label>
    <input type="radio" name="mailchimp_use_ssl" id="mailchimp_use_ssl_no" value="false" style="display: inline" '.$fls.' />
	<label for="mailchimp_use_ssl_no" style="display: inline">No</label>

	<input type="radio" name="mailchimp_use_ssl" id="mailchimp_use_ssl_yes" value="true" style="display: inline" '.$tr.' />
	<label for="mailchimp_use_ssl_yes" style="display: inline">Yes</label>
	<br><br>';
*/

return $output;
