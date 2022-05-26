<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $jiraKey;

    #[ORM\Column(type: 'string', length: 255)]
    private $summary;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private $project;

    #[ORM\Column(type: 'boolean')]
    private $isManaged = false;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private $createdAt;
    

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityLog::class)]
    private $activityLogs;

    public function __construct()
    {
        $this->activityLogs = new ArrayCollection();
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

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

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

    public function getIsManaged(): ?bool
    {
        return $this->isManaged;
    }

    public function setIsManaged(bool $isManaged): self
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

    /**
     * @return Collection|ActivityLog[]
     */
    public function getActivityLogs(): Collection
    {
        return $this->activityLogs;
    }

    public function addActivityLog(ActivityLog $activityLog): self
    {
        if (!$this->activityLogs->contains($activityLog)) {
            $this->activityLogs[] = $activityLog;
            $activityLog->setActivity($this);
        }

        return $this;
    }

    public function removeActivityLog(ActivityLog $activityLog): self
    {
        if ($this->activityLogs->removeElement($activityLog)) {
            // set the owning side to null (unless already changed)
            if ($activityLog->getActivity() === $this) {
                $activityLog->setActivity(null);
            }
        }

        return $this;
    }
}
