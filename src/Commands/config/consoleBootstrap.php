<?php

use Illuminate\Container\Container;
use ZnCore\Base\Libs\App\Loaders\BundleLoader;
use ZnLib\Db\Capsule\Manager;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use ZnCore\Domain\Libs\EntityManager;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnLib\Db\Factories\ManagerFactory;
use ZnCore\Base\Libs\App\Kernel;

DotEnv::init();

$kernel = new Kernel('console');
$container = Container::getInstance();
$kernel->setContainer($container);
$bundleLoader = new BundleLoader([], ['i18next', 'container', 'console', 'migration']);
$appBundlesConfigFile = \ZnCore\Base\Legacy\Yii\Helpers\FileHelper::path($_ENV['BUNDLES_CONFIG_FILE']);

if(file_exists($appBundlesConfigFile)) {
    $bundleLoader->addBundles(include $appBundlesConfigFile);
}
$bundleLoader->addBundles(include __DIR__ . '/bundle.php');
$kernel->setLoader($bundleLoader);

$config = $kernel->loadAppConfig();
