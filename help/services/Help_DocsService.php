<?php 

namespace Craft;

use DirectoryIterator;

class Help_DocsService extends BaseApplicationComponent {

  /**
   * Returns a recursive array of all the files found in {pluginDir}/templates/docs.
   * They are assumed to be Twig/HTML.
   *
   * @param DirectoryIterator $dir The starting directory in which to find docs
   * @return array $data A nested array of all the files that were found.
   */
  public function getDocs($dir) {
    if (! is_dir($dir)) {
      return array();
    }
    
    $dir = new DirectoryIterator($dir);
    $data = array();
    foreach ( $dir as $node )
    {
      if (!$node->isDot()) {
        if($node->isFile() && $node->getFilename() != "index.twig" && substr($node->getFilename(), 0,1) != '.') {
          $data[$node->getFilename()] = array(
            'path' => $this->cleanFileName($node->getPathname()),
            'title' => $this->extractTitle($node->getPathname()),
            'children' => array()
          );
        }
        else if($node->isDir()) {
          $data[$node->getFilename()] = array(
            'path' => $this->cleanFileName($node->getPathname()),
            'title' => $this->extractTitle($node->getPathname()),
            'children' => $this->getDocs( $node->getPathname() )
          );
        }
      }
    }

    ksort($data);
    return $data;
  }

  /**
   * Removes the absolute path portions of the file path, so that the paths are relative to
   * {pluginDir}/templates/docs
   * 
   * @param string $file The absolute path to a file
   * @return string $file The path to the file, trimmed to a {pluginDir}/templates/docs
   */
  private function cleanFileName($file) {
    $helpTemplatePath = craft()->plugins->getPlugin('help')->getSettings()->helpTemplatePath;
    $docsBasePath = craft()->path->getSiteTemplatesPath() . $helpTemplatePath;

    $file = str_replace($docsBasePath, '', $file);
    $file = preg_replace('/\.(html|htm|twig)$/', '', $file);
    $file = trim($file, '/');
    
    return $file;
  }

  /**
   * Extracts a human-readable title from a file. Expects a Twig comment like this:
   * {# Title: My Page Title #}
   *
   * @param string $file The absolute path to a file
   * @return string The human-readable title if it exists, or the basename of the file.
   */
  private function extractTitle($file) {
    if (is_dir($file)) {
      $file = $file . "/index.twig";
    }

    $contents = file_get_contents($file);

    $hasTitle = preg_match('/\{# ?Title: ?(.*?) ?#\}/', $contents, $matches);

    if($hasTitle) {
      return $matches[1];
    }
    else {
      return basename($file);
    }
  }
}

// EOF