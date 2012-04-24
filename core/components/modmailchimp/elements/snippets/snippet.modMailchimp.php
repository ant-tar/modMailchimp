<?php
if (!function_exists('get_field')) {
	function get_field ($list_field) {
		extract($list_field);
		if ($field_type != 'email') $tag = 'mmc_mergevars[' . $tag . ']';

		switch ($field_type) {
			case 'dropdown': {
				$selected = isset($_POST[$tag]) ? $_POST[$tag] : false;

				$field = '<select name="' . $tag . '">';
				foreach ($choices as $opt_id => $option) {
					$field.= '<option value="' . $opt_id . '"' . ($selected == $opt_id ? ' selected="selected"' : '') . '>' . $option . '</option>';
				}
				$field.= '</select>';
				break;
			}
			case 'radio': {
				$field = '<p>';
				foreach ($choices as $opt_id => $option) {
					$selected = isset($_POST[$tag]) ? ' selected="selected"' : '';
					$field.= '<input type="radio" name="' . $tag . '" value="' . $opt_id . '" ' . $selected . ' />' . $option . '<br />';
				}
				$field.= '</p>';
				break;
			}
			case 'email': {
				// We don't want EMAIL in the mergevars[] array so that we can check it individually later
				$val = isset($_POST['EMAIL']) ? htmlentities(trim($_POST['EMAIL'])) : '';
				$field = '<input type="text" name="EMAIL" value="' . $val . '" />';
				break;
			}
			case 'number':
			case 'text': {
				$val = isset($_POST[$tag]) ? htmlentities(trim($_POST['tag'])) : '';
				$field = '<input type="text" name="' . $tag . '" value="' . $val . '" />';
				break;
			}
			default: $field = '<span style="color: #f00;">Unsupported field type "' . $field_type . '"</span>';
		}

		return $field;
	}
}

// Cache the listId var for later checks in case it gets overwritten by POST
//$listIdFromSnippet = $listId;
if(!isset($recaptcha)) $recaptcha = $modx->getOption('recaptcha', $scriptProperties, NULL);
if($recaptcha !== NULL) $recaptcha = (int)$recaptcha;

$snippet = 'mailchimp';
$in_snippet = true;
include $modx->getOption('core_path') . 'components/modmailchimp/index.php';

// Explode/strip the merge tags and add email if not set already
$mergeTags = explode(',', $mergeTags);
foreach ($mergeTags as $k => $v) $mergeTags[$k] = trim($v);
if (!in_array('EMAIL', $mergeTags)) $mergeTags[] = 'EMAIL';

