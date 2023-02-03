<?php
/*
Plugin Name: My DB Data
Description: Display data from a database in a WordPress page
Version: 1.0
Author: Nabeel Javed
*/

function display_my_db_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . "my_table_name";
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    echo '<table>';
    echo '<tr>';
    echo '<th>Column 1</th>';
    echo '<th>Column 2</th>';
    echo '<th>Column 3</th>';
    echo '</tr>';
    foreach ($results as $result) {
      echo '<tr>';
      echo '<td>' . $result->column1 . '</td>';
      echo '<td>' . $result->column2 . '</td>';
      echo '<td>' . $result->column3 . '</td>';
      echo '</tr>';
    }
    echo '</table>';
  }

  display_my_db_data()
  