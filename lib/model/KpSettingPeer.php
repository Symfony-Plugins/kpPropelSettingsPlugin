<?php

require 'plugins/kpPropelSettingsPlugin/lib/model/om/BaseKpSettingPeer.php';

/**
 * Skeleton subclass for performing query and update operations on the 'kp_setting' table.
 *
 * @package kpPropelSettingsPlugin
 * @author Krzysztof Pyrkosz (http://www.pyrkosz.pl/)
 * @author Brent Shaffer <bshaffer@centresource.com>
 */
class KpSettingPeer extends BaseKpSettingPeer {
  // Get a value from the options array
  public function getOption($name, $required = false)
  {
    $config = $this->getOptionsArray();
    
    if ($required && !isset($config[$name])) 
    {
      throw new sfException(sprintf('Missing required option "%s" in setting %s', $name, $this['name']));
    }
    
    return isset($config[$name]) ? $config[$name] : false;
  }
  
  // path to uploaded files
  public function getUploadPath()
  {
    if ($this['type'] != 'upload') 
    {
      throw new sfException(sprintf('Cannot get Upload Path for setting of type "%s"', $this['type']));
    }
    
    $default_path = kpSettings::getDefaultUploadPath();
        
    $target_path = $this->getOption('upload_path');
    
    return $target_path ? $target_path : $default_path;
  }
  
  // shortcut for getting the setting group (field name 'group' is reserved)
  public function getGroup()
  {
    return $this['setting_group'];
  }
  
  // remove cache when an item is updated
  public function postSave(PropelPDO $con = null)
  {
    kpSettings::clearSettingsCache();
  }
  
  public function postDelete(PropelPDO $con = null)
  {
    kpSettings::clearSettingsCache();
  }
  
  // Ensures default value is set when object is saved without a value
  public function preInsert(PropelPDO $con = null)
  {
    if ($this['setting_default'] && !$this['value']) 
    {
      $this['value'] = $this['setting_default'];
    }
  }
  
  public function getValue()
  {
    $value = $this->_get('value');
    switch ($this['type']) 
    {
      case 'checkbox':
        if ($value == '') 
        {
          // Cast as boolean
          return false;
        }
        break;
      case 'yesno':
        return (bool) $value;
    }
    
    return $value;
  }
} // KpSettingPeer
