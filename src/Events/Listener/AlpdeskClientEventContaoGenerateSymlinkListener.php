<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskClient\Events\Listener;

use Contao\CoreBundle\Event\GenerateSymlinksEvent;

class AlpdeskClientEventContaoGenerateSymlinkListener {

  public function __invoke(GenerateSymlinksEvent $event): void {
    $event->addSymlink(dirname(__DIR__) . '/../../alpdeskclient', 'web/alpdeskclient');
  }

}
