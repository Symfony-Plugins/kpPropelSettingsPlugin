<li class='sf_admin_action_save_settings'>
  <input type="submit" name="submit" value="<?php echo __('Save Settings') ?>">
</li>
<?php if (kpSettings::isAuthenticated($sf_user)): ?>
  <?php echo $helper->linkToNew(array(  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
<?php endif ?>
<li class='sf_admin_action_restore_all_defaults'>
  <?php echo link_to(__('Restore All Defaults'), '@kp_setting_restore_all_defaults', array('confirm' => __('Are you sure?'))) ?>
</li>

