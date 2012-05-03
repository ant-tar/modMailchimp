<?php
/**
 *
 * build.transport.php
 * @package
 *
 * Created by JetBrains PhpStorm
 * Date: 14/04/12 6:06 PM
 *
 * http://www.bigblockstudios.ca
 * https://github.com/BigBlockStudios
 *
 */


// define package name, version, category etc.
define('PKG_NAME', 'modMailchimp');
define('PKG_NAME_LOWER', 'modmailchimp');
define('PKG_VERSION', '1.0.7');
define('PKG_RELEASE', 'pl'); // {alpha|beta|rc|pl}
define('PKG_CATEGORY', 'MailChimp');

// create some paths for our build, not all are needed for every package
$root = dirname(dirname(__FILE__)) . '/';
$sources = array (
    'root' => $root,
    'build' => $root . '_build/',
    'resolvers' => $root . '_build/resolvers/',
    'validators' => $root . '_build/validators/',
    'data' => $root . '_build/data/',
    'docs' => $root . 'core/components/' . PKG_NAME_LOWER . '/docs/',
    'source_core' => $root . 'core/components/' . PKG_NAME_LOWER,
    'source_assets' => $root . 'assets/components/' . PKG_NAME_LOWER,
    'snippets' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/snippets/',
    'chunks' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/chunks/',
    'plugins' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/plugins/',
    'tvs' => $root . 'core/components/' . PKG_NAME_LOWER . '/elements/tvs/',
    'install_options' => $root . '_build/install.options/',
    'packages' => $root . 'core/packages/',
    'lexicon' => $root . 'core/components/' . PKG_NAME_LOWER . '/lexicon/',
);
unset($root);

// Load/init MODx
// if building in a modx environment, just include the config.core.php
require_once dirname(dirname($sources['root'])).'/config.core.php';

// otherwise define
// define('MODX_CORE_PATH', '../../modx/core/');
// define('MODX_CONFIG_KEY', 'config');
// or include:
// require_once dirname(__FILE__) . '/build.config.php';

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

// load some system settings
$settings = array();
include_once $sources['data'].'transport.settings.php';

$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
);
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    $builder->putVehicle($vehicle);
}
unset($settings,$setting,$attributes);

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
        ),
        'Chunks' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        )
    )
);

// add base snippets from a transport file
$modx->log(modX::LOG_LEVEL_INFO,'Adding in modMailchimp snippets.'); flush();
$snippets = include_once $sources['data'].'transport.snippets.php';
if (is_array($snippets)) {
    $category->addMany($snippets);
} else {
    $modx->log(modX::LOG_LEVEL_FATAL,'Adding modMailchimp snippets failed miserably.');
}

// add base chunks from a transport file
$modx->log(modX::LOG_LEVEL_INFO,'Adding in modMailchimp chunks of bananas!'); flush();
$chunks = include_once $sources['data'].'transport.chunks.php';
if (is_array($chunks)) {
    $category->addMany($chunks);
} else {
    $modx->log(modX::LOG_LEVEL_FATAL,'Adding modMailchimp chunks failed mysteriously?');
}

// Create the category vehicle
$vehicle = $builder->createVehicle($category,$attr);

// Register post-install script - inserts all teh user install options
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
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    'setup-options' => array(
    'source' => $sources['build'] . 'setup.options.php'
    ),
));

// We're done! Zip up the package
$builder->pack();

$modx->log(xPDO::LOG_LEVEL_INFO, "Package Built.");

exit();