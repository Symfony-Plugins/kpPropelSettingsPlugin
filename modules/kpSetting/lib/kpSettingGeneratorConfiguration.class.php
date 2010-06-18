<?php

/**
 * news module helper.
 *
 * @package kpPropelSettingsPlugin
 * @author  Krzysztof Pyrkosz (http://www.pyrkosz.pl/)
 * @author  Brent Shaffer <bshaffer@centresource.com>
 */
class kpSettingGeneratorConfiguration extends BasekpSettingGeneratorConfiguration
{
  public function getTableMethod()
  {
    return 'findSettingsByGroup';
  }
}
