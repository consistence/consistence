<?php

declare(strict_types = 1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

$config = $config->enableAnalysisOfUnusedDevDependencies();
$config = $config->addPathToScan(__DIR__, true);

// tools
$config = $config->ignoreErrorsOnPackages([
	'consistence/coding-standard',
	'phing/phing',
	'php-parallel-lint/php-console-highlighter',
	'php-parallel-lint/php-parallel-lint',
], [ErrorType::UNUSED_DEPENDENCY]);

return $config;
