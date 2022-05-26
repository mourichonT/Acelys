<?php

namespace App\Entity;

use App\Repository\ActivityLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityLogRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ActivityLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $jiraKey;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $summary;


    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isManaged;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: 'activityLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private $activity;

    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'activityLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private $admin;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $project;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJiraKey(): ?string
    {
        return $this->jiraKey;
    }

    public function setJiraKey(string $jiraKey): self
    {
        $this->jiraKey = $jiraKey;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }
    
    public function getIsManaged(): ?bool
    {
        return $this->isManaged;
    }

    public function setIsManaged(?bool $isManaged): self
    {
        $this->isManaged = $isManaged;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getAdmin(): ?Admin
    {

        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {

        $this->admin = $admin;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
