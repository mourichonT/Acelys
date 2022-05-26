<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private $nicokaId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $jiraKey;

    #[ORM\Column(type: 'float', nullable: true)]
    private $tjm;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Activity::class, orphanRemoval: true)]
    private $activities;

    #[ORM\ManyToMany(targetEntity: Admin::class, inversedBy: 'projects', cascade: ['persist'])]
    private $admins;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->admins = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNicokaId(): ?String
    {
        return $this->nicokaId;
    }

    public function setNicokaId(?String $nicokaId): self
    {
        $this->nicokaId = $nicokaId;

        return $this;
    }

    public function getJiraKey(): ?string
    {
        return $this->jiraKey;
    }

    public function setJiraKey(?string $jiraKey): self
    {
        $this->jiraKey = $jiraKey;

        return $this;
    }

    public function getTjm(): ?float
    {
        return $this->tjm;
    }

    public function setTjm(?float $tjm): self
    {
        $this->tjm = $tjm;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setProject($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getProject() === $this) {
                $activity->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Admin>
     */
    public function getAdmins(): Collection
    {
        return $this->admins;
    }

    public function addAdmin(Admin $admin): self
    {
        if (!$this->admins->contains($admin)) {
            $this->admins[] = $admin;
        }

        return $this;
    }

    public function removeAdmin(Admin $admin): self
    {
        $this->admins->removeElement($admin);

        return $this;
    }
}
