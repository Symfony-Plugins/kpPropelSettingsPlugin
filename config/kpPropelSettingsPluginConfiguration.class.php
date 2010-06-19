<?php

/**
 * kpPropelSettingsPlugin configuration.
 * 
 * @package     kpPropelSettingsPlugin
 * @author      Krzysztof Pyrkosz
 */
class kpPropelSettingsPluginConfiguration extends sfPluginConfiguration
{
  //const VERSION = '0.0.1-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->dispatcher->connect('context.load_factories', array('kpSettings', 'populateSettings'));
  }
}
