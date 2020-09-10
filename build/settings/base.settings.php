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