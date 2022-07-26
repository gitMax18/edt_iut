<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /** 
     * @Groups({"course:read", "event:read"})
     */
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    /** 
     * @Groups({"course:read","event:read", "formation:read"})
     */
    private $name;

    #[ORM\Column(type: 'float', nullable: true)]
    /** 
     * @Groups({"course:read"})
     */
    private $hours;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'courses')]
    /** 
     * @Groups({"course:read"})
     */
    private $teachers;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Event::class)]
    private $events;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'courses')]
    private $formation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $backgroundColor;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $borderColor;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $textColor;

    public function __construct()
    {
        $this->teachers = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(?float $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(User $teacher): self
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers[] = $teacher;
        }

        return $this;
    }

    public function removeTeacher(User $teacher): self
    {
        $this->teachers->removeElement($teacher);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setCourse($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCourse() === $this) {
                $event->setCourse(null);
            }
        }

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    public function setBorderColor(?string $borderColor): self
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }
}
