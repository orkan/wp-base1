<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1;

/*
 * Plugin Name: Ork Base1
 * Description: Ork Base1 example.
 * Version: 3.0.0
 * Date: Sun, 26 May 2024 17:14:21 +02:00
 * Author: Orkan <orkans+wpbase1@gmail.com>
 * Author URI: https://github.com/orkan
 */
$Factory = new Factory();
$Factory->Plugin()->run();
$Factory->Settings()->run();
$Factory->Mailer()->run();
$Factory->Formix()->run();
$Factory->Ajaxer()->run();
unset( $Factory );
