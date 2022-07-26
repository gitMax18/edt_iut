<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Symfony\Component\Validator\Contraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /** 
     * @Groups({"event:read"})
     */
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    /** 
     * @Groups({"event:read"})
     */
    private $startAt;

    #[ORM\Column(type: 'datetime_immutable')]
    /** 
     * @Groups({"event:read"})
     */
    private $endAt;

    #[ORM\Column(type: 'string', length: 255)]
    /** 
     * @Groups({"event:read"})
     */
    private $classroom;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    /** 
     * @Groups({"event:read"})
     */
    private $teacher;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'events')]
    /** 
     * @Groups({"event:read"})
     */
    private $course;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'events')]
    /** 
     * @Groups({"event:read"})
     */
    private $formation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getClassroom(): ?string
    {
        return $this->classroom;
    }

    public function setClassroom(string $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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
}
