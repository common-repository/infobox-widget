<?php
/**
* Plugin Name: Infobox Widget
* Plugin URI:  http://infobox.tilda.ws/
* Description: Create a widget with the following properties: title, company name, phone number, and email.
* Version:     1.0
* Author:      Aleksandr Husliakov
* Author URI:  https://www.facebook.com/Darmograi
* Text Domain: wporg
* Domain Path: /languages
* License:     GPL2
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 
Infobox Widget is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Infobox Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Infobox Widget. If not, see http://www.gnu.org/licenses/gpl-2.0.txt .
*/

namespace infobox\widget;

// stop unwanted visitors from calling directly
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Go away!' );
}

// register the widget
class Infobox_Widget extends \WP_Widget {
	function __construct() {
	    $widget_options = array(
	        'classname' => 'Infobox-Widget',
	        'description' => 'Just adds a simple widget information box.'
	    );
	    parent::__construct( 'standard_Widget', 'My Infobox Widget', $widget_options );
	    add_action( 'widgets_init', function () {
	        register_widget( __NAMESPACE__.'\Infobox_Widget' );
	    });
		add_action( 'wp_enqueue_scripts', array($this, 
			'widget_enqueue_styles' ));
	}
	
	// creating a form with values
	function form( $instance ) { 
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Widget title';
		$company = ! empty( $instance['company'] ) ? $instance['company'] : '';
		$tel = ! empty( $instance['tel'] ) ? $instance['tel'] : '';
		$email = ! empty( $instance['email'] ) ? $instance['email'] : 'Email address';
	?>
	
	<!-- create your custom fields for data entry -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
		<input class="widefat" type ="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'company' ); ?>">Company name:</label>
		<input class="widefat" type ="text" id="<?php echo $this->get_field_id( 'company' ); ?>" name="<?php echo $this->get_field_name( 'company' ); ?>" value="<?php echo esc_attr( $company ); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'tel' ); ?>">Telephone number:</label>
		<input class="widefat" rows="10" type="text" id="<?php echo $this->get_field_id( 'tel' ); ?>" name="<?php echo $this->get_field_name( 'tel' ); ?>" value="<?php echo esc_attr( $tel ); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'email' ); ?>">E-mail address:</label>
		<input class="widefat" rows="10" type="text" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo esc_attr( $email ); ?>" />
	</p>

<?php }
	
	// update custom fields
    function update( $new_instance, $old_instance ) { 
            $instance = $old_instance;
            $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
            $instance[ 'company' ] = strip_tags( $new_instance[ 'company' ] );
            $instance[ 'tel' ] = strip_tags( $new_instance[ 'tel' ] );
            $instance[ 'email' ] = strip_tags( $new_instance[ 'email' ] );
            return $instance;
    }

	/* 
	 * This is how your widget will be displayed 
	 *
	 * The 4 arugments are; before_title, after_title, before_widget, and after_widget or else under theme control!
	 * In this version of the widget, WE ARE NOT USING THESE ARGUEMENTS, but taking the default versions from the theme
	 */
	public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );

    function widget( $args, $instance ) {
		wp_enqueue_style( 'infobox-widget' );
        echo $args ['before_widget'];
        $company = apply_filters( 'widget_company', $instance[ 'company' ] );
        $tel = $instance['tel'];
  		$email = $instance['email'];
		
      	/* TITLE */
		if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        } 
    ?>
	
	<!-- output the phone number and the emails -->
        <div class="cta">
                <?php if ( ! empty( $company ) ) {
                        echo  '<p>'.$company.'</p>' ; 
                }; ?>
        <?php echo 'Call us on ' . $tel . ' <br> Email: <a href="' . $email . '">' . $email . '</a>'; ?>
    </div>

        <?php echo $args['after_widget'];
    }  
	
	// Adding a widget stylesheet
	function widget_enqueue_styles() {
	    wp_register_style( 'infobox-widget', plugins_url( 'css/widget.css', __FILE__ ) );	    
	}

}

$my_widget = new infobox_Widget();

