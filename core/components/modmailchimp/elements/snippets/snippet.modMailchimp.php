<?php
/**
 *
 * snippet.modMailchimp.php
 *
 * Created using: JetBrains PhpStorm
 * BigBlock Studios http://www.bigblockstudios.ca
 * http://github.com/BigBlockStudios
 * Date: 15/04/12, 2:42 PM
 *
 */



//load the lexicon
$modx->lexicon->load('modmailchimp:default');

if (!function_exists('get_field')) {
	function get_field ($list_field) {
		extract($list_field);
		$tag_id = $tag;
		$interests = strpos($tag, 'mmc_interests') !== FALSE;

		if ($field_type != 'email' && !$interests) $tag = 'mmc_mergevars[' . $tag . ']';

		switch ($field_type) {
			case 'dropdown': {
				$field = '<select name="' . $tag . '">';
				foreach ($choices as $opt_id => $option) {
					if($interests){
						$selected = isset($_POST['mmc_interests'][$group_id]) && $_POST['mmc_interests'][$group_id] == $opt_id  ? ' selected="selected"' : '';
						if(!$selected) $selected = isset($_POST['mmc_interests'][$group_name]) && $_POST['mmc_interests'][$group_name] == $opt_id  ? ' selected="selected"' : '';
					} else {
						$selected = isset($_POST[$tag]) && $_POST[$tag] == $opt_id  ? ' selected="selected"' : '';
					}

					$field.= '<option value="' . $opt_id . '"' . $selected . '>' . $option . '</option>';
				}
				$field.= '</select>';
				break;
			}
			case 'radio': {
				$field = '<p>';
				foreach ($choices as $opt_id => $option) {
					if($interests){
						$checked = isset($_POST['mmc_interests'][$group_id]) && $_POST['mmc_interests'][$group_id] == $opt_id  ? ' checked="checked"' : '';
						if(!$checked) $checked = isset($_POST['mmc_interests'][$group_name]) && $_POST['mmc_interests'][$group_name] == $opt_id  ? ' checked="checked"' : '';
					} else {
						$checked = isset($_POST[$tag]) && $_POST[$tag] == $opt_id  ? ' checked="checked"' : '';
					}
					$field.= '<input type="radio" name="' . $tag . '" value="' . $opt_id . '" id="'.$tag_id.'_'.$opt_id.'" ' . $checked . ' /><label for="'.$tag_id.'_'.$opt_id.'">' . $option . '</label><br />';
				}
				$field.= '</p>';
				break;
			}
			case 'checkbox': {
				$field = '<p>';
				foreach ($choices as $opt_id => $option) {
					if($interests){
						$checked = isset($_POST['mmc_interests'][$group_id]) && in_array($opt_id, $_POST['mmc_interests'][$group_id])  ? ' checked="checked"' : '';
						if(!$checked) $checked = isset($_POST['mmc_interests'][$group_name]) && in_array($opt_id, $_POST['mmc_interests'][$group_name])  ? ' checked="checked"' : '';
					} else {
						$checked = isset($_POST[$tag]) && $_POST[$tag] == $opt_id  ? ' checked="checked"' : '';
					}
					$field.= '<input type="checkbox" name="' . $tag . '[]" value="' . $opt_id . '" id="'.$tag_id.'_'.$opt_id.'" ' . $checked . ' /><label for="'.$tag_id.'_'.$opt_id.'">' . $option . '</label><br />';
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
        // $data['errorMessage'] = 'Please specify a valid MailChimp list ID';
		$data['errorMessage'] = $modx->lexicon('modmailchimp.list_id_invalid');
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
		// $data['errorMessage'] = 'Please specify a valid MailChimp list ID';
		$data['errorMessage'] = $modx->lexicon('modmailchimp.list_id_invalid');
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
        // $data['errorMessage'] = 'Please enter an email address.';
        $data['errorMessage'] = $modx->lexicon('modmailchimp.email_address_missing');

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
            //$data['errorMessage'] = "The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: " . $resp->error . ")";
            $data['errorMessage'] = $modx->lexicon('modmailchimp.recaptcha_incorrect') . $resp->error . ")";

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
	// to-do: Add foreach version to support multiple lists

	// Avoid processing all forms when only submitting one.
	//if ($listId != $_POST['listId']) return '';
	if (!isset($_POST['formName']) || $formName == $_POST['formName']){

		// Check the email isn't added already
		$mailCheck = $api->listMemberInfo($listId, array($email));

		if ($mailCheck['success'] && $mailCheck['data'][0]['status'] == 'pending') {
			$data['errorCode'] = -1;
			//$data['errorMessage'] = 'This address is currently pending, please check for your confirmation email.';
            $data['errorMessage'] = $modx->lexicon('modmailchimp.email_address_pending');

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

			// Interest Groups - needs to be array(array('name' => 'Group Name', 'groups' => 'Group1, Group2, etc'), array(...))
			if($interestGroups)
			{
				$groups = array();
				foreach($_POST['mmc_interests'] as $id => $group)
				{
					if(!is_array($group)) $group = array($group);

					$groupData = array('groups' => implode(',', $group));

					if(is_numeric($id)) $groupData['id'] = $id;
					else $groupData['name'] = $id;

					$groups[] = $groupData;
				}

				$postedVars['GROUPINGS'] = $groups;
			}

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
				//$data['success'] = 'Thanks! Please check your email to confirm your subscription.';
                $data['success'] = $modx->lexicon('modmailchimp.success_subscription');

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

				//kill the post data on success
				unset($_POST['EMAIL'], $_POST['mmc_mergevars'], $_POST['mmc_interests']);
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
			//$data['errorMessage'] = "The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: " . $resp->error . ")";
			$data['errorMessage'] = $modx->lexicon('modmailchimp.recaptcha_incorrect') . $resp->error . ")";

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
				$data['errorMessage'] = $api->errorMessage ? $api->errorMessage : $modx->lexicon('modmailchimp.error_unknown');

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
                $data['success'] = $modx->lexicon('modmailchimp.success_unsubscribe');

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

				// How about interest groups?
				if($interestGroups)
				{
					$groupings = $api->listInterestGroupings($listId);
					//echo '<pre>$interestGroups = ', print_r($interestGroups, TRUE), '</pre>';
					foreach($groupings as $grouping)
					{
						$choices = array();
						foreach($grouping['groups'] as $group)
						{
							$choices[$group['name']] = $group['name'];
						}

						$group_tag = array(
							'field_type' => $grouping['form_field'] == 'checkboxes' ? 'checkbox' : $grouping['form_field'],
							'tag' => 'mmc_interests['.$grouping['id'].']',
							'choices' => $choices,
							'group_id' => $grouping['id'],
							'group_name' => $grouping['name']
						);

						$rowData = array(
							'tag' => $group_tag['tag'],
							'name' => $grouping['name'],
							'input' => get_field($group_tag)
						);
						$fields .= $modx->getChunk($rowTpl, $rowData);
					}
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
				$fields .= $modx->getChunk($rowTpl, array('tag' => 'RECAPTCHA', 'name' => $modx->lexicon('modmailchimp.recaptcha_enter_text'), 'input' => $recaptcha_field));
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
			 	$fields .= $modx->getChunk($rowTpl, array('tag' => 'RECAPTCHA', 'name' => $modx->lexicon('modmailchimp.recaptcha_enter_text'), 'input' => $recaptcha_field));
			 }

			 $data['fields'] = $fields;

			 $data['recaptchaField'] = $recaptcha_field;
			$output.= $modx->getChunk($unsubscribeTpl, $data);
			break;
		}
	}
//}
return $output;