<?php
$output = '';

if ($modx->getOption('modmailchimp.api_key', null, '') == ''):
$output .= <<<HTML
	<label for="api_key">MailChimp API Key:</label>
	<input type="text" name="api_key" id="api_key" width="300" />
	<br><br>
HTML;
endif;

if ($modx->getOption('modmailchimp.allow_override', null, '') == ''):
$output .= <<<HTML
	<label for="allow_override">MailChimp Allow API Key Override:</label>
	<input type="radio" name="allow_override" id="allow_override_yes" value="1" style="display: inline" checked /> <label for="allow_override_yes" style="display: inline">Yes</label>
	<input type="radio" name="allow_override" id="allow_override_no" value="0" style="display: inline" /> <label for="allow_override_no" style="display: inline">No</label>
	<br><br>
HTML;
endif;

if ($modx->getOption('modmailchimp.recaptcha_public', null, '') == ''):
$output .= <<<HTML
	<label for="recaptcha_public">Recaptcha Public Key:</label>
	<input type="text" name="recaptcha_public" id="recaptcha_public" width="300" />
	<br><br>
HTML;
endif;

if ($modx->getOption('modmailchimp.recaptcha_private', null, '') == ''):
$output .= <<<HTML
	<label for="recaptcha_private">Recaptcha Private Key:</label>
	<input type="text" name="recaptcha_private" id="recaptcha_private" width="300" />
	<br><br>
HTML;
endif;

return $output;