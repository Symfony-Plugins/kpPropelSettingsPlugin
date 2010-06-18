<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo link_to($kp_setting->getName(), 'kp_setting_edit', $kp_setting) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_value">
  <?php if ($form[$kp_setting->getSlug()]->hasError()): ?>
    <?php echo $form[$kp_setting->getSlug()]->renderError() ?>
  <?php endif ?>
  <?php echo $form[$kp_setting->getSlug()] ?>
</td>