<?php
/**
 * Build the setup options form
 *
 * @package mailchimp
 * @subpackage build
 * @author Dave Shoreman <codeM0nK3Y@me.com>
 */

$output = <<<HTML
<label for="api_key">MailChimp API Key:</label>
<input type="text" name="api_key" id="api_key" width="300" />
HTML;

return $output;