<?php use_helper('Form') ?>
<?php use_helper('Object') ?>
<?php 
$type = $kp_setting->getType();
$name = isset($name) ? $name:'kp_setting['.$kp_setting['id'].']';

switch($type)
{
  case 'checkbox':
    echo input_hidden_tag($name, 0);
    echo checkbox_tag($name, 1, $kp_setting->getValue());
  break;

  case 'input':
    echo input_tag($name, $kp_setting->getValue(), 'size=55');
  break;

  case 'textarea':
    echo textarea_tag($name, $kp_setting->getValue());
  break;

  case 'yesno':
    echo 'Yes: '.radiobutton_tag($name, 1, $kp_setting->getValue());
    echo 'No: '.radiobutton_tag($name, 0, $kp_setting->getValue() ? false:true);
  break;

  case 'select':
    $options = _parse_attributes($kp_setting->getOptions());
    echo select_tag($name, options_for_select($options, $kp_setting->getValue(), 'include_blank=true'));
  break;

  case 'model':
    $config = _parse_attributes($kp_setting->getOptions());
    $method = $kp_setting->getOption('table_method');
    $method = $method ? $method : 'findAll';
    $options = Doctrine::getTable($kp_setting->getOption('model', true))->$method(); 
    echo select_tag($name, objects_for_select($options, 'getId', '__toString', $kp_setting->getValue()), 'include_blank=true');
  break;

  case 'wysiwyg':
    echo textarea_tag($name, $kp_setting->getvalue(), 'rich=true '.$kp_setting->getOptions());
  break;

  case 'upload':
    echo $kp_setting->getValue() ? link_to($kp_setting->getValue(), public_path('uploads/setting/'.$kp_setting->getValue())) .'<br />' : '';
    echo input_file_tag($name, $kp_setting->getValue(), $kp_setting->getOptions());
  break;

  default:
    echo input_tag($name, $kp_setting->getValue(), 'size=55');
  break;
}
