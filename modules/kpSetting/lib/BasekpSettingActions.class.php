<?php

/**
 * BasekpSettingsActions 
 * 
 * @package kpPropelSettingsPlugin
 * @author  Krzysztof Pyrkosz (http://www.pyrkosz.pl/)
 * @author  Brent Shaffer <bshaffer@centresource.com>
 */
class BasekpSettingActions extends AutokpSettingActions
{ 
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SettingsListForm();
    return parent::executeIndex($request);
  }
  
  public function executeListSaveSettings(sfWebRequest $request)
  {
    self::executeIndex($request);
    if($settings = $request->getParameter('kp_setting'))
    {
      $this->form = new SettingsListForm();
      $this->form->bind($settings, $request->getfiles('kp_setting'));
      if ($this->form->isValid()) 
      {
        foreach($this->form->getValues() as $slug => $value)
        {
          $c = new Criteria();
          $c->add(KpSettingPeer::SLUG, $slug);
          $setting = KpSettingPeer::doSelectOne($c);
          if ($setting) 
          {
            $setting->setValue($value);
            $setting->save();
          }
        }
        
        if($files = $request->getFiles('kp_setting'))
        {
          $this->processUpload($settings, $files);
        }
        
        // Update form with new values
        $this->form = new SettingsListForm();

        $this->getUser()->setFlash('notice', sfContext::getInstance()->getI18N
()->__('Your settings have been saved.'));
      }
      else
      {
        $this->getUser()->setFlash('error', sfContext::getInstance()->getI18N
()->__('Your form contains some errors'));
      }
    }
    $this->setTemplate('index');
  }
  
  public function executeListRestoreDefault(sfWebRequest $request)
  {
    KpSetting::restoreDefault($request->getParameter('id'));

    $this->redirect($request->getReferer());
  }
  
  public function executeRestoreAllDefaults(sfWebRequest $request)
  {
    KpSetting::restoreAllDefaults();

    $this->redirect($request->getReferer());
  }
  
  public function processUpload($settings, $files)
  {
    $default_path = kpSettings::getDefaultUploadPath();
    
    foreach ($files as $slug => $file) 
    {
      if ($file['name']) 
      {
        $setting = Doctrine::getTable('kpSetting')->findOneBySlug($slug);
        
        $target_path = $setting->getOption('upload_path');
        
        $target_path = $target_path ? $target_path : $default_path;
        
        //If target path does not exist, attempt to create it
        if(!file_exists($target_path))
        {
          $target_path = mkdir($target_path) ? $target_path : 'uploads';
        }
        
        $target_path = $target_path . DIRECTORY_SEPARATOR . basename( $file['name']); 
        
        if(!move_uploaded_file($file['tmp_name'], $target_path)) 
        {
          $this->getUser()->setFlash('error', 'There was a problem uploading your file!');
        }
        else
        {  
          $setting->setValue(basename($file['name']));
          $setting->save();
        }
      }
      elseif (isset($settings[$slug.'_delete'])) 
      {
        $setting = Doctrine::getTable('kpSetting')->findOneBySlug($slug);
        unlink($setting->getUploadPath().'/'.$setting->getValue());
        $setting->setValue('');
        $setting->save();
      }
    }
  }
}
