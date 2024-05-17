<?php
/*
 * This file is part of the orkan/wp-base1 package.
 * Copyright (c) 2024 Orkan <orkans+wpbase1@gmail.com>
 */
namespace Orkan\WP\Base1;

/*
 * Plugin Name: Ork Base1
 * Description: Ork Base1 example.
 * Version: 2.0.0
 * Date: Fri, 17 May 2024 16:31:19 +02:00
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
