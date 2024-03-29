<?php declare(strict_types=1);

namespace Sadl\Framework\Template;

use Sadl\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    /**
     * @param \Sadl\Framework\Session\SessionInterface $session
     * @param string $templatesPath
     */
    public function __construct(
        private SessionInterface $session,
        private string $templatesPath
    )
    {
    }

    /**
     * @return \Twig\Environment
     */
    public function create(): Environment
    {
        // instantiate FileSystemLoader with templates path
        $loader = new FilesystemLoader($this->templatesPath);

        // instantiate Twig Environment with loader
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        // add new twig session() function to Environment
        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));

        return $twig;
    }

    /**
     * @return \Sadl\Framework\Session\SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}