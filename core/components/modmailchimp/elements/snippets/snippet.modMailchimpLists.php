<?php
$snippet = 'lists';
$in_snippet = true;
include $modx->getOption('core_path') . 'components/modmailchimp/index.php';

if (!$control) $control = 'select';
$lists = $lists ? explode(',', $lists) : null;
$mc_lists = $api->lists();

switch ($control) {
	case 'select':
	case 'multiselect': {
		$field = '<select name="listId"' . ($control == 'multiselect' ? 'multiple="multiple"' : '') . '>';
		foreach ($mc_lists['data'] as $list) {
			if (!$lists || ($lists && in_array($list['id'], $lists)))
				$field.= '<option value="' . $list['id'] . '">' . $list['name'] . '</option>';
		}
		$field.= '</select>';
		break;
	}
	//case 'checkbox':
	case 'radio': {
		$field = '';
		foreach ($mc_lists['data'] as $list) {
			if (!$lists || ($lists && in_array($list['id'], $lists)))
				$field.= '<input type="' . $control . '" name="listId" value="' . $list['id'] . '" id="mmc_listId_'.$list['id'].'" /> <label for="mmc_listId_'.$list['id'].'">' . $list['name'] . '</label>';
		}
		break;
	}
}

$rowData = array(
	'tag' => 'listId',
	'name' => $label,
	'input' => $field
);

$output .= $modx->getChunk($rowTpl, $rowData);
return $output;