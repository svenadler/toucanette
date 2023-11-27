<?php

namespace Sadl\Framework\Http;

class RedirectResponse extends Response
{
    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    /**
     * overrides the parent send method
     * @return void
     */
    public function send(): void
    {
        header('Location: ' . $this->getHeader('location'), true, $this->getStatus());
        exit;
    }
}