//if (isset($_POST['mmc_subscribe_' . $listId]) || isset($_POST['mmc_unsubscribe_' . $listId])) {
if (isset($_POST['mmc_subscribe']) || isset($_POST['mmc_unsubscribe'])) {
	$process = false;

	if (!$listId && isset($_POST['listId'])) $listId = trim($_POST['listId']);

	//if (!isset($_POST['listId']))
	if(!$listId){
		$data['errorCode'] = -1;
		$data['errorMessage'] = 'Please specify a valid MailChimp list ID';
		if ($failureId) {
			$_SESSION['mailchimp_error_code'] = $data['errorCode'];
			$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
			$_SESSION['mailchimp_placeholder'] = $placeholder;
			$modx->sendRedirect($modx->makeUrl($failureId));
			exit;
		}
		return $data['errorMessage'];
	}

	$lists = $api->lists(array('list_id' => $listId));
	if(!$lists)
	{
		$data['errorCode'] = -1;
		$data['errorMessage'] = 'Please specify a valid MailChimp list ID';
		if ($failureId) {
			$_SESSION['mailchimp_error_code'] = $data['errorCode'];
			$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
			$_SESSION['mailchimp_placeholder'] = $placeholder;
			$modx->sendRedirect($modx->makeUrl($failureId));
			exit;
		}
		return $data['errorMessage'];
	}

	$email = isset($_POST['EMAIL']) ? $_POST['EMAIL'] : '';
	if ($email == '')
	{
		$data['errorCode'] = -1;
		$data['errorMessage'] = 'Please enter an email address.';
		if ($failureId) {
			$_SESSION['mailchimp_error_code'] = $data['errorCode'];
			$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
			$_SESSION['mailchimp_placeholder'] = $placeholder;
			$modx->sendRedirect($modx->makeUrl($failureId));
			exit;
		}
		return $data['errorMessage'];
	}
	$process = true;

	if($recaptcha == 1)
	{
		$resp = recaptcha_check_answer($modx->getOption('modmailchimp.recaptcha_private'),
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			$process = false;
			$data['errorCode'] = null;
			$data['errorMessage'] = "The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: " . $resp->error . ")";

			if ($failureId) {
				$_SESSION['mailchimp_error_code'] = $data['errorCode'];
				$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
				$_SESSION['mailchimp_placeholder'] = $placeholder;
				$modx->sendRedirect($modx->makeUrl($failureId));
				exit;
			}
			elseif ($placeholder) {
				$data[$placeholder] = array_key_exists($placeholder, $data)
				 ? $data[$placeholder] . '<br/>' . $data['errorMessage']
				 : $data['errorMessage'];
			}
		}
	}
}
if (isset($_POST['mmc_subscribe']) && $process) {
	// TODO: Add foreach version to support multiple lists

	// Avoid processing all forms when only submitting one.
	//if ($listId != $_POST['listId']) return '';
	if (!isset($_POST['formName']) || $formName == $_POST['formName']){

		// Check the email isn't added already
		$mailCheck = $api->listMemberInfo($listId, array($email));

		if ($mailCheck['success'] && $mailCheck['data'][0]['status'] == 'pending') {
			$data['errorCode'] = -1;
			$data['errorMessage'] = 'This address is currently pending, please check for your confirmation email.';

			if ($failureId) {
				$_SESSION['mailchimp_error_code'] = $data['errorCode'];
				$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
				$_SESSION['mailchimp_placeholder'] = $placeholder;
				$modx->sendRedirect($modx->makeUrl($failureId));
				exit;
			}
			elseif ($placeholder) {
				$data[$placeholder] = array_key_exists($placeholder, $data)
				 ? $data[$placeholder] . '<br/>' . $data['errorMessage']
				 : $data['errorMessage'];
			}
		}
		else {
			// Check for merge vars
			$postedVars = isset($_POST['mmc_mergevars']) ? $_POST['mmc_mergevars'] : NULL;

			// Attempt to subscribe
			$status = $api->listSubscribe($listId, $email, $postedVars);
			if (($api->errorCode || $status === false)) {
				$data['errorCode'] = $api->errorCode ? $api->errorCode : null;
				$data['errorMessage'] = $api->errorMessage ? $api->errorMessage : 'Unknown error';

				if ($failureId) {
					$_SESSION['mailchimp_error_code'] = $data['errorCode'];
					$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
					$_SESSION['mailchimp_placeholder'] = $placeholder;
					$modx->sendRedirect($modx->makeUrl($failureId));
					exit;
				}
				elseif ($placeholder) {
					$data[$placeholder] = array_key_exists($placeholder, $data)
					 ? $data[$placeholder] . '<br/>' . $data['errorMessage']
					 : $data['errorMessage'];
				}
			}
			else {
				$data['success'] = 'Thanks! Please check your email to confirm your subscription.';

				if ($successId) {
					unset($_SESSION['mailchimp_error_message'], $_SESSION['mailchimp_error_code']);
					$_SESSION['mailchimp_success'] = $data['success'];
					$_SESSION['mailchimp_placeholder'] = $placeholder;
					$modx->sendRedirect($modx->makeUrl($successId));
					exit;
				}
				elseif ($placeholder) {
					$data[$placeholder] = array_key_exists($placeholder, $data)
					 ? $data[$placeholder] . '<br/>' . $data['success']
					 : $data['success'];
				}
			}
		}
	}
}
elseif (isset($_POST['mmc_unsubscribe']) && $process) {

	// Avoid processing all forms when only submitting one.
	//if ($listId != $_POST['listId']) return '';
	if (!isset($_POST['formName']) || $formName == $_POST['formName']){

		if($recaptcha !== 0)
		{
			$resp = recaptcha_check_answer($modx->getOption('modmailchimp.recaptcha_private'),
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);
		}

		if (isset($resp) && !$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			$data['errorCode'] = null;
			$data['errorMessage'] = "The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: " . $resp->error . ")";

			if ($failureId) {
				$_SESSION['mailchimp_error_code'] = $data['errorCode'];
				$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
				$_SESSION['mailchimp_placeholder'] = $placeholder;
				$modx->sendRedirect($modx->makeUrl($failureId));
				exit;
			}
			elseif ($placeholder) {
				$data[$placeholder] = array_key_exists($placeholder, $data)
				 ? $data[$placeholder] . '<br/>' . $data['errorMessage']
				 : $data['errorMessage'];
			}
		} else {
			// Your code here to handle a successful verification

			$status = $api->listUnsubscribe($listId, $email);
			if (($api->errorCode || $status === false)) {
				$data['errorCode'] = $api->errorCode ? $api->errorCode : null;
				$data['errorMessage'] = $api->errorMessage ? $api->errorMessage : 'Unknown error';

				if ($failureId) {
					$_SESSION['mailchimp_error_code'] = $data['errorCode'];
					$_SESSION['mailchimp_error_message'] = $data['errorMessage'];
					$_SESSION['mailchimp_placeholder'] = $placeholder;
					$modx->sendRedirect($modx->makeUrl($failureId));
					exit;
				}
				elseif ($placeholder) {
					$data[$placeholder] = array_key_exists($placeholder, $data)
					 ? $data[$placeholder] . '<br/>' . $data['errorMessage']
					 : $data['errorMessage'];
				}
			}
			else {
				$data['success'] = 'Unsubscribe successful.';

				if ($successId) {
					unset($_SESSION['mailchimp_error_message'], $_SESSION['mailchimp_error_code']);
					$_SESSION['mailchimp_success'] = $data['success'];
					$_SESSION['mailchimp_placeholder'] = $placeholder;
					$modx->sendRedirect($modx->makeUrl($successId));
					exit;
				}
				elseif ($placeholder) {
					$data[$placeholder] = array_key_exists($placeholder, $data)
					 ? $data[$placeholder] . '<br/>' . $data['success']
					 : $data['success'];
				}
			}
		}
	}
}
$data['listId'] = $listId;
$data['formName'] = $formName;

