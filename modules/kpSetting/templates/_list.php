<div class="sf_admin_list">
  <?php if (!$pager->getNbResults()): ?>
    <p><?php echo __('No result', array(), 'sf_admin') ?></p>
  <?php else: ?>
    <table cellspacing="0">
      <thead>
        <tr>
          <?php include_partial('kpSetting/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions">
          <?php if (kpSettings::isAuthenticated($sf_user)): ?>
            <?php echo __('Actions', array(), 'sf_admin') ?>
          <?php endif ?>
          </th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="3">
            <?php if ($pager->haveToPaginate()): ?>
              <?php include_partial('kpSetting/pagination', array('pager' => $pager)) ?>
            <?php endif; ?>

            <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'sf_admin') ?>
            <?php if ($pager->haveToPaginate()): ?>
              <?php echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'sf_admin') ?>
            <?php endif; ?>
          </th>
        </tr>
      </tfoot>
      <tbody>
        <?php $group = '' ?>
        <?php foreach ($pager->getResults() as $i => $kp_setting): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <?php if ($kp_setting->getSettingGroup() != $group): ?>
            <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
              <th colspan="3">
                <?php echo $kp_setting->getSettingGroup() ?>
              </th>
            </tr>
          <?php endif ?>
          <?php $group = $kp_setting->getSettingGroup(); ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
            <?php include_partial('kpSetting/list_td_tabular', array('kp_setting' => $kp_setting, 'form' => $form)) ?>
            <?php include_partial('kpSetting/list_td_actions', array('kp_setting' => $kp_setting, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<script type="text/javascript">
/* <![CDATA[ */
function checkAll()
{
  var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
}
/* ]]> */
</script>
