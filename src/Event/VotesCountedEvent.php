<?php
/**
 * Votix. The advanced and secure online voting platform.
 *
 * @author Club*Nix <club.nix@edu.esiee.fr>
 *
 * @license MIT
 */
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class VotesCountedEvent
 */
class VotesCountedEvent extends Event
{
    public const NAME = 'votes.counted';

    /**
     * @var array
     */
    private $results;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
