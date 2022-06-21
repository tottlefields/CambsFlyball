<?php
// Creating the widget 
class cft_widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
        
        // Base ID of your widget
        'cft_widget', 
        
        // Widget name will appear in UI
        __('Flyball Seedings Widget', 'cft_widget_domain'), 
        
        // Widget description
        array( 'description' => __( 'A widget to list current seed times for a club.', 'cft_widget_domain' ), ) 
        );
    }
    
    // Creating widget front-end    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];


        // This is where you run the code and display the output
        global $wpdb;
        $sql = "select seed_position, seed_time, race_date, is_mb, replace(lower(team_name), ' ', '-') as slug, replace(team_name, 'Cambridgeshire ', '') as team
        from seed_list where team_name like 'Cambridgeshire%' order by is_mb asc, seed_position";
        $results = $wpdb->get_results($sql);

        if (count($results) > 0){
            echo '<figure class="wp-block-table is-style-stripes"><table><tbody>';
            //<tr><td class="has-text-align-center" data-align="center">9th</td>
            //<td><a href="/teams/cambridgeshire-canines/" data-type="URL">Canines</a></td>
            //<td class="has-text-align-center" data-align="center">15.78</td></tr>
            //<tr><td class="has-text-align-center" data-align="center">25th</td><td><a href="/teams/cambridgeshire-catapults/">Catapults</a></td><td class="has-text-align-center" data-align="center">16.92</td></tr><tr><td class="has-text-align-center" data-align="center">46th</td><td><a href="/teams/cambridgeshire-cannons/">Cannons</a></td><td class="has-text-align-center" data-align="center">17.86</td></tr><tr><td class="has-text-align-center" data-align="center">71st</td><td><a href="/teams/cambridgeshire-crossbows/">Crossbows</a></td><td class="has-text-align-center" data-align="center">19.67</td></tr><tr><td class="has-text-align-center" data-align="center">79th</td><td><a href="/teams/cambridgeshire-crusaders/">Crusaders</a></td><td class="has-text-align-center" data-align="center">20.27</td></tr><tr><td class="has-text-align-center" data-align="center">91st</td><td><a href="/teams/cambridgeshire-chargers/">Chargers</a></td><td class="has-text-align-center" data-align="center">21.10</td></tr>
            foreach ($results as $row){
                echo '<tr>
                <td class="has-text-align-center" data-align="center">'.getOrdinal($row->seed_postition).'</td>
                <td><a href="/teams/'.$row->slug.'/" data-type="URL">'.$row->team.'</a></td>
                <td class="has-text-align-center" data-align="center">'.number_format($row->seed_time, 2).'</td></tr>';
            }
            echo '</tbody></table></figure>';    
        }
        
        //echo __( 'Hello, World!', 'cft_widget_domain' );
        echo $args['after_widget'];
    }
            
    // Widget Backend 
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'Current Seedings', 'cft_widget_domain' );
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php 
    }
        
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
    
    // Class cft_widget ends here
} 
 
 
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'cft_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
