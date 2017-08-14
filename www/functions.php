<?php

function mytheme_enqueue_scripts() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("//code.jquery.com/jquery-2.2.4.min.js"), false, '2.2.4', true);
	wp_enqueue_script('jquery');
	
	//Bootstrap JS
	wp_register_script('bootstrap-js', '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', '', '3.3.7', true);
	wp_enqueue_script('bootstrap-js');
	
	//BS DatePicker
	wp_register_script('datepicker-js', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', array('jquery', 'bootstrap-js'), '1.6.4', true);
	wp_enqueue_script('datepicker-js');
	
	//PDF Make
	wp_register_script('pdfmake-js', get_template_directory_uri().'/assets/js/pdfmake.min.js', false, '0.1.31', true);
	wp_register_script('pdfmake-fonts-js', get_template_directory_uri().'/assets/js/vfs_fonts.js', array('pdfmake-js'), '0.1.31', true);
	wp_enqueue_script('pdfmake-js');
	wp_enqueue_script('pdfmake-fonts-js');
	
	//DataTables
	wp_register_script('datatables-js', '//cdn.datatables.net/v/bs/dt-1.10.15/b-1.3.1/b-html5-1.3.1/b-print-1.3.1/r-2.1.1/se-1.2.2/datatables.min.js', array('jquery', 'bootstrap-js', 'pdfmake-js'), '1.10.15', true);
	wp_enqueue_script('datatables-js');
	
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');
