<?php

namespace App\Listeners;

use App\Events\SongLikeToggled;
use App\Services\Lastfm;

class LoveTrackOnLastfm
{
    /**
     * The Last.fm service instance, which is DI'ed into our listener.
     *
     * @var Lastfm
     */
    protected $lastfm;

    /**
     * Create the event listener.
     *
     * @param Lastfm $lastfm
     */
    public function __construct(Lastfm $lastfm)
    {
        $this->lastfm = $lastfm;
    }

    /**
     * Handle the event.
     *
     * @param SongLikeToggled $event
     */
    public function handle(SongLikeToggled $event)
    {
        if (!$this->lastfm->enabled() ||
            !($sessionKey = $event->user->getLastfmSessionKey()) ||
            $event->interaction->song->album->artist->isUnknown()
        ) {
            return;
        }

        $this->lastfm->toggleLoveTrack(
            $event->interaction->song->title,
            $event->interaction->song->album->artist->name,
            $sessionKey,
            $event->interaction->liked
        );
    }
}
