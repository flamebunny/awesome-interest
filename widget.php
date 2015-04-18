<?php

/*	
 	
	Copyright 2014  pixeltradr.com  (email : dbudhia@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/


/**
 * widget_init hook
 * 
 */
add_action('widgets_init', 'load_awesome_interest_widget');


//------------------------------------------------------------------------


/**
 * register widget 
 * 
 */
function load_awesome_interest_widget(){
	register_widget('Awesome_Interest_Widget');
}


//------------------------------------------------------------------------


/**
 * widget class
 *
 */
class Awesome_Interest_Widget extends WP_Widget {	
	/**
	 * construct
	 * 
	 */
	function Awesome_Interest_Widget() {
		// Widget settings. 
		$widget_ops = array( 'classname' => 'Awesome_Interest', 'description' => __('A widget that allows visitors to register their interest in your wedding and reception', 'awesome_interest') );

		// Widget control settings.
		$control_ops = array( 'id_base' => 'awesome_interest_widget' );

		// Create the widget.
		$this->WP_Widget( 'awesome_interest_widget', __('Awesome Interest - Register Interest Widget', 'awessome_interest'), $widget_ops, $control_ops );
	}
}


/* End of file widget.php */