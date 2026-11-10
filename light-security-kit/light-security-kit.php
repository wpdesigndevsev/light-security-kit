<?php
/*
Plugin Name: Light Security Kit
Plugin URI: https://weblowcost.ovh/
Description: Disables XML-RPC, hides the WordPress version, blocks the file editor and applies light security and performance tweaks. No complex configuration.
Version: 1.2.0
Requires at least: 5.0
Requires PHP: 7.4
Requires CP:  2.0
Author: webpressdesing
Author URI: https://profiles.wordpress.org/webpressdesing/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: light-security-kit
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. SETTINGS PAGE
 */
function lsk_agregar_pagina_ajustes() {
    add_options_page(
        __( 'Light Security Kit', 'light-security-kit' ),
        __( 'Light Security', 'light-security-kit' ),
        'manage_options',
        'light-security-kit',
        'lsk_renderizar_pagina'
    );
}
add_action( 'admin_menu', 'lsk_agregar_pagina_ajustes' );

/**
 * 2. REGISTER OPTIONS
 */
function lsk_registrar_ajustes() {
    register_setting(
        'lsk_grupo_ajustes',
        'lsk_opciones',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'lsk_sanitizar_opciones',
            'default'           => array(
                'desactivar_xmlrpc' => 1,
                'ocultar_version'   => 1,
                'desactivar_editor' => 1,
                'limpiar_head'      => 1,
                'bloquear_rest'     => 0,
                'bloquear_usuarios' => 1,
                'desactivar_emojis' => 1,
            ),
        )
    );
}
add_action( 'admin_init', 'lsk_registrar_ajustes' );

/**
 * 3. SANITIZE
 */
function lsk_sanitizar_opciones( $input ) {
    $claves = array(
        'desactivar_xmlrpc',
        'ocultar_version',
        'desactivar_editor',
        'limpiar_head',
        'bloquear_rest',
        'bloquear_usuarios',
        'desactivar_emojis',
    );
    $limpio = array();
    foreach ( $claves as $clave ) {
        $limpio[ $clave ] = ( isset( $input[ $clave ] ) && 1 == $input[ $clave ] ) ? 1 : 0;
    }
    return $limpio;
}

/**
 * 4. RENDER SETTINGS FORM
 */
function lsk_renderizar_pagina() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $opciones = wp_parse_args(
        get_option( 'lsk_opciones', array() ),
        array(
            'desactivar_xmlrpc' => 1,
            'ocultar_version'   => 1,
            'desactivar_editor' => 1,
            'limpiar_head'      => 1,
            'bloquear_rest'     => 0,
            'bloquear_usuarios' => 1,
            'desactivar_emojis' => 1,
        )
    );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Light Security Kit', 'light-security-kit' ); ?></h1>
        <p><?php esc_html_e( 'Check the boxes to enable or disable each protection. Changes are saved when you click the button.', 'light-security-kit' ); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'lsk_grupo_ajustes' ); ?>
            <table class="form-table" role="presentation">

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable XML-RPC', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[desactivar_xmlrpc]" value="1" <?php checked( $opciones['desactivar_xmlrpc'], 1 ); ?> />
                            <?php esc_html_e( 'Blocks xmlrpc.php to prevent brute force attacks.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Hide WordPress version', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[ocultar_version]" value="1" <?php checked( $opciones['ocultar_version'], 1 ); ?> />
                            <?php esc_html_e( 'Removes the tag that shows your WordPress version in the source code.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable file editor', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[desactivar_editor]" value="1" <?php checked( $opciones['desactivar_editor'], 1 ); ?> />
                            <?php esc_html_e( 'Prevents editing PHP files of themes and plugins from the dashboard.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Clean up the <head>', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[limpiar_head]" value="1" <?php checked( $opciones['limpiar_head'], 1 ); ?> />
                            <?php esc_html_e( 'Removes unnecessary links (wlwmanifest, shortlink, REST, adjacent posts) to save resources.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Block REST API for visitors', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[bloquear_rest]" value="1" <?php checked( $opciones['bloquear_rest'], 1 ); ?> />
                            <?php esc_html_e( 'Prevents unauthenticated visitors from using the REST API. May affect the block editor if you are not logged in.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Block user enumeration', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[bloquear_usuarios]" value="1" <?php checked( $opciones['bloquear_usuarios'], 1 ); ?> />
                            <?php esc_html_e( 'Prevents scanning usernames with ?author=1.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php esc_html_e( 'Disable emojis', 'light-security-kit' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="lsk_opciones[desactivar_emojis]" value="1" <?php checked( $opciones['desactivar_emojis'], 1 ); ?> />
                            <?php esc_html_e( 'Removes WordPress emoji scripts to save requests.', 'light-security-kit' ); ?>
                        </label>
                    </td>
                </tr>

            </table>
            <?php submit_button( __( 'Save changes', 'light-security-kit' ) ); ?>
        </form>
    </div>
    <?php
}

/**
 * 5. APPLY SETTINGS
 */
function lsk_aplicar_seguridad() {
    $opciones = wp_parse_args(
        get_option( 'lsk_opciones', array() ),
        array(
            'desactivar_xmlrpc' => 1,
            'ocultar_version'   => 1,
            'desactivar_editor' => 1,
            'limpiar_head'      => 1,
            'bloquear_rest'     => 0,
            'bloquear_usuarios' => 1,
            'desactivar_emojis' => 1,
        )
    );

    // 5.1 XML-RPC
    if ( ! empty( $opciones['desactivar_xmlrpc'] ) ) {
        add_filter( 'xmlrpc_enabled', '__return_false' );
        remove_action( 'wp_head', 'rsd_link' );
    }

    // 5.2 Hide version
    if ( ! empty( $opciones['ocultar_version'] ) ) {
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );
    }

    // 5.3 Disable file editor
    if ( ! empty( $opciones['desactivar_editor'] ) ) {
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
    }

    // 5.4 Clean head
    if ( ! empty( $opciones['limpiar_head'] ) ) {
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
    }

    // 5.5 Block REST API for visitors
    if ( ! empty( $opciones['bloquear_rest'] ) ) {
        add_filter( 'rest_authentication_errors', function( $resultado ) {
            if ( ! empty( $resultado ) ) {
                return $resultado;
            }
            if ( ! is_user_logged_in() ) {
                return new WP_Error(
                    'rest_no_autorizado',
                    __( 'The REST API is only available to authenticated users.', 'light-security-kit' ),
                    array( 'status' => 401 )
                );
            }
            return $resultado;
        } );
    }

    // 5.6 Block user enumeration
    if ( ! empty( $opciones['bloquear_usuarios'] ) ) {
        if ( ! is_admin() ) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public GET check to block user enumeration, no nonce needed.
            $author_id = isset( $_GET['author'] ) ? absint( wp_unslash( $_GET['author'] ) ) : 0;
            if ( $author_id > 0 ) {
                wp_die(
                    esc_html__( 'Access not allowed.', 'light-security-kit' ),
                    esc_html__( 'Access blocked', 'light-security-kit' ),
                    array( 'response' => 403 )
                );
            }
            add_filter( 'rest_endpoints', function( $endpoints ) {
                if ( isset( $endpoints['/wp/v2/users'] ) ) {
                    unset( $endpoints['/wp/v2/users'] );
                }
                if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
                    unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
                }
                return $endpoints;
            } );
        }
    }

    // 5.7 Disable emojis
    if ( ! empty( $opciones['desactivar_emojis'] ) ) {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', function( $plugins ) {
            return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
        } );
    }
}
add_action( 'init', 'lsk_aplicar_seguridad' );
