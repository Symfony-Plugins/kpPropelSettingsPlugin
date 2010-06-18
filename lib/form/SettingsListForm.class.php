<?php

/**
 * SettingListForm
 *
 * @package kpPropelSettingsPlugin
 * @author  Krzysztof Pyrkosz (http://www.pyrkosz.pl/)
 * @author  Brent Shaffer <bshaffer@centresource.com>
 */
class SettingsListForm extends sfForm
{
  public function configure()
  {
    foreach (KpSettingPeer::doSelect(new Criteria()) as $setting) 
    {
      $form = new kpSettingForm($setting);
      $this->widgetSchema[$setting->getSlug()] = $form->getSettingWidget();
      $this->widgetSchema[$setting->getSlug()]->setDefault($setting->getValue());
      $this->validatorSchema[$setting->getSlug()] = $form->getSettingValidator();      
    }
    
    $this->widgetSchema->setNameFormat('kp_setting[%s]');
  }
}