<?php

/**
 * KpSetting filter form base class.
 *
 * @package kpPropelSettingsPlugin
 */
abstract class BaseKpSettingFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormFilterInput(),
      'widget_options'  => new sfWidgetFormFilterInput(),
      'value'           => new sfWidgetFormFilterInput(),
      'setting_group'   => new sfWidgetFormFilterInput(),
      'setting_default' => new sfWidgetFormFilterInput(),
      'slug'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorPass(array('required' => false)),
      'widget_options'  => new sfValidatorPass(array('required' => false)),
      'value'           => new sfValidatorPass(array('required' => false)),
      'setting_group'   => new sfValidatorPass(array('required' => false)),
      'setting_default' => new sfValidatorPass(array('required' => false)),
      'slug'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('kp_setting_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'KpSetting';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'type'            => 'Text',
      'widget_options'  => 'Text',
      'value'           => 'Text',
      'setting_group'   => 'Text',
      'setting_default' => 'Text',
      'slug'            => 'Text',
    );
  }
}
