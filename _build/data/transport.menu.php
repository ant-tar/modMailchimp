<?php
/**
 * Define action and menu item
 *
 * @package mailchimp
 * @subpackage build
 * @author Dave Shoreman <codeM0nK3Y@me.com>
 */

// Add our action
$action = $modx->newObject('modAction');
$action->fromArray(array(
	'id' => 1,
	'namespace' => 'mailchimp',
	'parent' => '0',
	'controller' => 'index',
	'haslayout' => '1',
	'lang_topics' => '',
	'assets' => ''
), '', true, true);

$menu = $modx->newObject('modMenu');
$menu->fromArray(array(
	'text' => 'MailChimp',
	'parent' => 'components',
	'description' => 'Get snippet code for your MailChimp lists',
	'icon' => 'images/icons/plugin.gif',
	'menuindex' => '0',
	'params' => '',
	'handler' => ''
), '', true, true);
$menu->addOne($action);

return $menu;