<?php

/**
 * @file
 * Include global settings overrides here.
 */

// Specify install profile.
$settings['install_profile'] = 'minimal';

// Redis.
$settings['cache_prefix']['default'] = 'voi_';
$conf['chq_redis_cache_enabled'] = TRUE;
require_once dirname(__FILE__) . "/settings.redis.inc";

// For migration.
$databases['migrate']['default'] = [
  'database'  => 'voi',
  'username'  => 'root',
  'password'  => 'datadump',
  'host'      => 'mysqlimport',
  'port'      => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver'    => 'mysql',
];

// Newrelic.
if (extension_loaded('newrelic')) {
  require_once dirname(__FILE__) . "/settings.newrelic.inc";
}
