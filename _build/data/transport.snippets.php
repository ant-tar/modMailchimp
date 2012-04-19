<?php
/**
 *
 * transport.snippets.php
 * @package modmailchimp
 * @subpackage build
 *
 * Created by JetBrains PhpStorm
 * Date: 14/04/12 7:11 PM
 *
 * http://www.bigblockstudios.ca
 * https://github.com/BigBlockStudios
 *
 */

// stole this from the formit package - very handy... :)
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}

$snippets = array();

// the subscribe snippet
$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'modMailchimp',
    'description' => 'the main MailChimp subscription snippet',
    'snippet' => getSnippetContent($sources['snippets'].'snippet.php'),
),'',true,true);
// $properties = include $sources['properties'].'properties.modmailchimp.php';
// $snippets[0]->setProperties($properties);
// unset($properties);


// the list display snippet
$snippets[1] = $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'modMailchimpLists',
    'description' => 'Generate a list of your lists for use with the mailchimp snippet',
    'snippet' => getSnippetContent($sources['snippets'].'snippet.lists.php'),
),'',true,true);
// $properties = include $sources['properties'].'properties.modmailchimp.php';
// $snippets[1]->setProperties($properties);
// unset($properties);


// the unsubscribe snippet
$snippets[2] = $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'modMailChimpMessage',
    'description' => 'Gets mailchimp messages',
    'snippet' => getSnippetContent($sources['snippets'].'snippet.message.php'),
),'',true,true);
// $properties = include $sources['properties'].'properties.modmailchimp.php';
// $snippets[2]->setProperties($properties);
// unset($properties);


/*
// the modmailchimp helper
$snippets[3] = $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3,
    'name' => 'mmcWorkerchimp',
    'description' => 'unsubscrbe from a list',
    'snippet' => getSnippetContent($sources['snippets'].'/snippet.worker.php'),
),'',true,true);
// $properties = include $sources['properties'].'properties.modmailchimp.php';
// $snippets[3]->setProperties($properties);
// unset($properties);


// the modmailchimp subscriber
$snippets[4] = $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 4,
    'name' => 'mmcSubscriber',
    'description' => 'subscribe to a list',
    'snippet' => getSnippetContent($sources['snippets'].'/snippet.subscribe.php'),
),'',true,true);
// $properties = include $sources['properties'].'properties.modmailchimp.php';
// $snippets[3]->setProperties($properties);
// unset($properties);


// the modmailchimp lister
$snippets[5] = $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => 5,
    'name' => 'mmcLister',
    'description' => 'show lists',
    'snippet' => getSnippetContent($sources['snippets'].'/snippet.lists.php'),
),'',true,true);
// $properties = include $sources['properties'].'properties.modmailchimp.php';
// $snippets[3]->setProperties($properties);
// unset($properties);
*/

return $snippets;