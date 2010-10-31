<?php
/**
 * Created on 31-okt-2010 18:07:18
 * 
 * auto-prune-posts-adminpage.php
 * @author	Ramon Fincken
 */
 
?>

<h3>Auto prune posts</h3>
<?php
if($action_taken)
{
	echo '<p class="updated">Settings updated!</p>';
}
?>
<br/>

<form id="form1" name="form1" method="post" action="" onsubmit="return confirm('Are you sure?')">
Delete posts in <?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'cat_id_add', 'hierarchical' => true)); ?> after

<input type="text" maxlength="6" name="period_duration_add" style="width: 45px;">
  <select name="period_add">
  <?php
  foreach($this->periods as $period)
  {
    echo '<option value="'.$period.'">'.$period.'(s)</option>';
  }
  ?>
  </select>
  
<br/>
<p class="updated">Note: if a duration has been set for the selected category, it will be over-written by the new settings!</p>
<input type="hidden" name="formaction" value="add" />
<input type="submit" name="submitbutton" value="Add settings" class="button-primary">
<input type="reset" name="submitbutton" value="Reset" class="button"></form>

<br/><br/>

<form id="form1" name="form1" method="post" action="" onsubmit="return confirm('Are you sure?')">
<table class="widefat">
   <thead>
   <tr>
      <th class="manage-column" style="width: 200px;">Option</th>
      <th class="manage-column" style="width: 300px;">Value/Setting</th>
      <th class="manage-column">Current Value/Setting</th>
   </tr>
   </thead>
   <tbody>

<?php 
      foreach($this->conf as $cat_id => $values)
      {
?>
   <tr class="iedit">
      <td valign="top">Category</td>
      <td valign="top">
      <?php
	  $cat = get_category($cat_id);
      echo $cat->name. ' (id= '.$cat_id.')';
      ?>
	  </td>
      <td>
      <?php
      wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'category_parent', 'orderby' => 'name', 'selected' => $cat_id, 'hierarchical' => true));
      ?>
      <br/>
      Note: you cannot use this dropdown, it is only to show which category is used.
      </td>
   </tr>
   <tr class="iedit">
      <td valign="top">Delete after </td>
      <td>
      
      <input type="text" maxlength="6" style="width: 45px;" name="period_duration[<?php echo $cat_id; ?>]" value="<?php echo $values['period']; ?>" />
      <select name="period[<?php echo $cat_id; ?>]">
  <?php
  foreach($this->periods as $period)
  {
    $select = '';
    if($values['period_duration'] == $period)
    {
    	$select = ' selected="selected"';
    }
    echo '<option value="'.$period.'"'.$select.'>'.$period.'(s)</option>';
  }
  ?>
  </select> 
      </td>
      <td>
      <input type="text" maxlength="6" style="width: 45px;" disabled="disabled" name="period_duration_disabled[<?php echo $cat_id; ?>]" value="<?php echo $values['period']; ?>" />
      <select disabled="disabled" name="period_disabled[<?php echo $cat_id; ?>]">
  <?php
  foreach($this->periods as $period)
  {
    $select = '';
    if($values['period_duration'] == $period)
    {
    	$select = ' selected="selected"';
    }
    echo '<option value="'.$period.'"'.$select.'>'.$period.'(s)</option>';
  }
  ?>
  </select> 
      </td>     
   </tr> 
   <tr class="iedit">
      <td valign="top">Action</td>
      <td valign="top">
		<select name="action[<?php echo $cat_id; ?>]">
		<option value="update" selected="selected">Update settings</option>
		<option value="delete">Delete settings</option>
		  </select>	  
	  </td>
      <td valign="top">&nbsp;</td>
   </tr>  
   
   <tr class="iedit">
      <td valign="top" style="height:50px;">&nbsp;</td>
      <td valign="top">&nbsp;</td>
      <td valign="top">&nbsp;</td>
   </tr>         
   </tbody>
   <?php
      }
      ?>
</table>
<input type="hidden" name="formaction" value="update" />
<input type="submit" name="submitbutton" value="Update" class="button-primary">
<input type="reset" name="submitbutton" value="Reset" class="button"></form>