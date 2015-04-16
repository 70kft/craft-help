<?php

namespace Craft;

class HelpPlugin extends BasePlugin {
  function init () {

    // Register an admin bar link for @wbrowar's adminbar plugin
    // https://github.com/wbrowar/craft-admin-bar
    $this->addAdminBarLinks();
  }

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

  private function addAdminBarLinks() {
    // First, be sure the adminbar plugin exists and is installed AND enabled
    $adminbarPlugin = craft()->plugins->getPlugin('adminbar');
    if($adminbarPlugin === null) {
      return;
    }

    if(!$adminbarPlugin->isInstalled || !$adminbarPlugin->isEnabled) {
      return;
    }

    Craft::import('plugins.adminbar.events.AdminbarEvent', true);
    craft()->on('adminbar.onFindPluginLinks', function(AdminbarEvent $event) {
      // call this for each link you want to add
      craft()->adminbar->addPluginLink(array(
        'title' => 'Help',
        'url' => 'help',
        'type' => 'cpUrl',
      ));

    });
  }
}

?>
