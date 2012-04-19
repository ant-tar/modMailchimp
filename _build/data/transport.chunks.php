<?php
/**
 *
 * transport.chunks.php
 * @package modmailchimp
 * @subpackage build
 *
 * Created by JetBrains PhpStorm
 * Date: 14/04/12 7:43 PM
 *
 * http://www.bigblockstudios.ca
 * https://github.com/BigBlockStudios
 *
 */


$chunks = array();

// subscription form chunk
$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'mmc_subscribe',
    'description' => 'Default subscribe form for MailChimp',
    'snippet' => file_get_contents($sources['chunks'].'/mmc_subscribe.chunk.tpl'),
    'properties' => '',
),'',true,true);

// unsubscription form chunk
$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'mmc_unsubscribe',
    'description' => 'Default unsubscribe form for modMailchimp',
    'snippet' => file_get_contents($sources['chunks'] . '/mmc_unsubscribe.chunk.tpl'),
    'properties' => ''
), '', true, true);

// form row template
$chunks[2] = $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'mmc_row',
    'description' => 'MailChimp form row template',
    'snippet' => file_get_contents($sources['chunks'] . '/mmc_row.chunk.tpl'),
    'properties' => ''
), '', true, true);


return $chunks;