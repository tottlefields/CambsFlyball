<?php
add_action ( 'wp_ajax_diary_click', 'do_diary_click' );
add_action ( 'wp_ajax_nopriv_diary_click', 'do_diary_click' );

function do_diary_click() {
  global $wpdb, $current_user; // this is how you get access to the database

  $status = $_POST['status'];
  $dog_id = $_POST['dog_id'];
  $event_id = $_POST['event_id'];
  $event_date = $_POST['event_date'];

  $result = $wpdb->update(
    'cft_events_diary',
    array('status' => $status), 
    array(
      'dog_id' => $dog_id,
      'event_id' => $event_id,
      'event_date' => $event_date
    )
  );

  if ($result == 1){
    echo json_encode(array( 'results' => "Updated" ));
  } else {
    $wpdb->insert('cft_events_diary', array(
      'dog_id' => $dog_id,
      'event_id' => $event_id,
      'event_date' => $event_date,
      'status' => $status
	  ));
    echo json_encode(array( 'results' => "Inserted" ));
  }

  wp_die ();
}

?>