<?php

namespace App\Service;

use App\Entity\Scene;
use App\Entity\User;
use App\Entity\WorkSession;
use App\Repository\WorkSessionRepository;

class WorkSessionService
{
    private $workSessionRepository;
    private $edgeInterval;

    public function __construct(
        WorkSessionRepository $workSessionRepository,
        string $workSessionEdgeInterval
    ) {
        $this->workSessionRepository = $workSessionRepository;
        $this->edgeInterval = $workSessionEdgeInterval;
    }

    public function ping(User $user, Scene $scene)
    {
        $currentDateTime = new \DateTime();
        $edgeDateTime = (new \DateTime())->sub(new \DateInterval($this->edgeInterval));
        $workSession = $this->workSessionRepository->fetchCurrent($user, $scene, $edgeDateTime);

        if (!$workSession) {
            $workSession = new WorkSession();
            $workSession
                ->setUser($user)
                ->setScene($scene)
                ->setChapter($scene->getChapter())
                ->setProject($scene->getChapter()->getProject())
                ->setStart($currentDateTime)
            ;
        }

        $workSession->setEnd($currentDateTime);
        $workSession->setSeconds($this->computeSeconds($workSession));

        $this->workSessionRepository->save($workSession);
    }

    private function computeSeconds(WorkSession $workSession)
    {
        return
            $workSession->getEnd()->getTimestamp()
            + $workSession->getEnd()->getOffset()
            - $workSession->getStart()->getTimestamp()
            - $workSession->getStart()->getOffset()
        ;
    }
}
