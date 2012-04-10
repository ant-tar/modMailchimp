<?php
// Add our action
$action = $modx->newObject('modAction');
$action->fromArray(array(
	'id' => 1,
	'namespace' => 'modmailchimp',
	'parent' => '0',
	'controller' => 'index',
	'haslayout' => '1',
	'lang_topics' => '',
	'assets' => ''
), '', true, true);

$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
	'text' => 'modMailchimp',
	'parent' => 'components',
	'description' => 'Get snippet code for your MailChimp lists',
	'icon' => 'images/icons/plugin.gif',
	'menuindex' => '0',
	'params' => '',
	'handler' => ''
), '', true, true);
$menu->addOne($action);

return $menu;