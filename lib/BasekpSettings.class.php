<?php

/**
 * BasekpSettings
 *
 * @package kpPropelSettingsPlugin
 * @author Krzysztof Pyrkosz (http://www.pyrkosz.pl/)
 * @author Brent Shaffer <bshaffer@centresource.com>
 */
class BasekpSettings
{
  static  $cache = false;
    
  static function getDefaultUploadPath()
  {
    return 'uploads/setting';
  }

  static function isAuthenticated($user = null)
  {
    if (!$user)
    {
      $user = sfContext::getInstance()->getUser();
    }
    
    $authMethod = sfConfig::get('app_kpPropelSettingsPlugin_authMethod');
    $authCredential = sfConfig::get('app_kpPropelSettingsPlugin_authCredential');

    $hasAccess = false;
    if ($authMethod)
    {
      $hasAccess = $user->$authMethod();
    }
    if (!$hasAccess && $authCredential)
    {
      $hasAccess = $user->hasCredential($authCredential);
    }

    return $hasAccess;
  }

  /**
   * get
   * Returns the string value of a particular setting.
   *
   * @param string $setting
   * @static
   * @access public
   * @return string
   */
  static function get($setting, $default = null)
  {
    // Pull from cached settings array
    $cache = self::buildCache('settings_array');
    
    if($cache->has('settings_array_'.$setting))
    {
      return unserialize($cache->get('settings_array_'.$setting));
    }

    //Look in app.ymls for setting
    return sfConfig::get('app_'.self::settingize($setting), $default);
  }
  
  static function buildCache($type)
  {
    $cache_handler = self::getCache();

    if(!$cache_handler->get($type.'_kp_cache_is_built', false))
    {
      foreach (KpSettingPeer::doSelect(new Criteria()) as $setting)
      {
        if($type == 'settings_array')
        {
          $cache_handler->set($type.'_'.$setting->getSlug(), serialize($setting->getValue()));
          $cache_handler->set($type.'_'.$setting->getName(), serialize($setting->getValue()));
        }
        elseif( $type == 'settings_object')
        {
          $cache_handler->set($type.'_'.$setting->getSlug(), serialize($setting->toArray()));
          $cache_handler->set($type.'_'.$setting->getName(), serialize($setting->toArray()));
        }
      }
      
      $cache_handler->set($type.'_kp_cache_is_built', true);
    }
    
    return $cache_handler;
  }

  /**
   * getSetting
   * pulls the kpSetting object for a given setting
   *
   * @param string $setting
   * @static
   * @access public
   * @return object kpSetting
   */
  static function getSetting($setting)
  {

    if(strlen(trim($setting)) == 0)
    {
      throw new sfException('[f6Settings::getSetting] invalid name');
    }
    
    $cache = self::buildCache('settings_object');
    
    if ($cache->has('settings_object_'.$setting))
    {
      $ret = new kpSetting();
      $ret->fromArray(unserialize($cache->get('settings_object_'.$setting, serialize(array()))));
      
      $value = unserialize($cache->get('settings_object_'.$setting));
      if(!is_array($value))
      {
        return null;
      }
      
      $ret->toArray($value);
      $ret->assignIdentifier($value['id']);
      
      return $ret;
    }

    return null;
  }

  static public function clearSettingsCache()
  {
    
    self::getCache()->clean(); 
  }

  static public function getCache()
  {
    if(!self::$cache)
    {
      $cache_settings = sfConfig::get('app_kpPropelSettingsPlugin_cache', array(
        'class'   => 'sfNoCache',
        'options' => array()
      ));
      
      $class    = $cache_settings['class'];
      $options  = $cache_settings['options'];
            
      self::$cache = new $class($options);
    }
    
    return self::$cache;
  }

  static public function settingize($anystring)
  {
    return str_replace('-', '_', self::stripText(trim($anystring)));
  }
  
  /**
    * Convert text to slug
    *
    * @author Guillermo Rauch (http://devthought.com)
    */
  public static function stripText($text, $separator = '-')
  {
    $bad = array(
    'À','à','Á','á','Â','â','Ã','ã','Ä','ä','Å','å','Ă','ă','Ą','ą',
    'Ć','ć','Č','č','Ç','ç',
    'Ď','ď','Đ','đ',
    'È','è','É','é','Ê','ê','Ë','ë','Ě','ě','Ę','ę',
    'Ğ','ğ',
    'Ì','ì','Í','í','Î','î','Ï','ï',
    'Ĺ','ĺ','Ľ','ľ','Ł','ł',
    'Ñ','ñ','Ň','ň','Ń','ń',
    'Ò','ò','Ó','ó','Ô','ô','Õ','õ','Ö','ö','Ø','ø','ő',
    'Ř','ř','Ŕ','ŕ',
    'Š','š','Ş','ş','Ś','ś',
    'Ť','ť','Ť','ť','Ţ','ţ',
    'Ù','ù','Ú','ú','Û','û','Ü','ü','Ů','ů',
    'Ÿ','ÿ','ý','Ý',
    'Ž','ž','Ź','ź','Ż','ż',
    'Þ','þ','Ð','ð','ß','Œ','œ','Æ','æ','µ',
    '”','“','‘','’',"'","\n","\r",'_');

    $good = array(
    'A','a','A','a','A','a','A','a','Ae','ae','A','a','A','a','A','a',
    'C','c','C','c','C','c',
    'D','d','D','d',
    'E','e','E','e','E','e','E','e','E','e','E','e',
    'G','g',
    'I','i','I','i','I','i','I','i',
    'L','l','L','l','L','l',
    'N','n','N','n','N','n',
    'O','o','O','o','O','o','O','o','Oe','oe','O','o','o',
    'R','r','R','r',
    'S','s','S','s','S','s',
    'T','t','T','t','T','t',
    'U','u','U','u','U','u','Ue','ue','U','u',
    'Y','y','Y','y',
    'Z','z','Z','z','Z','z',
    'TH','th','DH','dh','ss','OE','oe','AE','ae','u',
    '','','','','','','','-');

    // convert special characters
    $text = str_replace($bad, $good, $text);
    
    // convert special characters
    $text = utf8_decode($text);
    $text = htmlentities($text);
    $text = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $text);
    $text = html_entity_decode($text);
    
    $text = strtolower($text);

    // strip all non word chars
    $text = preg_replace('/\W/', ' ', $text);

    // replace all white space sections with a separator
    $text = preg_replace('/\ +/', $separator, $text);

    // trim separators
    $text = trim($text, $separator);
    //$text = preg_replace('/\-$/', '', $text);
    //$text = preg_replace('/^\-/', '', $text);
        
    return $text;
  }
}
