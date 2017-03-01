<?php
/**
 *
 *Plugin Name: Adminbar_widget
 *
 * Adds admin_bar_Widget widget.
 *
 * Author: Arne Ziegert
 */
class AB_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {
		parent::__construct(
			'adminbar_widget', // Base ID
			esc_html__( 'Adminbar Widget', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'A Admin_bar Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
    ?>
    <style>
		#wpadminbar .ab-sub-wrapper, #wpadminbar ul, #wpadminbar ul li {
    background: 0 0;
    clear: none;
    margin: 0;
    padding: 0;
    position: relative;
    text-indent: 0;
    float: left;
    display: block;
    width: 100%;
		}


		#wp-admin-bar-my-account > a:first-child {
			display: none;
		}

		#wp-admin-bar-wp-logo {
			display: none!important;
		}

		#wp-admin-bar-customize {
			display: none!important;
		}

		#wp-admin-bar-user-info img.avatar {
    height: 80px;
    width: 80px;
		}

		#wp-admin-bar-user-info .avatar {
    position: absolute;
    left: -78px;
    top: 10px;
    width: 64px;
    height: 64px;
		}

		#adminbar_widget .ab-top-secondary {
    display: block;
    background-color: #777;
    margin-bottom: 30px;
		}

		#ab-pending-notifications::before {
			content: "Notifications:   ";
		}

		html { margin-top: 0px !important; }
    #adminbar_widget #wpadminbar {
			display: table;
      position: relative;
			min-width:200px;
			min-height:300px;
			z-index: 8;
    	padding: 5px;
    	background-color: #777;
    	box-shadow: inset 0 0 6px 0px #000;
    }
		<?php if (!current_user_can('delete_published_posts')) { ?>
		#adminbar_widget .ab-top-menu {
			display:none;
		}
		<?php } ?>
		#adminbar_widget .ab-top-secondary {
			display:block;
		}
    </style>
    <div id="adminbar_widget">
            <?php admin_menu_widget();
             ?>
    </div> <?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class admin_bar_widget


function admin_menu_widget() {
	if ( is_user_logged_in() ) {
		wp_admin_bar_render();
		?>
		<script>
		var d = document.getElementById("wp-admin-bar-my-account");
		d.classList.remove("menupop");
		</script>
		<?php
	}
	else {
		?>
		<style>#wpadminbar{display:none} header{top:0px!important;}</style><?php
		global $wpdb;
	if(sanitize_text_field( $_GET['login'] ) != ''){
	 $login_fail_msg=sanitize_text_field( $_GET['login'] );
	}
	?>
	<div class="alar-login-form">
	<?php if($login_fail_msg=='failed'){?>
	<div class="error"  align="center"><?php _e('Username or password is incorrect','');?></div>
	<?php }?>
		<div class="ab_widget_login">
		</div>
		<form method="post" action="<?php echo get_option('home');?>/wp-login.php" id="loginform" name="loginform" >
			<div class="ftxt">
			<label style="width:100%"><?php _e('Login :','');?>
			 <input type="text" tabindex="10" size="20" value="" class="input" id="user_login" required name="log" style="float:right" /></label>
			</div>
			<div class="ftxt">
			<label style="width:100%"><?php _e('Password :','');?>
				<input type="password" tabindex="20" size="20" value="" class="input" id="user_pass" required name="pwd" style="float:right"/></label>
			</div>
			<div class="fbtn">
			<input style="width:100%" type="submit" tabindex="100" value="Login" class="button" id="wp-submit" name="wp-submit" />
			<input type="hidden" value="<?php echo get_option('home');?>" name="redirect_to">
			</div>
		</form>
	</div>
	<?php

	}
}


function register_ab_widget() {
    register_widget( 'AB_Widget' );
}
add_action( 'widgets_init', 'register_ab_widget' );


function go_home(){
  wp_redirect( home_url() );
  exit();
}
add_action('wp_logout','go_home');
