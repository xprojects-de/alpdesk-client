<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskClient\Controller\Client;

use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Contao\Environment;
use Symfony\Component\Filesystem\Filesystem;

class AlpdeskClientController extends AbstractController {

  protected ContaoFramework $framework;
  protected string $rootDir;

  public function __construct(ContaoFramework $framework, string $rootDir) {
    $this->framework = $framework;
    $this->framework->initialize();
    $this->rootDir = $rootDir;
  }

  public function client(Request $request): RedirectResponse {

    $filesystem = new Filesystem();

    try {

      $configFile = $this->rootDir . '/web/alpdeskclient/assets/config/config.prod.json';
      $clientFile = $this->rootDir . '/web/alpdeskclient/client.html';

      if ($filesystem->exists($clientFile) && $filesystem->exists($configFile)) {
        $settings = \json_decode(\file_get_contents($configFile), true);
        if ($settings !== null && \is_array($settings) && isset($settings['apiServer']['rest'])) {
          $stillModified = false;
          if (isset($settings['modified'])) {
            $stillModified = $settings['modified'];
          }
          if ($stillModified == false) {
            $settings['apiServer']['rest'] = \substr(Environment::get('base'), 0, (\strlen(Environment::get('base')) - 1));
            $settings['modified'] = true;
            $newSettings = \json_encode($settings);
            $newConfigFile = \fopen($configFile, "wb");
            \fwrite($newConfigFile, $newSettings);
            \fclose($newConfigFile);
          }
          return (new RedirectResponse(Environment::get('base') . 'alpdeskclient/client.html'));
        }
      }
    } catch (\Exception $ex) {
      
    }
    return (new RedirectResponse(Environment::get('base')));
  }

}
