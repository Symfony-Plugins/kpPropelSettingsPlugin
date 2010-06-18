<?php

require 'plugins/kpPropelSettingsPlugin/lib/model/om/BaseKpSetting.php';

/**
 * Skeleton subclass for representing a row from the 'kp_setting' table.
 *
 * @package kpPropelSettingsPlugin
 * @author Krzysztof Pyrkosz (http://www.pyrkosz.pl/)
 * @author Brent Shaffer <bshaffer@centresource.com>
 */
class KpSetting extends BaseKpSetting {

	/**
	 * Initializes internal state of KpSetting object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}
	
  // convert the options text area to an array
  public function getOptionsArray()
  {
    return sfToolkit::stringToArray($this->getWidgetOptions());
  }
  
  public function findAllForList()
  {
    return $this->findAll();
  }
  
  public function findSettingsByGroup($query)
  {
    return $query->addOrderBy($query->getRootAlias().'.setting_group ASC');
  }
  public function getExistingGroupsArray()
  {
    $groups = $this->createQuery()
                   ->groupBy('setting_group')
                   ->execute();
                   
    $groupArray = array();
    
    foreach ($groups as $group) 
    {
      $groupArray[$group['setting_group']] = $group['setting_group'];
    }
    return array_filter($groupArray);
  }
  
  static public function restoreAllDefaults()
  {
    $con = Propel::getConnection();
    $c1 = new Criteria();
    $c2 = new Criteria();
    $c1->add(KpSettingPeer::ID, 0, Criteria::GREATER_THAN);
    $c2->add(KpSettingPeer::VALUE, "setting_default", Criteria::CUSTOM_EQUAL);
    return BasePeer::doUpdate($c1, $c2, $con);
  }
  
  static public function restoreDefault($id)
  {
    $con = Propel::getConnection();
    $c1 = new Criteria();
    $c2 = new Criteria();
    $c1->add(KpSettingPeer::ID, $id);
    $c2->add(KpSettingPeer::VALUE, "setting_default", Criteria::CUSTOM_EQUAL);
    return BasePeer::doUpdate($c1, $c2, $con);
  }

  /**
    * Called before node is saved - creates slug
    */
  public function preSave(PropelPDO $con = null)
  {   
    $this->setSlug(kpSettings::stripText($this->getName(), '_'));
    parent::doSave($con);
  }
} // KpSetting
