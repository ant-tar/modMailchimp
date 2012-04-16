<?php
if (!function_exists('load_view')) {
	function load_view($view, array $data = array()) {
		global $modx;
		extract($data);
		
		ob_start();
		include($modx->getOption('core_path') . '/components/modmailchimp/views/' . $view . '.php');
		$tpl = ob_get_contents();
		ob_end_clean();
		return $tpl;
	}
}

// Setup default properties
$base_path = $modx->getOption('base_path');
$core_path = $modx->getOption('core_path');
$allow_override = $modx->getOption('modmailchimp.allow_override');
$output = '';

if($snippet != 'message')
{

	if (!isset($in_snippet) || ($in_snippet && !$apiKey) || ($in_snippet && $apiKey && !$allow_override)) $apiKey = $modx->getOption('modmailchimp.api_key');

	// Load the MailChimp API
	require_once $modx->getOption('core_path') . 'components/modmailchimp/MCAPI.class.php';
	$api = new MCAPI($apiKey);

	// Load Recaptcha
	require_once $modx->getOption('core_path') . 'components/modmailchimp/recaptchalib.php';

	if (!isset($in_snippet)) {
		// Init tabs
		$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : NULL;
		$tabs = array('Main');
		$tab_selected = isset($_REQUEST['tab_selected']) && in_array($_REQUEST['tab_selected'], $tabs) ? $_REQUEST['tab_selected'] : NULL;
		$tab_default = 'Main';

		$valid_actions = array('fields', 'lists');
		$action = isset($_GET['action']) && in_array($_GET['action'], $valid_actions) ? $_GET['action'] : NULL;
		$action_default = 'lists';

		if (!$action) header('Location: ?a=' . $a . '&action=' . $action_default);
		if (!$tab_selected) header('Location: ?a=' . $a . '&action=' . $action . '&tab_selected=' . $tab_default);

		// Prepare data for the templates
		$data = array(
			'action' => $a,
			'error' => '',
			'tabs' => array('index' => 'Main'),
			'tab_selected' => $tab_selected,
			'api' => &$api
		);

		switch ($action) {
			case 'lists': {
				$data['page_title'] = 'MailChimp Lists';
				$view = 'lists';
				$data['lists'] = $api->lists();
				break;
			}
			case 'fields': {
				$data['page_title'] = 'Merge Fields';
				$view = 'fields';
				$listId = isset($_GET['listId']) ? $_GET['listId'] : NULL;

				if (!$listId) $data['error'] = 'List ID must be set';
				else {
					$data['fields'] = $api->listMergeVars($listId);

					if ($api->errorCode) {
						$ecode = trim($api->errorCode) != '' ? ' (Error code ' . $api->errorCode . ')' : '';
						$data['error'] = 'Failed to load merge fields' .  $ecode . '<br/>' . $api->errorMessage;
					}
				}
				break;
			}
		}
		
		// Load the views
		$output.= load_view('common/header', $data);
		$output.= load_view('grids/' . $view, $data);
		$output.= load_view('common/footer', $data);
	}
}

return $output;