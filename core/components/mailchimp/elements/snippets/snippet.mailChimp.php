<?php
/**
 *
 * @package mailchimp
 * @subpackage snippet
 * @author Dave Shoreman <codeM0nK3Y@me.com>
 */

function get_field ($list_field) {
	extract($list_field);

	switch ($field_type) {
		case 'radio': {
			$field = '<p>';
			foreach ($choices as $opt_id => $option) $field.= '<input type="radio" name="margevars[' . $tag . ']" value="' . $opt_id . '" />' . $option . '<br />';
			$field.= '</p>';
			break;
		}
		case 'email': {
			$field = '<input type="text" name="EMAIL" />';
			break;
		}
		case 'text': {
			$field = '<input type="text" name="mergevars[' . $tag . ']" />';
			break;
		}
		default: $field = '<span style="color: #f00;">Unsupported field type "' . $field_type . '"</span>';
	}

	return $field;
}

$in_snippet = true;
require_once $modx->getOption('core_path') . 'components/mailchimp/index.php';

if (!$listId) return 'Please specify a valid MailChimp list ID';

// Explode/strip the merge tags and add email if not set already
$mergeTags = explode(',', $mergeTags);
foreach ($mergeTags as $k => $v) $mergeTags[$k] = trim($v);
if (!in_array('EMAIL', $mergeTags)) $mergeTags[] = 'EMAIL';

if (isset($_POST['mailchimp_subscribe'])) {
	$email = isset($_POST['EMAIL']) ? $_POST['EMAIL'] : '';
	if ($email == '') $output = 'Please enter an email address';
	else {
		// Check for merge vars
		$postedVars = isset($_POST['mergevars']) ? $_POST['mergevars'] : NULL;

		// Attempt to subscribe
		if (!($status = $api->listSubscribe($listId, $email, $postedVars))) {
			if ($failureId) {
				$modx->sendRedirect($modx->makeUrl($failureId));
				exit;
			}
			else $output = 'Failed to subscribe. Please check the email address is valid. Detail:<br/><pre>'.print_r($status, true).'</pre>';
		}
		else {
			if ($successId) {
				$modx->sendRedirect($modx->makeUrl($successId));
				exit;
			}
			else $output = 'Thanks! Please check your email to confirm your subscription.';
		}
	}
}

$mergeVars = $api->listMergeVars($listId);
$fields = '';
foreach ($mergeVars as $mergeVar) {
	// First check we want this var
	if (!in_array($mergeVar['tag'], $mergeTags)) continue;

	$rowData = array(
		'tag' => $mergeVar['tag'],
		'name' => $mergeVar['name'],
		'input' => get_field($mergeVar)
	);
	$fields .= $modx->getChunk($rowTpl, $rowData);
}
$data['fields'] = $fields;

$output.= $modx->getChunk($subscribeTpl, $data);

return $output;