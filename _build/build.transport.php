<?php
/**
 * MailChimp Build Script
 *
 * @package mailchimp
 * @subpackage build
 * @author Dave Shoreman <codeM0nK3Y@me.com>
 */

define('PKG_NAME', 'MailChimp');
define('PKG_NAME_LOWER', 'mailchimp');
define('PKG_VERSION', '0.1.0');
define('PKG_RELEASE', 'beta1');
define('PKG_CATEGORY', 'MailChimp');

$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

$root = dirname(dirname(__FILE__)) . '/';
$sources = array (
	'root' => $root,
	'build' => $root . '_build/',
	'source_core' => $root . 'core/components/' . PKG_NAME_LOWER,
	'resolvers' => $root . '_build/resolvers/',
	'data' => $root . '_build/data/',
	'docs' => $root . 'core/components/' . PKG_NAME_LOWER . '/docs/',
	'snippets' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/snippets/'
);
unset($root);

// Load/init MODx
require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

// Load the package builder
$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER . '/');

// Create the category
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_CATEGORY);

// Create the snippet
$modx->log(modX::LOG_LEVEL_INFO,'Adding in snippets.');
$snippet = $modx->newObject('modSnippet');
$snippet->set('id', 0);
$snippet->set('name', 'mailchimp');
$snippet->set('description', 'Generate MailChimp subscription form for your lists');
$snippet->set('snippet', file_get_contents($sources['source_core'] . '/snippet.php'));

// Assign snippet properties
$properties = include $sources['data'] . 'properties.inc.php';
$snippet->setProperties($properties);
$category->addMany($snippet);

// Load the chunks
$modx->log(modX::LOG_LEVEL_INFO, 'Adding chunks');
$chunks = array();

$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'mc_subscribe',
    'description' => 'Default subscribe form for MailChimp',
    'snippet' => file_get_contents($sources['source_core'] . '/elements/chunks/mc_subscribe.chunk.tpl'),
    'properties' => ''
), '', true, true);
$chunks[2] = $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'mc_row',
    'description' => 'MailChimp form row template',
    'snippet' => file_get_contents($sources['source_core'] . '/elements/chunks/mc_row.chunk.tpl'),
    'properties' => ''
), '', true, true);

$category->addMany($chunks);

// Prepare category tree
$attr = array(
	xPDOTransport::UNIQUE_KEY => 'category',
	xPDOTransport::PRESERVE_KEYS => false,
	xPDOTransport::UPDATE_OBJECT => true,
	xPDOTransport::RELATED_OBJECTS => true,
	xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
		'Snippets' => array(
	        xPDOTransport::PRESERVE_KEYS => false,
	        xPDOTransport::UPDATE_OBJECT => true,
	        xPDOTransport::UNIQUE_KEY => 'name'
		)
	)
);

// Create the category vehicle
$vehicle = $builder->createVehicle($category,$attr);

// Register post-install script
$modx->log(modX::LOG_LEVEL_INFO,'Adding in Script Resolver.');
$vehicle->resolve('php',array(
    'source' => $sources['resolvers'] . 'install.script.php',
));

// Tell MODx to copy the core/components dir
$vehicle->resolve('file', array(
	'source' => $sources['source_core'],
	'target' => 'return MODX_CORE_PATH . \'components/\';'
));

// Inject category vehicle into the package
$builder->putVehicle($vehicle);

// Load/create menus
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in menu...');
$menu = include $sources['data'].'transport.menu.php';
if (empty($menu)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in menu.');
}
else {
    $vehicle= $builder->createVehicle($menu,array (
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => 'text',
        xPDOTransport::RELATED_OBJECTS => true,
        xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
            'Action' => array (
                xPDOTransport::PRESERVE_KEYS => false,
                xPDOTransport::UPDATE_OBJECT => true,
                xPDOTransport::UNIQUE_KEY => array ('namespace','controller')
            )
        )
    ));
    $builder->putVehicle($vehicle);

    $modx->log(modX::LOG_LEVEL_INFO,'Packaged in '.count($menu).' menu items.');
    unset($vehicle,$menu);
}

// Load the docs and setup options form
$builder->setPackageAttributes(array(
    'license' => '',
    'readme' => '',
    'changelog' => '',
    'setup-options' => array(
		'source' => $sources['build'] . 'setup.options.php'
    ),
));

// We're done! Zip up the package
$builder->pack();

// Finish off with some stat dumping
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(xPDO::LOG_LEVEL_INFO, "Package Built.");
$modx->log(xPDO::LOG_LEVEL_INFO, "Execution time: {$totalTime}");
exit();