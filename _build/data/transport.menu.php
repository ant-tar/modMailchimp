<?php
/**
 *
 * transport.menu.php
 * @package modmailchimp
 * @subpackage build
 *
 * Created by JetBrains PhpStorm
 * Date: 14/04/12 3:56 PM
 *
 * http://www.bigblockstudios.ca
 * https://github.com/BigBlockStudios
 *
 */


// Add our action, creates the action in modx & points it t the index.php file
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


// creates the menu item under components in the modx manager
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