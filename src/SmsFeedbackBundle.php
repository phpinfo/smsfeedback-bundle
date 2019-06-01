<?php
declare(strict_types=1);

namespace SmsFeedbackBundle;

use SmsFeedbackBundle\DependencyInjection\SmsFeedbackExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmsFeedbackBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        return SmsFeedbackExtension::class;
    }
}
