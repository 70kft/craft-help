<?php

namespace Craft;

class HelpPlugin extends BasePlugin {
  function getName () {
    return Craft::t('Help');
  }

  function getVersion () {
    return '1.0';
  }

  function getDeveloper () {
    return '70kft';
  }

  function getDeveloperUrl () {
    return 'http://70kft.com';
  }

  protected function defineSettings ()
  {
      return array(
          'helpTemplatePath' => array(AttributeType::String, 'default' => '_help'),
      );
  }

  public function getSettingsHtml() {
    return craft()->templates->render(
      'help/settings',
      array('settings' => $this->getSettings() )
    );
  }

  public function hasCpSection() {
    return true;
  }

  public function registerCpRoutes() {
    return array(
      'help/(?P<helpPath>[a-zA-Z0-9\-\_\/]+)' => 'help/index'
    );
  }

  public function addTwigExtension()
  {
      Craft::import('plugins.help.twigextensions.HelpTwigExtension');

      return new HelpTwigExtension();
  }
  
  public function addAdminBarLinks() {
    // Register an admin bar link for @wbrowar's adminbar plugin
    // https://github.com/wbrowar/craft-admin-bar
    return array(
      array(
        'title' => 'Help',
        'url' => 'help',
        'type' => 'cpUrl',
      ),
    );
  }
}

?>
