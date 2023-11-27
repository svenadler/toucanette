<?php declare(strict_types=1);

namespace App\EventListener;

use Sadl\Framework\Http\Event\ResponseEvent;

class ContentLengthListener
{
    /**
     * @param \Sadl\Framework\Http\Event\ResponseEvent $event
     *
     * @return void
     */
    public function __invoke(ResponseEvent $event): void
    {
        // an event is needed where we can get the response off
        $response = $event->getResponse();

        // if the response headers not have a Content-Length key then set it
        // get all headers
        if (!array_key_exists('Content-Length', $response->getHeaders())) {
            $response->setHeader('Content-Length', strlen($response->getContent()));
        }

//        dd($response);
//        dd('you got here!');
    }
}