<?php

/**
 * KpSetting form base class.
 *
 * @method KpSetting getObject() Returns the current form's model object
 *
 * @package kpPropelSettingsPlugin
 */
abstract class BaseKpSettingForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'widget_options'  => new sfWidgetFormTextarea(),
      'value'           => new sfWidgetFormTextarea(),
      'setting_group'   => new sfWidgetFormInputText(),
      'setting_default' => new sfWidgetFormTextarea(),
      'slug'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'widget_options'  => new sfValidatorString(array('required' => false)),
      'value'           => new sfValidatorString(array('required' => false)),
      'setting_group'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'setting_default' => new sfValidatorString(array('required' => false)),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'KpSetting', 'column' => array('name'))),
        new sfValidatorPropelUnique(array('model' => 'KpSetting', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('kp_setting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'KpSetting';
  }


}
