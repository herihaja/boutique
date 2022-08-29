<?php 

namespace App\Service;

use App\Entity\EmailTracker;
use App\Repository\EmailTrackerRepository;
use DateTimeImmutable;

class EmailTrackerService
{

    public function __construct(private EmailTrackerRepository $trackerRepository)
    {
        
    }

    public function processEmailRead(string $token): EmailTracker
    {
        $tracker = $this->trackerRepository->findOneByToken($token);
        $tracker->setReadAt(new DateTimeImmutable());
        $tracker = $this->trackerRepository->add($tracker);
        return $tracker;
    }
}