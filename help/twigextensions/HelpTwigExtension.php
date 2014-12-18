<?php

namespace Craft;

use Twig_Extension;  
use Twig_Function_Method;
use Twig_Filter_Method;

class HelpTwigExtension extends Twig_Extension  
{
  public function getName()
  {
      return 'Help Twig Extension';
  }

  public function getFunctions()
  {
    return array(
      'getSidebar' => new Twig_Function_Method($this, 'getSidebar'),
      'getHelpDocument' => new Twig_Function_Method($this, 'getHelpDocument'),
      'getHelpTemplatePath' => new Twig_Function_Method($this, 'getHelpTemplatePath'),
    );
  }

  public function getHelpDocument($path = '') {
    $helpTemplatePath = craft()->plugins->getPlugin('help')->getSettings()->helpTemplatePath;
    $oldTemplatePath = craft()->path->getTemplatesPath();
    $newTemplatePath = craft()->path->getSiteTemplatesPath() . $helpTemplatePath;
    craft()->path->setTemplatesPath($newTemplatePath);

    if(craft()->templates->doesTemplateExist($path)) {
      $output = craft()->templates->render($path);
    }
    elseif ($path === '' || $path === 'index') {
      $newTemplatePath = craft()->path->getPluginsPath() . 'help/templates';
      craft()->path->setTemplatesPath($newTemplatePath);
      $output = craft()->templates->render('getting-started');
    }
    else {
      $newTemplatePath = craft()->path->getPluginsPath() . 'help/templates';
      craft()->path->setTemplatesPath($newTemplatePath);
      $output = craft()->templates->render('404');
    }

    craft()->path->setTemplatesPath($oldTemplatePath);
    echo $output;
  }

  /**
   * A Twig function to display the navigation sidebar, which is a nested list.
   */
  public function getSidebar() {
    // Temporarily set the template path to our plugin's directory, then render the shortcode replacement template.
    $oldTemplatePath = craft()->path->getTemplatesPath();
    $newTemplatePath = craft()->path->getPluginsPath().'help/templates';
    craft()->path->setTemplatesPath($newTemplatePath);

    $helpTemplatePath = craft()->plugins->getPlugin('help')->getSettings()->helpTemplatePath;
    $output = craft()->templates->render(
      'includes/sidebar',
      array(
        'docs' => craft()->help_docs->getDocs( craft()->path->getSiteTemplatesPath() . $helpTemplatePath)
      )
    );
    craft()->path->setTemplatesPath($oldTemplatePath);

    echo $output;

  }

  public function getHelpTemplatePath() {

    echo craft()->path->getSiteTemplatesPath() . craft()->plugins->getPlugin('help')->getSettings()->helpTemplatePath;
  }

  
}

?>