<?php
/**
 * Plugin Name: Fuhao Abilities
 * Description: 把页面、产品、分类、媒体、菜单、站点设置与主题选项的编辑操作注册为 WordPress Abilities，供 MCP 客户端与 REST 调用。
 * Version: 0.3.1
 * Requires at least: 7.1
 * Requires PHP: 8.0
 * Author: Fuhao
 * License: GPL-2.0-or-later
 * Text Domain: fuhao-abilities
 *
 * @package FuhaoAbilities
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FUHAO_ABILITIES_VERSION', '0.3.1' );
define( 'FUHAO_ABILITIES_DIR', plugin_dir_path( __FILE__ ) );

require_once FUHAO_ABILITIES_DIR . 'includes/register.php';
require_once FUHAO_ABILITIES_DIR . 'includes/catalog.php';
