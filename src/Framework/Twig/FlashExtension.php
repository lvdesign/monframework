<?php
namespace Framework\Twig;

use Framework\Session\FlashService;

class FlashExtension extends \Twig\Extension\AbstractExtension
{
/**
 * @var FlashService
 */
    private $flashService;

    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('flash', [$this, 'getFlash'], ['is_safe' => ['html']]),
        ];
    }
    


    public function getFlash($type): ?string
    {
        return $this->flashService->get($type);
    }
}