// We can only support merge vars if the listId is specified in the snippet call.
// If they want to POST the listId, they'll have to hardcode their form in a chunk or something.
//if ($listIdFromSnippet) {
	//$lists = $api->lists(array('list_id' => $listIdFromSnippet));
	//if(!$lists) return 'Please specify a valid MailChimp list ID';
	
	switch ($action) {
		case 'subscribe': {
			$fields = '';

			if($formName != 'mailchimp') $fields .= '<input type="hidden" name="formName" value="'.$formName.'">';

			if(!empty($listId)) $fields .= '<input type="hidden" name="listId" value="'.$listId.'">';

			if($listId){
				$mergeVars = $api->listMergeVars($listId);
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
			}
			else
			{
				$fields .= $modx->getChunk($rowTpl, array(
				 	'tag' => 'EMAIL',
				 	'name' => 'Email Address',
				 	'input' => get_field(array('field_type' => 'email'))
				 ));

				if(!isset($listLists)) $listLists = $modx->getOption('listLists', $scriptProperties, NULL);
				if(!isset($listLabel)) $listLabel = $modx->getOption('listLabel', $scriptProperties, 'Which List?');
				if(!isset($listControl)) $listControl = $modx->getOption('listControl', $scriptProperties, 'select');

				$fields .= $modx->runSnippet('modMailchimpLists', array(
					'apiKey' => $apiKey,
					'lists' => $listLists,
					'control' => $listControl,
					'rowTpl' => $rowTpl,
					'label' => $listLabel
				));
			}

			$recaptcha_field = recaptcha_get_html($modx->getOption('modmailchimp.recaptcha_public'));

			if($recaptcha)
			{
				$fields .= $modx->getChunk($rowTpl, array('tag' => 'RECAPTCHA', 'name' => 'Enter this text', 'input' => $recaptcha_field));
			}
			$data['recaptchField'] = $recaptcha_field;

			$data['fields'] = $fields;
			$output.= $modx->getChunk($subscribeTpl, $data);
			break;
		}
		case 'unsubscribe': {
			$fields = '';

			if($formName != 'mailchimp') $fields .= '<input type="hidden" name="formName" value="'.$formName.'">';
			$fields .= '<input type="hidden" name="listId" value="'.$listId.'">';

			$fields .= $modx->getChunk($rowTpl, array(
			 	'tag' => 'EMAIL',
			 	'name' => 'Email Address',
			 	'input' => get_field(array('field_type' => 'email'))
			 ));

			 $recaptcha_field = recaptcha_get_html($modx->getOption('modmailchimp.recaptcha_public'));

			 if($recaptcha !== 0)
			 {
			 	$fields .= $modx->getChunk($rowTpl, array('tag' => 'RECAPTCHA', 'name' => 'Enter this text', 'input' => $recaptcha_field));
			 }

			 $data['fields'] = $fields;

			 $data['recaptchaField'] = $recaptcha_field;
			$output.= $modx->getChunk($unsubscribeTpl, $data);
			break;
		}
	}
//}
return $output;