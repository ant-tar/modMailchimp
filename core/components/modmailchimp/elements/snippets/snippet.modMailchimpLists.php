<?php
$in_snippet = true;
include $modx->getOption('core_path') . 'components/modmailchimp/index.php';

if (!$control) $control = 'select';
$lists = $lists ? explode(',', $lists) : null;
$mc_lists = $api->lists();

switch ($control) {
	case 'select':
	case 'multiselect': {
		$field = '<select name="mmc_listId"' . ($control == 'multiselect' ? 'multiple="multiple"' : '') . '>';
		foreach ($mc_lists['data'] as $list) {
			if (!$lists || ($lists && in_array($list['id'], $lists)))
				$field.= '<option value="' . $list['id'] . '">' . $list['name'] . '</option>';
		}
		$field.= '</select>';
		break;
	}
	case 'checkbox':
	case 'radio': {
		$field = '';
		foreach ($mc_lists['data'] as $list) {
			if (!$lists || ($lists && in_array($list['id'], $lists)))
				$field.= '<input type="' . $control . '" name="mmc_listId" value="' . $list['id'] . '" /> ' . $list['name'] . '<br/>';
		}
		break;
	}
}

$rowData = array(
	'tag' => 'mmc_listId',
	'name' => $label,
	'input' => $field
);

$output .= $modx->getChunk($rowTpl, $rowData);
return $output;