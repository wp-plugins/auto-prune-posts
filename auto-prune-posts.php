<?php
/*
 Plugin Name: Auto Prune Posts
 Plugin URI: http://www.mijnpress.nl
 Description: Auto deletes (prune) posts after a certain amount of time. On a per category basis.
 Version: 1.0
 Author: Ramon Fincken
 Author URI: http://mijnpress.nl
 Created on 31-okt-2010 17:33:40
 */

// General config
$plugin_autopruneposts_conf = get_option('plugin_autopruneposts_conf');
if ($plugin_autopruneposts_conf === false) {
   add_option('plugin_autopruneposts_conf', array (), NULL, 'yes');
}

/**
 * Init for admin menu
 */
if (is_admin()) {
   add_action('admin_menu', 'plugin_autopruneposts_addmenu');
}

/**
 * Shows admin menu
 */
function plugin_autopruneposts_addmenu() {
   add_submenu_page("plugins.php", "Auto prune posts", "Auto prune posts", 10, 'plugin_autopruneposts', 'plugin_autopruneposts_initpage');
}

/**
 * Shows admin page and handles POST requests
 */
function plugin_autopruneposts_initpage() {
   $plugin_autopruneposts_initpage = new auto_prune_posts();
   $plugin_autopruneposts_initpage->configpage();
}

function my_activation() {
   $plugin_autopruneposts_initpage = new auto_prune_posts();
   $plugin_autopruneposts_initpage->prune();
}
add_action('wp', 'my_activation');

/**
 * @author  Ramon Fincken
 */
class auto_prune_posts {

   /**
    * Constructor
    */
   function auto_prune_posts() {
      $this->conf = get_option('plugin_autopruneposts_conf');
      $this->periods = array (
         'hour',
         'day',
         'week',
         'month',
         'year'
      );
   }

   /**
    * Shows admin page and handles POST requests
    */
   function configpage() {
      $action_taken = false;
      if (isset ($_POST['formaction'])) {
         switch ($_POST['formaction']) {
            case 'add' :
               if (isset ($_POST['period_duration_add']) && !empty ($_POST['period_duration_add']) && intval($_POST['period_duration_add']) > 0) {
                  if (in_array($_POST['period_add'], $this->periods) && intval($_POST['cat_id_add']) > 0) {
                     $period_php = intval(trim($_POST['period_duration_add'])) . ' ' . $_POST['period_add'];
                     $period = intval(trim($_POST['period_duration_add']));
                     $period_duration = $_POST['period_add'];
                     $this->conf[intval($_POST['cat_id_add'])] = array (
                        'period_php' => $period_php,
                        'period' => $period,
                        'period_duration' => $period_duration
                     );
                     update_option('plugin_autopruneposts_conf', $this->conf);
                     $action_taken = true;
                  }
               }
               break;
            case 'update' :
               // Walk
               foreach ($this->conf as $cat_id => $values) {
                  $action = $_POST['action'][$cat_id];
                  if ($action == 'delete') {
                     unset ($this->conf[$cat_id]);
                     $action_taken = true;
                  } else {
                     // Update falltrough
                     if (isset ($_POST['period_duration'][$cat_id]) && !empty ($_POST['period_duration'][$cat_id]) && intval($_POST['period_duration'][$cat_id]) > 0) {
                        if (in_array($_POST['period'][$cat_id], $this->periods)) {
                           $period_php = intval(trim($_POST['period_duration'][$cat_id])) . ' ' . $_POST['period'][$cat_id];
                           $period = intval(trim($_POST['period_duration'][$cat_id]));
                           $period_duration = $_POST['period'][$cat_id];
                           $this->conf[$cat_id] = array (
                              'period_php' => $period_php,
                              'period' => $period,
                              'period_duration' => $period_duration
                           );
                           $action_taken = true;
                        }
                     }
                  }
               }

               // Now perform updates
               update_option('plugin_autopruneposts_conf', $this->conf);
               break;
         }
      }

      // Show form
      include ('auto-prune-posts-adminpage.php');
   }

   /**
    * Uses transient instead of cronjob, will run on wp call in frontend.
    */
   function prune() {
      $lastrun = get_transient('auto-prune-posts-lastrun');
      if (false === $lastrun) {
         $force_delete = false; // true||false > true=really remove, false=sent to trash // TODO: make this an admin option

         // Walk
         foreach ($this->conf as $cat_id => $values) {
            $period_php = $values['period_php']; // Will be in format so strtotime can handle this [int][space][string] example: "4 day" or "5 month"

            // Get all posts for this category
            $myposts = get_posts('category=' . $cat_id);
            foreach ($myposts AS $post) {
               $post_date_plus_visibleperiod = strtotime($post->post_date . " +" . $period_php);
               $now = strtotime("now");
               if ($post_date_plus_visibleperiod < $now) {
                  // GOGOGO !
                  $this->delete_post_and_attachments($post->ID,$force_delete);

                  // TODO: Mail admin?
               }
            }
         }

         set_transient('auto-prune-posts-lastrun', 'lastrun: '.time(), 60*60); // 60*30 = 30 minutes
      }
   }

   /**
    * Actually deletes post and its attachments
    */
   private function delete_post_and_attachments($post_id, $force_delete) {
      $photos = get_children(array (
         'post_parent' => $post_id,
         'post_status' => 'inherit',
         'post_type' => 'attachment'
      ));
      if ($photos) {
         foreach ($photos as $photo) {
            // Deletes this attachment
            wp_delete_attachment($photo->ID, $force_delete);
         }
      }

      // Now delete post
      wp_delete_post($post_id, $force_delete);
   }

}
?>
