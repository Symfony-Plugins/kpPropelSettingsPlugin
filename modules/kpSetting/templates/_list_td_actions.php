<td>
  <ul class="sf_admin_td_actions">
    <?php if (kpSettings::isAuthenticated($sf_user)): ?>
      <?php echo $helper->linkToEdit($kp_setting, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
      <?php echo $helper->linkToDelete($kp_setting, array(  'params' =>   array(  ),  'confirm' => __('Are you sure?'),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
    <?php endif ?>
    <li class="sf_admin_action_restore_default">
      <?php echo link_to(__('Restore Default', array(), 'messages'), 'kpSetting/ListRestoreDefault?id='.$kp_setting->getId(), array('confirm' => __('Are you sure?'))) ?>
    </li>    
  </ul>
</td>
