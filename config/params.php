<?php

use app\components\EnvLoader;

return [
    'adminEmail' => EnvLoader::getString('ADMIN_EMAIL', 'admin@example.com'),
    'senderEmail' => EnvLoader::getString('SENDER_EMAIL', 'noreply@example.com'),
    'senderName' => EnvLoader::getString('SENDER_NAME', 'Kub24 Test App'),
    'user.passwordResetTokenExpire' => EnvLoader::getInt('PASSWORD_RESET_TOKEN_EXPIRE', 3600),
    'cache.l1.duration' => EnvLoader::getInt('L1_CACHE_DURATION', 60),
    'cache.l2.duration' => EnvLoader::getInt('L2_CACHE_DURATION', 300),
];
