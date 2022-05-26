<?php

namespace App\Message;

class ActivityMessage
{
    private int $activityId;

    public function __construct(int $activityId)
    {
        $this->activityId = $activityId;
    }

    public function getActivityId(): int
    {
        return $this->activityId;
    }
}
