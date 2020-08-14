<?php
/**
 * PB Settings
 *
 * Quick settings page generator for WordPress
 *
 * @package PB_Settings
 * @version 3.2
 * @author Pluginbazar
 * @copyright 2019 Pluginbazar.com
 * @see https://github.com/jaedm97/PB-Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Settings' ) ) {
	class PB_Settings {

		public $data = array();

		private $options = array();
		private $checked = array();


		/**
		 * PB_Settings constructor.
		 *
		 * @param array $args
		 */
		function __construct( $args = array() ) {

			$this->data = &$args;

			if ( $this->add_in_menu() ) {
				add_action( 'admin_menu', array( $this, 'add_menu_in_admin_menu' ), 12 );
			}

			$this->set_options();

			add_action( 'admin_init', array( $this, 'display_fields' ), 12 );
			add_action( 'admin_notices', array( $this, 'required_plugin_check' ) );
			add_action( 'after_plugin_row', array( $this, 'deactivation_feedback' ), 10, 2 );
			add_action( 'wp_ajax_pbfb_feedback_new', array( $this, 'pbfb_feedback_new' ) );

			add_filter( 'whitelist_options', array( $this, 'whitelist_options' ), 99, 1 );
		}


		/**
		 * Handle Ajax submission of feedback form
		 */
		function pbfb_feedback_new() {

			$curl = curl_init();
			curl_setopt_array( $curl, array(
				CURLOPT_URL            => "https://pluginbazar.com/feedback_new",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "POST",
				CURLOPT_POSTFIELDS     => $_POST,
				CURLOPT_HTTPHEADER     => array(
					"Cookie: pb_offer_time=Apr%2018%202020%200%3A38%3A54"
				),
			) );
			$response = curl_exec( $curl );
			curl_close( $curl );

			wp_send_json_success( $response );
		}


		/**
		 * Render quick feedback form
		 *
		 * @param $plugin_file
		 * @param $plugin_data
		 */
		function deactivation_feedback( $plugin_file, $plugin_data ) {

			$plugin_slug = $this->get_data( 'plugin_slug' );
			$this_plugin = sprintf( '%1$s/%1$s.php', $plugin_slug );
			$plugin_name = $this->get_data( 'Title', '', $plugin_data );

			if ( $plugin_file !== $this_plugin || ! $this->get_data( 'enable_feedback', false ) ) {
				return;
			}

			?>
            <div class="pbfb-container">
                <div class="pbfb-box">
                    <div class="pbfb-header"><?php esc_html_e( 'quick feedback', 'pb-settings' ); ?></div>
                    <div class="pbfb-content">
                        <p><?php printf( esc_html__( 'If you have a moment, please share why you are deactivating %s', 'pb-settings' ), $plugin_name ); ?></p>
                        <div class="pbfb-reasons">
                            <div>
                                <input id="no_longer_needed" type="radio" name="reason_key" value="no_longer_needed">
                                <label for="no_longer_needed"><?php esc_html_e( 'I no longer need the plugin', 'pb-settings' ); ?></label>
                            </div>
                            <div>
                                <input id="found_a_better_plugin" type="radio" name="reason_key"
                                       value="found_a_better_plugin">
                                <label for="found_a_better_plugin"><?php esc_html_e( 'I found a better plugin', 'pb-settings' ); ?></label>
                                <div class="pbfb-extra-text">
                                    <input type="text" name="reason_better_plugin"
                                           placeholder="Please share which plugin">
                                </div>
                            </div>
                            <div>
                                <input id="couldnt_get_the_plugin_to_work" type="radio" name="reason_key"
                                       value="couldnt_get_the_plugin_to_work">
                                <label for="couldnt_get_the_plugin_to_work"><?php esc_html_e( 'I couldn\'t get the plugin to work', 'pb-settings' ); ?></label>
                            </div>
                            <div>
                                <input id="temporary_deactivation" type="radio" name="reason_key"
                                       value="temporary_deactivation">
                                <label for="temporary_deactivation"><?php esc_html_e( 'It\'s a temporary deactivation', 'pb-settings' ); ?></label>
                            </div>
                            <div>
                                <input id="using_pro" type="radio" name="reason_key" value="using_pro">
                                <label for="using_pro"><?php esc_html_e( 'I have the Pro Version', 'pb-settings' ); ?></label>
                                <div class="pbfb-extra-text">
									<?php printf( esc_html__( 'Wait! Dont deactivate %s. You have to activate both Free and Pro version in order for the plugin to work.', 'pb-settings' ), $plugin_name ); ?>
                                </div>
                            </div>
                            <div>
                                <input id="other" type="radio" name="reason_key" value="other">
                                <label for="other"><?php esc_html_e( 'Other', 'pb-settings' ); ?></label>
                                <div class="pbfb-extra-text">
                                    <input type="text" name="reason_other"
                                           placeholder="Please share the reason">
                                </div>
                            </div>
                        </div>
                        <div class="pbfb-buttons">
                            <div class="pbfb-button-submit"
                                 data-loading_text="<?php esc_attr_e( 'Sending...', 'pb-settings' ); ?>"
                                 data-ajax_url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
                                 data-plugin_slug="<?php echo esc_attr( $plugin_slug ); ?>"
                                 data-website="<?php echo esc_url( get_bloginfo( 'url' ) ); ?>"
                                 data-admin_email="<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>">
								<?php esc_html_e( 'Submit & Deactivate', 'pb-settings' ); ?>
                            </div>
                            <a class="pbfb-button-skip"
                               href="#"><?php esc_html_e( 'Skip & Deactivate', 'pb-settings' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                (function ($, window, document) {

                    $(document).on('click', '.pbfb-button-submit', function () {

                        let submitButton = $(this),
                            reasonKey = $('input[name="reason_key"]:checked'),
                            reasonBetterPlugin = $('input[name="reason_better_plugin"]'),
                            reasonOther = $('input[name="reason_other"]');

                        submitButton.html(submitButton.data('loading_text'));

                        $.ajax({
                            type: 'POST',
                            url: submitButton.data('ajax_url'),
                            context: this,
                            data: {
                                'action': 'pbfb_feedback_new',
                                'plugin_slug': submitButton.data('plugin_slug'),
                                'reason_key': reasonKey.val(),
                                'reason_better_plugin': reasonBetterPlugin.val(),
                                'reason_other': reasonOther.val(),
                                'website': submitButton.data('website'),
                                'admin_email': submitButton.data('admin_email'),
                            },
                            success: function (response) {
                                if (response.success) {
                                    window.location.href = $('.pbfb-button-skip').attr('href');
                                }
                            }
                        });
                    });

                    $(document).on('change', '.pbfb-container input[name="reason_key"]', function () {

                        let allExtraTexts = $('.pbfb-extra-text'),
                            submitButton = $('.pbfb-button-submit'),
                            checkBox = $(this),
                            checkBoxValue = checkBox.val(),
                            thisReason = checkBox.parent();

                        allExtraTexts.slideUp();

                        if (checkBoxValue === 'found_a_better_plugin' || checkBoxValue === 'using_pro' || checkBoxValue === 'other') {
                            thisReason.find('.pbfb-extra-text').slideDown();
                        }

                        checkBoxValue === 'using_pro' ? submitButton.addClass('disabled') : submitButton.removeClass('disabled');
                    });

                    $(document).on('click', '.wp-list-table.plugins .row-actions .deactivate > a', function () {

                        let deactivateButton = $(this),
                            deactivateURL = deactivateButton.attr('href'),
                            pluginTR = deactivateButton.parent().parent().parent().parent(),
                            pbfbContainer = $('.pbfb-container'),
                            pluginSlug = pluginTR.data('slug');

                        if (pluginSlug === '<?php echo esc_attr( $plugin_slug ); ?>') {
                            pbfbContainer.find('.pbfb-button-skip').attr('href', deactivateURL);
                            pbfbContainer.fadeIn();
                            return false;
                        }

                        return true;
                    });

                    $(document).mouseup(function (e) {
                        let container = $('.pbfb-container .pbfb-box');
                        if (!container.is(e.target) && container.has(e.target).length === 0) {
                            container.parent().fadeOut();
                        }
                    });

                })(jQuery, window, document);
            </script>
            <style>
                .pbfb-container {
                    background: none repeat scroll 0 0 rgba(0, 0, 0, 0.6);
                    display: none;
                    height: 100%;
                    left: 0;
                    position: fixed;
                    top: 0;
                    width: 100%;
                    z-index: 9999999;
                }

                .pbfb-container .pbfb-box {
                    background: none repeat scroll 0 0 rgb(255, 255, 255);
                    height: auto;
                    margin: 0 auto;
                    position: relative;
                    top: 15%;
                    max-width: 520px;
                    border-radius: 3px;
                    box-shadow: 0 10px 8px rgba(100, 100, 100, 0.9);
                    max-height: 550px;
                    overflow-y: auto;
                    user-select: none;
                }

                .pbfb-container .pbfb-header {
                    padding: 25px 30px;
                    text-transform: uppercase;
                    font-size: 18px;
                    letter-spacing: 1px;
                    font-weight: 600;
                    box-shadow: 0 5px 30px 2px rgba(0, 0, 0, 0.17);
                    overflow: hidden;
                }

                .pbfb-container .pbfb-content {
                    padding: 15px 30px 25px;
                }

                .pbfb-container .pbfb-content p {
                    font-size: 16px;
                    font-weight: 500;
                }

                .pbfb-container .pbfb-reasons label {
                    font-size: 16px;
                    line-height: 35px;
                    vertical-align: baseline;
                }

                .pbfb-container .pbfb-reasons .pbfb-extra-text {
                    display: none;
                    margin-left: 23px;
                    width: calc(100% - 20px);
                    line-height: 1.7;
                    color: #ab073e;
                }

                .pbfb-container .pbfb-reasons .pbfb-extra-text > input,
                .pbfb-container .pbfb-reasons .pbfb-extra-text > input:focus {
                    min-height: 40px;
                    width: 100%;
                    outline: none;
                    box-shadow: none;
                    border: 1px solid #ddd;
                }


                .pbfb-container .pbfb-buttons {
                    margin-top: 30px;
                }

                .pbfb-container .pbfb-buttons > *,
                .pbfb-container .pbfb-buttons > *:active,
                .pbfb-container .pbfb-buttons > *:focus {
                    cursor: pointer;
                    display: inline-block;
                    font-weight: 600;
                    letter-spacing: 0.8px;
                    text-decoration: none;
                    color: #4e4e4e;
                    outline: none;
                    box-shadow: none;
                }

                .pbfb-container .pbfb-button-submit {
                    background: #E91E63;
                    color: #fff !important;
                    padding: 10px 12px;
                    border-radius: 3px;
                    margin-right: 10px;
                    text-transform: uppercase;
                }

                .pbfb-container .pbfb-button-submit.disabled {
                    pointer-events: none;
                    background: #c7c7c7;
                }
            </style>
			<?php
		}


		/**
		 * Check if any plugin require to work the current plugin
		 *
		 * Required arguments in the initializer
		 *
		 * @arg string plugin_name | Current plugin name
		 * @arg array required_plugins | array of required plugins with key as the plugin slug and value as the plugin name or label
		 */
		function required_plugin_check() {

			$buttons = array();
			$plugins = array();

			foreach ( $this->get_data( 'required_plugins', array() ) as $plugin_slug => $label ) {

				$this_plugin = sprintf( '%1$s/%1$s.php', $plugin_slug );
				$plugins[]   = sprintf( '<strong>%s</strong>', $label );

				if ( is_plugin_active( $this_plugin ) ) {
					continue;
				}

				if ( $this->is_plugin_installed( $this_plugin ) ) {

					$button_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $this_plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $this_plugin );
					$buttons[]  = sprintf( '<a class="button-primary" href="%s">%s</a>', $button_url, sprintf( esc_html( 'Activate %s' ), $label ) );
				} else {
					$button_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
					$buttons[]  = sprintf( '<a class="button-primary" href="%s">%s</a>', $button_url, sprintf( esc_html( 'Install %s' ), $label ) );
				}
			}

			if ( count( $buttons ) > 0 ) {
				printf( '<div class="notice notice-error is-dismissible"><p>%s</p><p>%s</p></div>',
					sprintf( __( '<strong>%s</strong> plugin requires plugin(s): %s to be installed and activated. Please continue with installation or activation', 'woc-open-close' ),
						$this->get_data( 'plugin_name' ), implode( ', ', $plugins ), '<strong>', '</strong>'
					),
					implode( ' ', $buttons )
				);
			}
		}


		/**
		 * Check if a plugin installed in the plugins list or not
		 *
		 * @param $basename
		 *
		 * @return bool
		 */
		function is_plugin_installed( $basename ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				include_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			$installed_plugins = get_plugins();

			return isset( $installed_plugins[ $basename ] );
		}


		/**
		 * Register Shortcode
		 *
		 * @param string $shortcode
		 * @param string $callable_func
		 */
		function register_shortcode( $shortcode = '', $callable_func = '' ) {

			if ( empty( $shortcode ) || ! $shortcode || ! $callable_func || empty( $callable_func ) ) {
				return;
			}

			add_shortcode( $shortcode, $callable_func );
		}


		/**
		 * Register Taxonomy
		 *
		 * @param $tax_name
		 * @param $obj_type
		 * @param array $args
		 */
		function register_taxonomy( $tax_name, $obj_type, $args = array() ) {

			if ( taxonomy_exists( $tax_name ) ) {
				return;
			}

			$singular = isset( $args['singular'] ) ? $args['singular'] : '';
			$plural   = isset( $args['plural'] ) ? $args['plural'] : '';
			$labels   = isset( $args['labels'] ) ? $args['labels'] : array();

			$args = array_merge( array(
				'hierarchical'        => true,
				'description'         => sprintf( __( 'This is where you can create and manage %s.' ), $plural ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'rewrite'             => true,
				'query_var'           => true,
				'show_in_nav_menus'   => true,
				'show_in_menu'        => true,
			), $args );

			$args['labels'] = array_merge( array(
				'name'               => sprintf( __( '%s' ), $plural ),
				'singular_name'      => $singular,
				'menu_name'          => __( $plural ),
				'all_items'          => sprintf( __( '%s' ), $plural ),
				'add_new'            => sprintf( __( 'Add %s' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add %s' ), $singular ),
				'edit'               => __( 'Edit' ),
				'edit_item'          => sprintf( __( '%s Details' ), $singular ),
				'new_item'           => sprintf( __( 'New %s' ), $singular ),
				'view'               => sprintf( __( 'View %s' ), $singular ),
				'view_item'          => sprintf( __( 'View %s' ), $singular ),
				'search_items'       => sprintf( __( 'Search %s' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in trash' ), $plural ),
				'parent'             => sprintf( __( 'Parent %s' ), $singular ),
			), $labels );

			register_taxonomy( $tax_name, $obj_type, apply_filters( "pb_register_taxonomy_$tax_name", $args, $obj_type ) );
		}


		/**
		 * Register Post Type
		 *
		 * @param $post_type
		 * @param array $args
		 */
		function register_post_type( $post_type, $args = array() ) {

			if ( post_type_exists( $post_type ) ) {
				return;
			}

			$singular = isset( $args['singular'] ) ? $args['singular'] : '';
			$plural   = isset( $args['plural'] ) ? $args['plural'] : '';
			$labels   = isset( $args['labels'] ) ? $args['labels'] : array();

			$args = array_merge( array(
				'description'         => sprintf( __( 'This is where you can create and manage %s.' ), $plural ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'rewrite'             => true,
				'query_var'           => true,
				'supports'            => array( 'title', 'thumbnail', 'editor', 'author' ),
				'show_in_nav_menus'   => true,
				'show_in_menu'        => true,
				'menu_icon'           => '',
			), $args );

			$args['labels'] = array_merge( array(
				'name'               => sprintf( __( '%s' ), $plural ),
				'singular_name'      => $singular,
				'menu_name'          => __( $singular ),
				'all_items'          => sprintf( __( '%s' ), $plural ),
				'add_new'            => sprintf( __( 'Add %s' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add %s' ), $singular ),
				'edit'               => __( 'Edit' ),
				'edit_item'          => sprintf( __( 'Edit %s' ), $singular ),
				'new_item'           => sprintf( __( 'New %s' ), $singular ),
				'view'               => sprintf( __( 'View %s' ), $singular ),
				'view_item'          => sprintf( __( 'View %s' ), $singular ),
				'search_items'       => sprintf( __( 'Search %s' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in trash' ), $plural ),
				'parent'             => sprintf( __( 'Parent %s' ), $singular ),
			), $labels );

			register_post_type( $post_type, apply_filters( "pb_register_post_type_$post_type", $args ) );
		}


		/**
		 * Add Menu in WordPress Admin Menu
		 */
		function add_menu_in_admin_menu() {

			if ( "menu" == $this->get_menu_type() ) {
				$menu_ret = add_menu_page( $this->get_menu_name(), $this->get_menu_title(), $this->get_capability(), $this->get_menu_slug(), array(
					$this,
					'display_function'
				), $this->get_menu_icon(), $this->get_menu_position() );

				do_action( 'pb_settings_menu_added_' . $this->get_menu_slug(), $menu_ret );
			}

			if ( "submenu" == $this->get_menu_type() ) {
				$submenu_ret = add_submenu_page( $this->get_parent_slug(), $this->get_page_title(), $this->get_menu_title(), $this->get_capability(), $this->get_menu_slug(), array(
					$this,
					'display_function'
				) );

				do_action( 'pb_settings_submenu_added_' . $this->get_menu_slug(), $submenu_ret );
			}
		}


		/**
		 * Generate Settings Fields
		 *
		 * @param array $settings
		 * @param bool $post_id
		 * @param bool $custom_style
		 *
		 * @return string|mixed
		 */
		function generate_fields( $settings = array(), $post_id = false, $custom_style = true ) {

			if ( ! is_array( $settings ) ) {
				return '';
			}

			$post_id = ! $post_id ? 0 : $post_id;
			$post    = get_post( $post_id );

			foreach ( $settings as $key => $setting_section ) :

				if ( isset( $setting_section['title'] ) ) {
					printf( '<div style="padding: 0;font-size: 16px;margin: 10px 0;">%s</div>', $setting_section['title'] );
				}
				if ( isset( $setting_section['description'] ) ) {
					printf( '<p>%s</p>', $setting_section['description'] );
				}

				$options          = isset( $setting_section['options'] ) ? $setting_section['options'] : array();

				foreach ( $options as $option ) :

					$option_id = isset( $option['id'] ) ? $option['id'] : '';
					$option_title = isset( $option['title'] ) ? $option['title'] : '';
					$option_class = isset( $option['class'] ) ? $option['class'] : '';
					$option_type  = isset( $option['type'] ) ? $option['type'] : '';
					$field_id     = str_replace( array( '[', ']' ), '', $option_id );

					if ( $post_id && ! empty( $post_id ) ) {

						if ( $option_id == 'post_title' ) {
							$option['value'] = $post->post_title != 'Auto Draft' ? $post->post_title : '';
						} else if ( $option_id == 'content' ) {
							$option['value'] = $post->post_content;
						} else {
							$option['value'] = get_post_meta( $post_id, $option_id, true );
						}

						$option['post_id'] = $post_id;
					}

					?>
                    <div class="wps-field <?php echo esc_attr( implode( ' ', array(
						$option_class,
						$option_type
					) ) ); ?>">
                        <label for="<?php echo esc_attr( $field_id ); ?>"
                               class="wps-field-inline wps-field-title"><?php echo esc_html( $option_title ); ?></label>

                        <div class="wps-field-inline wps-field-inputs">
							<?php $this->field_generator( $option ); ?>
                        </div>

                    </div>
				<?php
				endforeach;

			endforeach;

			if ( $custom_style ) {
				printf( '<style>%s</style>', '.wps-field {padding: 10px 0;}.wps-field .wps-field-inline {display: inline-block;vertical-align: top;}.wps-field .wps-field-title {font-size: 14px;width: 120px;min-width: 120px;font-weight: 500;}.wps-field .wps-field-inputs {margin-left: 15px;width: 76%;min-width: 320px;} .wps-field .wps-field-inputs input[type=text], .wps-field .wps-field-inputs textarea, .wps-field .wps-field-inputs input[type=number]{border-radius:4px; padding: 7px 5px; height: inherit;}' );
			}
		}


		/**
		 * Display Settings Fields
		 */
		function display_fields() {

			foreach ( $this->get_settings_fields() as $key => $setting ):

				add_settings_section( $key, isset( $setting['title'] ) ? $setting['title'] : "", array(
					$this,
					'section_callback'
				), $this->get_current_page() );

				foreach ( $setting['options'] as $option ) :

					$option_id    = isset( $option['id'] ) ? $option['id'] : '';
					$option_title = isset( $option['title'] ) ? $option['title'] : '';

					if ( empty( $option_id ) ) {
						continue;
					}

					add_settings_field( $option_id, $option_title, array(
						$this,
						'field_generator'
					), $this->get_current_page(), $key, $option );

				endforeach;

			endforeach;
		}


		/**
		 * Generate field automatically from $option
		 *
		 * @param $option
		 */
		function field_generator( $option ) {

			$id      = isset( $option['id'] ) ? $option['id'] : "";
			$details = isset( $option['details'] ) ? $option['details'] : "";

			if ( empty( $id ) ) {
				return;
			}

			do_action( "pb_settings_before_$id", $option );

			if ( isset( $option['type'] ) && ! empty( $field_type = $option['type'] ) ) {

				if ( method_exists( $this, "generate_$field_type" ) && is_callable( array(
						$this,
						"generate_$field_type"
					) ) ) {
					call_user_func( array( $this, "generate_$field_type" ), $option );
				}
			}

			if ( isset( $option['disabled'] ) && $option['disabled'] && ! empty( $this->get_disabled_notice() ) ) {
				printf( '<span class="disabled-notice" style="background: #ffe390eb;margin-left: 10px;padding: 5px 12px;font-size: 12px;border-radius: 3px;color: #717171;">%s</span>', $this->get_disabled_notice() );
			}

			do_action( "pb_settings_before_option", $option );

			do_action( "pb_settings_$id", $option );

			if ( ! empty( $details ) ) {
				echo "<p class='description'>$details</p>";
			}

			do_action( "pb_settings_after_option", $option );

			do_action( "pb_settings_after_$id", $option );
		}


		/**
		 * Generate Field - Gallery
		 *
		 * @param $option
		 */
		function generate_gallery( $option ) {

			$id       = isset( $option['id'] ) ? $option['id'] : "";
			$disabled = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$value    = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$value    = is_array( $value ) ? $value : array( $value );
			$value    = array_filter( $value );
			$html     = "";

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			wp_enqueue_media();
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-sortable' );

			foreach ( $value as $attachment_id ) {

				$media_url = wp_get_attachment_url( $attachment_id );

				$html .= "<div><span onclick='this.parentElement.remove()' class='dashicons dashicons-trash'></span><img src='{$media_url}' />";
				$html .= "<input type='hidden' name='{$id}[]' value='{$attachment_id}'/>";
				$html .= "</div>";
			}

			?>
            <div id="media_preview_<?php echo esc_attr( $id ); ?>">
				<?php echo esc_html( $html ); ?>
            </div>
            <div class='button' <?php echo esc_attr( $disabled ); ?>
                 id="media_upload_<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Select Images' ); ?></div>

            ?>
            <script>
                jQuery(document).ready(function ($) {

                    $('#media_upload_<?php echo $id; ?>').click(function () {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        wp.media.editor.send.attachment = function (props, attachment) {

                            html = "<div><span onclick='this.parentElement.remove()' class='dashicons dashicons-trash'></span><img src='" + attachment.url + "' />";
                            html += "<input type='hidden' name='<?php echo $id; ?>[]' value='" + attachment.id + "'/>";
                            html += "</div>";

                            $('#media_preview_<?php echo $id; ?>').append(html);
                        }
                        wp.media.editor.open($(this));
                        wp.media.multiple = false;
                        return false;
                    });

                    $(function () {
                        $('#media_preview_<?php echo $id; ?>').sortable({
                            handle: 'img',
                            revert: false,
                            axis: "x",
                        });
                    });
                });
            </script>
            <style>
                #media_preview_<?php echo $id; ?> > div {
                    display: inline-block;
                    vertical-align: top;
                    width: 180px;
                    border: 1px solid #ddd;
                    padding: 12px;
                    margin: 0 10px 10px 0;
                    border-radius: 4px;
                    position: relative;
                }

                #media_preview_<?php echo $id; ?> > div:hover span {
                    display: block;
                }

                #media_preview_<?php echo $id; ?> > div > span {
                    display: none;
                    cursor: pointer;
                    background: #ddd;
                    padding: 2px;
                    position: absolute;
                    top: 0px;
                    left: 0;
                    font-size: 16px;
                    border-bottom-right-radius: 4px;
                    color: #f443369c;
                }

                #media_preview_<?php echo $id; ?> > div > img {
                    width: 100%;
                    cursor: move;
                }
            </style>
			<?php
		}


		/**
		 * Generate Field - Media
		 *
		 * @param $option
		 */
		function generate_media( $option ) {

			$id          = isset( $option['id'] ) ? $option['id'] : "";
			$field_id    = str_replace( array( '[', ']' ), '_', $id );
			$value       = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$disabled    = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$media_url   = wp_get_attachment_url( $value );
			$media_ext   = pathinfo( $media_url, PATHINFO_EXTENSION );
			$media_title = get_the_title( $value );

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			wp_enqueue_media();

			?>
            <div class="media_preview"
                 style="width: 150px;margin-bottom: 10px;background: #d2d2d2;padding: 15px 5px;text-align: center;border-radius: 5px;">

				<?php if ( in_array( $media_ext, array( 'mp3', 'wav' ) ) ) : ?>

                    <div id="media_preview_<?php echo esc_attr( $field_id ); ?>"
                         class="dashicons dashicons-format-audio" style="font-size: 70px;display: inline;"></div>
                    <div><?php echo esc_html( $media_title ); ?></div>

				<?php else : ?>
                    <img id="media_preview_<?php echo esc_attr( $field_id ); ?>"
                         src="<?php echo esc_url( $media_url ); ?>" style="width:100%"/>
				<?php endif; ?>

            </div>
            <input type="hidden" name="<?php echo esc_attr( $id ); ?>"
                   id="media_input_<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
            <div class="button" <?php echo esc_attr( $disabled ); ?>
                 id="media_upload_<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Upload' ); ?></div>

			<?php if ( ! empty( $value ) ) : ?>
                <div class="button button-primary"
                     id="media_upload_<?php echo esc_attr( $field_id ); ?>_remove"><?php esc_html_e( 'Remove' ); ?></div>
			<?php endif; ?>

            <script>
                jQuery(document).ready(function ($) {

                    $(document).on('click', '#media_upload_<?php echo esc_attr( $field_id ); ?>_remove', function () {
                        $(this).parent().find('.media_preview img').attr('src', '');
                        $(this).parent().find('#media_input_<?php echo esc_attr( $field_id ); ?>').val('');
                    });

                    $(document).on('click', '#media_upload_<?php echo esc_attr( $field_id ); ?>', function () {
                        var send_attachment_bkp = wp.media.editor.send.attachment;
                        wp.media.editor.send.attachment = function (props, attachment) {
                            $("#media_preview_<?php echo esc_attr( $field_id ); ?>").attr('src', attachment.url);
                            $("#media_input_<?php echo esc_attr( $field_id ); ?>").val(attachment.id);
                            wp.media.editor.send.attachment = send_attachment_bkp;
                        };
                        wp.media.editor.open($(this));
                        return false;
                    });
                });
            </script>
			<?php
		}


		/**
		 * Generate Field - Range
		 *
		 * @param $option
		 */
		function generate_range( $option ) {

			$id       = isset( $option['id'] ) ? $option['id'] : "";
			$min      = isset( $option['min'] ) ? $option['min'] : 1;
			$max      = isset( $option['max'] ) ? $option['max'] : 100;
			$value    = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$disabled = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$value    = empty( $value ) ? $min : $value;

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			?>
            <input class="pick_range"
                   type="range" <?php echo esc_attr( $disabled ); ?>
                   min="<?php echo esc_attr( $min ); ?>"
                   max="<?php echo esc_attr( $max ); ?>"
                   name="<?php echo esc_attr( $id ); ?>"
                   id="<?php echo esc_attr( $id ); ?>"
                   value="<?php echo esc_attr( $value ); ?>">
            <span class="show_value"
                  id="<?php echo esc_attr( $id ); ?>_show_value"><?php echo esc_html( $value ); ?></span>
            <style>
                .pick_range {
                    -webkit-appearance: none;
                    width: 280px;
                    height: 7px;
                    background: #9a9a9a;
                    outline: none;
                }

                .show_value {
                    font-size: 17px;
                    margin-left: 8px;
                }

                .pick_range::-webkit-slider-thumb {
                    -webkit-appearance: none;
                    appearance: none;
                    width: 25px;
                    height: 25px;
                    border-radius: 50%;
                    background: #138E77;
                    cursor: pointer;
                }

                .pick_range::-moz-range-thumb {
                    width: 25px;
                    height: 25px;
                    border-radius: 50%;
                    background: #138E77;
                    cursor: pointer;
                }
            </style>
            <script>
                jQuery(document).ready(function ($) {
                    $('#<?php echo esc_attr( $id ); ?>').on('input', function (e) {
                        $('#<?php echo esc_attr( $id ); ?>_show_value').html($('#<?php echo esc_attr( $id ); ?>').val());
                    });
                })
            </script>
			<?php
		}


		/**
		 * Generate Select 2
		 *
		 * @param $option
		 */
		function generate_select2( $option ) {

			$id            = isset( $option['id'] ) ? $option['id'] : "";
			$args          = isset( $option['args'] ) ? $option['args'] : array();
			$args          = is_array( $args ) ? $args : $this->generate_args_from_string( $args, $option );
			$value         = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$multiple      = isset( $option['multiple'] ) ? $option['multiple'] : false;
			$required      = isset( $option['required'] ) ? $option['required'] : false;
			$disabled      = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$required      = $required ? "required='required'" : '';
			$name          = $multiple ? sprintf( '%s[]', $id ) : sprintf( '%s', $id );
			$multiple_text = $multiple ? 'multiple' : '';
			$field_options = isset( $option['field_options'] ) ? $option['field_options'] : array();
			$field_options = preg_replace( '/"([^"]+)"\s*:\s*/', '$1:', json_encode( $field_options ) );

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css' );
			wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js', array( 'jquery' ) );

			if ( $multiple && ! is_array( $value ) ) {
				$value = array( $value );
			}
			if ( ! $multiple && is_array( $value ) ) {
				$value = reset( $value );
			}

			?>
            <select <?php echo esc_attr( $disabled ); ?> <?php echo esc_attr( $required ); ?> <?php echo esc_attr( $multiple_text ); ?>
                    name="<?php echo esc_attr( $name ); ?>"
                    id="<?php echo esc_attr( $id ) ?>">

				<?php

				if ( ! $multiple ) {
					printf( '<option value="">%s</option>', esc_html__( 'Select your choice' ) );
				}

				foreach ( $args as $key => $name ) {

					if ( $multiple ) {
						$selected = in_array( $key, $value ) ? "selected" : "";
					} else {
						$selected = $value == $key ? "selected" : "";
					}

					printf( '<option %s value="%s">%s</option>', $selected, $key, $name );
				}
				?>
            </select>
            <script>
                jQuery(document).ready(function ($) {
                    $("#<?php echo esc_attr( $id ); ?>").select2( <?php echo wp_kses_post( $field_options ); ?> );
                });
            </script>
			<?php
		}


		/**
		 * Generate Field - Datepicker
		 *
		 * @param $option
		 */
		function generate_datepicker( $option ) {

			$id            = isset( $option['id'] ) ? $option['id'] : "";
			$placeholder   = isset( $option['placeholder'] ) ? $option['placeholder'] : "";
			$autocomplete  = isset( $option['autocomplete'] ) ? $option['autocomplete'] : "off";
			$value         = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$disabled      = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$field_options = isset( $option['field_options'] ) ? $option['field_options'] : array();
			$field_options = preg_replace( '/"([^"]+)"\s*:\s*/', '$1:', json_encode( $field_options ) );
			$field_id      = str_replace( array( '[', ']' ), '', $id );

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
			wp_enqueue_script( 'jquery-ui-datepicker' );

			?>
            <input type="text" <?php echo esc_attr( $disabled ); ?>
                   name="<?php echo esc_attr( $id ); ?>"
                   id="<?php echo esc_attr( $field_id ); ?>"
                   autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>"
                   value="<?php echo esc_attr( $value ); ?>"/>

            <script>
                jQuery(document).ready(function ($) {
                    $("#<?php echo esc_attr( $field_id ); ?>").datepicker( <?php echo wp_kses_post( $field_options ); ?> );
                });
            </script>
			<?php
		}


		/**
		 * Generate Field - TimePicker
		 *
		 * @param $option
		 */
		function generate_timepicker( $option ) {

			$id            = isset( $option['id'] ) ? $option['id'] : "";
			$placeholder   = isset( $option['placeholder'] ) ? $option['placeholder'] : "";
			$autocomplete  = isset( $option['autocomplete'] ) ? $option['autocomplete'] : "";
			$value         = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$disabled      = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$field_options = isset( $option['field_options'] ) ? $option['field_options'] : array();
			$field_options = preg_replace( '/"([^"]+)"\s*:\s*/', '$1:', json_encode( $field_options ) );
			$field_id      = str_replace( array( '[', ']' ), '', $id );

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			wp_enqueue_style( 'jquery-ui-timepicker', '//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css' );
			wp_enqueue_script( 'jquery-ui-timepicker', '//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js' );

			?>
            <input type="text" <?php echo esc_attr( $disabled ); ?>
                   name="<?php echo esc_attr( $id ); ?>"
                   id="<?php echo esc_attr( $field_id ); ?>"
                   autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>"
                   value="<?php echo esc_attr( $value ); ?>"/>

            <script>
                jQuery(document).ready(function ($) {
                    $("#<?php echo esc_attr( $field_id ); ?>").timepicker( <?php echo wp_kses_post( $field_options ); ?> );
                });
            </script>
			<?php
		}

		/**
		 * Generate Field - wp_editor
		 *
		 * @param $option
		 */
		function generate_wp_editor( $option ) {

			$id            = isset( $option['id'] ) ? $option['id'] : "";
			$post_id       = isset( $option['post_id'] ) ? $option['post_id'] : '';
			$field_options = isset( $option['field_options'] ) ? $option['field_options'] : array();
			$post          = get_post( $post_id );

			wp_editor( $post->post_content, $id, $field_options );

			?>
            <style>
                #wp-content-editor-tools {
                    background-color: #fff;
                    padding-top: 0;
                }
            </style>
			<?php
		}


		/**
		 * Generate Field - Color Picker
		 *
		 * @param $option
		 */
		function generate_colorpicker( $option ) {

			$id          = isset( $option['id'] ) ? $option['id'] : "";
			$field_id    = str_replace( array( '[', ']' ), '_', $id );
			$placeholder = isset( $option['placeholder'] ) ? $option['placeholder'] : "";
			$value       = isset( $option['value'] ) ? $option['value'] : get_option( $id );

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			?>
            <input type="text"
                   name="<?php echo esc_attr( $id ); ?>"
                   id="<?php echo esc_attr( $field_id ); ?>"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>"
                   value="<?php echo esc_attr( $value ); ?>"/>

            <script>
                jQuery(document).ready(function ($) {
                    $('#<?php echo esc_attr( $field_id ); ?>').wpColorPicker();

					<?php if( isset( $option['disabled'] ) && $option['disabled'] ) : ?>
                    $('#<?php echo esc_attr( $field_id ); ?>').parent().parent().parent().find('button.wp-color-result').prop('disabled', true);
					<?php endif; ?>
                });
            </script>
			<?php
		}


		/**
		 * Generate Field - Text
		 *
		 * @param $option
		 */
		function generate_text( $option ) {

			$id           = isset( $option['id'] ) ? $option['id'] : "";
			$placeholder  = isset( $option['placeholder'] ) ? $option['placeholder'] : "";
			$autocomplete = isset( $option['autocomplete'] ) ? $option['autocomplete'] : "";
			$value        = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$required     = isset( $option['required'] ) ? $option['required'] : false;
			$required     = $required ? "required='required'" : '';
			$disabled     = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$field_id     = str_replace( array( '[', ']' ), '', $id );

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			?>
            <input type="text" <?php echo esc_attr( $disabled ); ?> <?php echo esc_attr( $required ); ?>

                   name="<?php echo esc_attr( $id ); ?>"
                   id="<?php echo esc_attr( $field_id ); ?>"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>"
                   autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
                   value="<?php echo esc_attr( $value ); ?>"/>
			<?php
		}


		/**
		 * Generate Field - Number
		 *
		 * @param $option
		 */
		function generate_number( $option ) {

			$id          = isset( $option['id'] ) ? $option['id'] : "";
			$placeholder = isset( $option['placeholder'] ) ? $option['placeholder'] : "";
			$value       = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$required    = isset( $option['required'] ) ? $option['required'] : false;
			$required    = $required ? "required='required'" : '';
			$disabled    = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			?>
            <input type="number"
				<?php echo esc_attr( $disabled ); ?> <?php echo esc_attr( $required ); ?>
                   name="<?php echo esc_attr( $id ); ?>"
                   id="<?php echo esc_attr( $id ); ?>"
                   placeholder="<?php echo esc_attr( $placeholder ); ?>"
                   value="<?php echo esc_attr( $value ); ?>"/>
			<?php
		}


		/**
		 * Generate Field - Textarea
		 *
		 * @param $option
		 */
		function generate_textarea( $option ) {

			$id          = isset( $option['id'] ) ? $option['id'] : "";
			$placeholder = isset( $option['placeholder'] ) ? $option['placeholder'] : "";
			$rows        = isset( $option['rows'] ) ? $option['rows'] : 4;
			$value       = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$required    = isset( $option['required'] ) ? $option['required'] : false;
			$required    = $required ? "required='required'" : '';
			$disabled    = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			?>
            <textarea cols="40" <?php echo esc_attr( $disabled ); ?> <?php echo esc_attr( $required ); ?>
                      rows="<?php echo esc_attr( $rows ); ?>"
                      name="<?php echo esc_attr( $id ); ?>"
                      id="<?php echo esc_attr( $id ); ?>"
                      placeholder="<?php echo esc_attr( $placeholder ); ?>"><?php echo esc_html( $value ); ?></textarea>
			<?php
		}


		/**
		 * Generate Field - Select
		 *
		 * @param $option
		 */
		function generate_select( $option ) {

			$id       = isset( $option['id'] ) ? $option['id'] : "";
			$args     = isset( $option['args'] ) ? $option['args'] : array();
			$args     = is_array( $args ) ? $args : $this->generate_args_from_string( $args, $option );
			$value    = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$required = isset( $option['required'] ) ? $option['required'] : false;
			$required = $required ? "required='required'" : '';
			$disabled = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? reset( $option['default'] ) : $value;
			}

			?>
            <select <?php echo esc_attr( $disabled ); ?> <?php echo esc_attr( $required ); ?>
                    name="<?php echo esc_attr( $id ); ?>"
                    id="<?php echo esc_attr( $id ); ?>">

				<?php

				printf( '<option value="">%s</option>', esc_html__( 'Select your choice' ) );

				foreach ( $args as $key => $name ) {
					printf( '<option %s value="%s">%s</option>', $value == $key ? "selected" : "", $key, $name );
				}

				?>
            </select>
			<?php
		}


		/**
		 * Generate Field - Checkbox
		 *
		 * @param $option
		 */
		function generate_checkbox( $option ) {

			$id       = isset( $option['id'] ) ? $option['id'] : "";
			$args     = isset( $option['args'] ) ? $option['args'] : array();
			$args     = is_array( $args ) ? $args : $this->generate_args_from_string( $args, $option );
			$value    = isset( $option['value'] ) ? $option['value'] : get_option( $id );
			$disabled = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$cb_items = array();

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			foreach ( $args as $key => $val ) {

				$checked    = is_array( $value ) && in_array( $key, $value ) ? "checked" : "";
				$cb_items[] = sprintf( '<label for="%1$s_%2$s"><input %3$s %4$s type="checkbox" id="%1$s_%2$s" name="%1$s[]" value="%2$s">%5$s</label>',
					$id, $key, $disabled, $checked, $val
				);
			}

			printf( '<fieldset>%s</fieldset>', implode( '<br>', $cb_items ) );
		}


		/**
		 * Generate Field - Radio
		 *
		 * @param $option
		 */
		function generate_radio( $option ) {

			$option_id = isset( $option['id'] ) ? $option['id'] : "";
			$args      = isset( $option['args'] ) ? $option['args'] : array();
			$args      = is_array( $args ) ? $args : $this->generate_args_from_string( $args, $option );
			$value     = isset( $option['value'] ) ? $option['value'] : get_option( $option_id );
			$disabled  = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			?>
            <fieldset>
				<?php
				foreach ( $args as $key => $val ) {
					$checked = is_array( $value ) && in_array( $key, $value ) ? "checked" : "";
					printf( '<label><input %1$s %2$s type="radio" name="%3$s[]" value="%4$s">%5$s</label><br>',
						$disabled, $checked, $option_id, $key, $val
					);
				}
				?>
            </fieldset>
			<?php
		}


		/**
		 * Generate Image Select Field
		 *
		 * @param $option
		 */
		function generate_image_select( $option ) {

			$option_id  = isset( $option['id'] ) ? $option['id'] : "";
			$args       = isset( $option['args'] ) ? $option['args'] : array();
			$value      = isset( $option['value'] ) ? $option['value'] : get_option( $option_id );
			$disabled   = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled' : '';
			$multiple   = isset( $option['multiple'] ) && $option['multiple'] ? true : false;
			$input_type = $multiple ? 'checkbox' : 'radio';

			if ( empty( $value ) || ! $value ) {
				$value = isset( $option['default'] ) ? $option['default'] : $value;
			}

			$value = is_array( $value ) ? $value : array( $value );

			?>
            <div class="image-select">
				<?php
				foreach ( $args as $key => $val ) {
					$checked = is_array( $value ) && in_array( $key, $value ) ? "checked" : "";
					printf( '<label class="%2$s"><input %1$s %2$s type="%6$s" name="%3$s[]" value="%4$s"><img src="%5$s" /></label>',
						$disabled, $checked, $option_id, $key, $val, $input_type
					);
				}
				?>
            </div>

			<?php if ( ! in_array( 'image_select', $this->checked ) ) : ?>
                <style>
                    .image-select > label {
                        display: inline-block;
                        width: 120px;
                        margin: 0 15px 15px 0;
                        position: relative;
                        border: 1px solid #d1d1d1;
                        border-radius: 5px;
                    }

                    .image-select > label.checked:after {
                        content: '✔';
                        position: absolute;
                        width: 30px;
                        height: 30px;
                        background: #4CAF50;
                        color: #fff;
                        top: -10px;
                        right: -10px;
                        border-radius: 50%;
                        text-align: center;
                        line-height: 30px;
                    }

                    .image-select > label > input[type="radio"],
                    .image-select > label > input[type="checkbox"] {
                        display: none;
                    }

                    .image-select > label > img {
                        width: 100%;
                        transition: 0.3s;
                        border-radius: 5px;
                    }

                    .image-select > label.checked > img {
                        opacity: 0.7;
                        border-radius: 5px;
                    }
                </style>
                <script>
                    jQuery(document).ready(function ($) {
                        $('.image-select > label > input').on('change', function () {
                            if ($(this).attr('type') === 'radio') {
                                $(this).parent().parent().find('> label').removeClass('checked');
                            }

                            if ($(this).is(":checked")) {
                                $(this).parent().addClass('checked');
                            } else {
                                $(this).parent().removeClass('checked');
                            }
                        });
                    });
                </script>
			<?php
			endif;

			$this->checked[] = 'image_select';
		}

		/**
		 * Section Callback
		 *
		 * @param $section
		 */
		function section_callback( $section ) {

			$data        = isset( $section['callback'][0]->data ) ? $section['callback'][0]->data : array();
			$description = isset( $data['pages'][ $this->get_current_page() ]['page_settings'][ $section['id'] ]['description'] ) ? $data['pages'][ $this->get_current_page() ]['page_settings'][ $section['id'] ]['description'] : "";

			echo $description;
		}


		/**
		 * Add new options to $whitelist_options
		 *
		 * @param $whitelist_options
		 *
		 * @return mixed
		 */
		function whitelist_options( $whitelist_options ) {

			foreach ( $this->get_pages() as $page_id => $page ) :
				$page_settings = isset( $page['page_settings'] ) ? $page['page_settings'] : array();
				foreach ( $page_settings as $section ):
					foreach ( $section['options'] as $option ):
						$whitelist_options[ $page_id ][] = $option['id'];
					endforeach;
				endforeach;
			endforeach;

			return $whitelist_options;
		}


		/**
		 * Display Settings Tab Page
		 */
		function display_function() {

			?>
            <div class="wrap">
                <h2><?php echo esc_html( $this->get_menu_page_title() ); ?></h2><br>

				<?php
				settings_errors();

				do_action( 'pb_settings_before_page_' . $this->get_current_page(), $this );

				$this->get_settings_nav_tab();

				if ( $this->show_submit_button() ) {
					printf( '<form class="pb_settings_form" action="options.php" method="post">%s%s</form>', $this->get_settings_fields_html(), get_submit_button() );
				} else {
					print( $this->get_settings_fields_html() );
				}

				do_action( $this->get_current_page(), $this );

				do_action( 'pb_settings_after_page_' . $this->get_current_page(), $this );
				?>
            </div>
			<?php
		}


		/**
		 * Return settings navigation tabs
		 */
		function get_settings_nav_tab() {

			global $pagenow;

			parse_str( $_SERVER['QUERY_STRING'], $nav_url_args );

			?>
            <nav class="nav-tab-wrapper">
				<?php
				foreach ( $this->get_pages() as $page_id => $page ) {

					$active              = $this->get_current_page() == $page_id ? 'nav-tab-active' : '';
					$nav_url_args['tab'] = $page_id;
					$nav_menu_url        = http_build_query( $nav_url_args );
					$page_nav            = isset( $page['page_nav'] ) ? $page['page_nav'] : '';

					printf( '<a href="%s?%s" class="nav-tab %s">%s</a>', $pagenow, $nav_menu_url, $active, $page_nav );
				}

				do_action( 'pb_settings_after_nav_tab' );
				?>
            </nav>
			<?php
		}


		/**
		 * Return All Settings HTML
		 *
		 * @return false|string
		 */
		function get_settings_fields_html() {

			ob_start();

			do_action( 'pb_settings_page_' . $this->get_current_page() );

			settings_fields( $this->get_current_page() );
			do_settings_sections( $this->get_current_page() );

			return ob_get_clean();
		}


		/**
		 * Generate and return arguments from string
		 *
		 * @param $string
		 * @param $option
		 *
		 * @return array|mixed|void
		 */
		function generate_args_from_string( $string, $option ) {

			if ( strpos( $string, 'PAGES' ) !== false ) {
				return $this->get_pages_array();
			}
			if ( strpos( $string, 'USERS' ) !== false ) {
				return $this->get_users_array();
			}
			if ( strpos( $string, 'TAX_' ) !== false ) {
				$taxonomies = $this->get_taxonomies_array( $string, $option );

				return is_wp_error( $taxonomies ) ? array() : $taxonomies;
			}
			if ( strpos( $string, 'POSTS_' ) !== false ) {
				$posts = $this->get_posts_array( $string, $option );

				return is_wp_error( $posts ) ? array() : $posts;
			}
			if ( strpos( $string, 'TIME_ZONES' ) !== false ) {
				return $this->get_timezones_array( $string, $option );
			}

			return array();
		}


		/**
		 * Return WP Timezones as Array
		 *
		 * @param $string
		 * @param $option
		 *
		 * @return mixed
		 */
		function get_timezones_array( $string, $option ) {

			foreach ( timezone_identifiers_list() as $time_zone ) {
				$arr_items[ $time_zone ] = str_replace( '/', ' > ', $time_zone );
			}

			return $arr_items;
		}


		/**
		 * Return Posts as Array
		 *
		 * @param $string
		 * @param $option
		 *
		 * @return array | WP_Error
		 */
		function get_posts_array( $string, $option ) {

			$arr_posts = array();

			preg_match_all( "/\%([^\]]*)\%/", $string, $matches );

			if ( isset( $matches[1][0] ) ) {
				$post_type = $matches[1][0];
			} else {
				$post_type = 'post';
			}

			if ( ! post_type_exists( $post_type ) ) {
				return new WP_Error( 'not_found', sprintf( 'Post type <strong>%s</strong> does not exists !', $post_type ) );
			}

			$wp_query = isset( $option['wp_query'] ) ? $option['wp_query'] : array();
			$ppp      = isset( $wp_query['posts_per_page'] ) ? $option['posts_per_page'] : - 1;
			$wp_query = array_merge( $wp_query, array( 'post_type' => $post_type, 'posts_per_page' => $ppp ) );
			$posts    = get_posts( $wp_query );

			foreach ( $posts as $post ) {
				$arr_posts[ $post->ID ] = $post->post_title;
			}

			return $arr_posts;
		}


		/**
		 * Get taxonomies as Array
		 *
		 * @param $string
		 * @param $option
		 *
		 * @return array|WP_Error
		 */
		function get_taxonomies_array( $string, $option ) {

			$taxonomies = array();

			preg_match_all( "/\%([^\]]*)\%/", $string, $matches );

			if ( isset( $matches[1][0] ) ) {
				$taxonomy = $matches[1][0];
			} else {
				return new WP_Error( 'invalid_declaration', 'Invalid taxonomy declaration !' );
			}

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return new WP_Error( 'not_found', sprintf( 'Taxonomy <strong>%s</strong> does not exists !', $taxonomy ) );
			}

			$terms = get_terms( $taxonomy, array(
				'hide_empty' => false,
			) );

			foreach ( $terms as $term ) {
				$taxonomies[ $term->term_id ] = $term->name;
			}

			return $taxonomies;
		}


		/**
		 * Get pages as Array
		 *
		 * @return mixed|void
		 */
		function get_pages_array() {

			$pages_array = array();
			foreach ( get_pages() as $page ) {
				$pages_array[ $page->ID ] = $page->post_title;
			}

			return apply_filters( 'pb_settings_filter_pages', $pages_array );
		}


		/**
		 * Get users as Array
		 *
		 * @return mixed|void
		 */
		function get_users_array() {

			$user_array = array();
			foreach ( get_users() as $user ) {
				$user_array[ $user->ID ] = $user->display_name;
			}

			return apply_filters( 'pb_settings_filter_users', $user_array );
		}


		/**
		 * Return All options ids as Array
		 *
		 * @return array
		 */
		function get_option_ids() {

			$option_ids = array_map( function ( $option ) {
				return isset( $option['id'] ) ? $option['id'] : '';
			}, $this->get_options() );

			return $option_ids;
		}


		/**
		 * Set options from Data object
		 */
		private function set_options() {

			foreach ( $this->get_pages() as $page ):
				$setting_sections = isset( $page['page_settings'] ) ? $page['page_settings'] : array();
				foreach ( $setting_sections as $setting_section ):
					if ( isset( $setting_section['options'] ) ) {
						$this->options = array_merge( $this->options, $setting_section['options'] );
					}
				endforeach;
			endforeach;
		}


		/**
		 * Return Options
		 *
		 * @return array
		 */
		function get_options() {
			return $this->options;
		}


		/**
		 * Return whether Submit button to show or hide
		 *
		 * @return bool
		 */
		private function show_submit_button() {
			return isset( $this->get_pages()[ $this->get_current_page() ]['show_submit'] )
				? $this->get_pages()[ $this->get_current_page() ]['show_submit']
				: true;
		}


		/**
		 * Return Current Page
		 *
		 * @return mixed|string
		 */
		function get_current_page() {

			$all_pages   = $this->get_pages();
			$page_keys   = array_keys( $all_pages );
			$default_tab = ! empty( $all_pages ) ? reset( $page_keys ) : "";

			return isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $default_tab;
		}


		/**
		 * Return menu type
		 *
		 * @return mixed|string
		 */
		private function get_menu_type() {
			if ( isset( $this->data['menu_type'] ) ) {
				return $this->data['menu_type'];
			} else {
				return "main";
			}
		}


		/**
		 * Return Pages
		 *
		 * @return array|mixed
		 */
		private function get_pages() {
			if ( isset( $this->data['pages'] ) ) {
				$pages = $this->data['pages'];
			} else {
				return array();
			}

			$pages_sorted = array();
			$increment    = 0;

			foreach ( $pages as $page_key => $page ) {

				$increment += 5;
				$priority  = isset( $page['priority'] ) ? $page['priority'] : $increment;

				$pages_sorted[ $page_key ] = $priority;
			}
			array_multisort( $pages_sorted, SORT_ASC, $pages );

			return $pages;
		}


		/**
		 * Return settings fields
		 *
		 * @return array
		 */
		private function get_settings_fields() {
			if ( isset( $this->get_pages()[ $this->get_current_page() ]['page_settings'] ) ) {
				return $this->get_pages()[ $this->get_current_page() ]['page_settings'];
			} else {
				return array();
			}
		}


		/**
		 * @return mixed|string
		 */
		private function get_menu_position() {
			if ( isset( $this->data['position'] ) ) {
				return $this->data['position'];
			} else {
				return 60;
			}
		}


		/**
		 * @return mixed|string
		 */
		private function get_menu_icon() {
			if ( isset( $this->data['menu_icon'] ) ) {
				return $this->data['menu_icon'];
			} else {
				return "dashicons-admin-tools";
			}
		}


		/**
		 * Return menu slug
		 *
		 * @return mixed|string
		 */
		function get_menu_slug() {
			if ( isset( $this->data['menu_slug'] ) ) {
				return $this->data['menu_slug'];
			} else {
				return "my-custom-settings";
			}
		}


		/**
		 * Get user capability
		 *
		 * @return mixed|string
		 */
		private function get_capability() {
			if ( isset( $this->data['capability'] ) ) {
				return $this->data['capability'];
			} else {
				return "manage_options";
			}
		}


		/**
		 * Return menu page title
		 *
		 * @return mixed|string
		 */
		private function get_menu_page_title() {
			if ( isset( $this->data['menu_page_title'] ) ) {
				return $this->data['menu_page_title'];
			} else {
				return "My Custom Menu";
			}
		}


		/**
		 * Return menu name
		 *
		 * @return mixed|string
		 */
		private function get_menu_name() {
			if ( isset( $this->data['menu_name'] ) ) {
				return $this->data['menu_name'];
			} else {
				return "Menu Name";
			}
		}


		/**
		 * Return menu title
		 *
		 * @return mixed|string
		 */
		private function get_menu_title() {
			if ( isset( $this->data['menu_title'] ) ) {
				return $this->data['menu_title'];
			} else {
				return "Menu Title";
			}
		}


		/**
		 * Return menu page title
		 *
		 * @return mixed|string
		 */
		private function get_page_title() {
			if ( isset( $this->data['page_title'] ) ) {
				return $this->data['page_title'];
			} else {
				return "Page Title";
			}
		}


		/**
		 * Check whether to add in WordPress Admin menu or not
		 *
		 * @return bool
		 */
		private function add_in_menu() {
			if ( isset( $this->data['add_in_menu'] ) && $this->data['add_in_menu'] ) {
				return true;
			} else {
				return false;
			}
		}


		/**
		 * Return parent menu slug
		 *
		 * @return mixed|string
		 */
		function get_parent_slug() {
			if ( isset( $this->data['parent_slug'] ) && $this->data['parent_slug'] ) {
				return $this->data['parent_slug'];
			} else {
				return "";
			}
		}


		/**
		 * Return disabled notice
		 *
		 * @return mixed|string
		 */
		function get_disabled_notice() {
			if ( isset( $this->data['disabled_notice'] ) && $this->data['disabled_notice'] ) {
				return $this->data['disabled_notice'];
			} else {
				return "";
			}
		}


		/**
		 * Return Option Value for Given Option ID
		 *
		 * @param bool $option_id
		 * @param string|mixed $default
		 *
		 * @return bool|mixed|void
		 */
		function get_option_value( $option_id = false, $default = '' ) {

			if ( ! $option_id || empty( $option_id ) ) {
				returnfalse;
			}

			$option = array();
			foreach ( $this->get_options() as $__option ) {
				if ( ! isset( $__option['id'] ) || $__option['id'] != $option_id ) {
					continue;
				}
				$option = $__option;
			}

			// Check from DB
			$option_value = get_option( $option_id, '' );

			// Check from given value
			if ( empty( $option_value ) ) {
				$option_value = isset( $option['value'] ) ? $option['value'] : '';
			}

			// Check from default value
			if ( empty( $option_value ) ) {
				$option_value = isset( $option['default'] ) ? $option['default'] : '';
			}

			// Set given Default value
			if ( empty( $option_value ) ) {
				$option_value = $default;
			}

			return apply_filters( 'pb_settings_option_value', $option_value, $option_id, $option );
		}

		/**
		 * Return data using key from args
		 *
		 * @param string $key
		 * @param string $default
		 * @param array $args
		 *
		 * @return mixed|string
		 */
		private function get_data( $key = '', $default = '', $args = array() ) {

			$args    = empty( $args ) ? $this->data : $args;
			$default = empty( $default ) ? is_array( $default ) ? array() : '' : $default;
			$key     = empty( $key ) ? '' : $key;

			if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
				return $args[ $key ];
			}

			return $default;
		}
	}
}