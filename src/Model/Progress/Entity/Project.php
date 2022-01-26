<?php declare(strict_types=1);

namespace App\Model\Progress\Entity;

use App\Model\Progress\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'progress_projects')]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id()]
    #[ORM\Column(type: 'ulid')]
    private Ulid $id;

    #[ORM\Column(name: 'title', type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'creator_id', referencedColumnName: 'id', nullable: false)]
    private User $creator;

    // TODO
    private Collection $members;

    public function __construct(
        Ulid $id,
        string $title,
        User $creator
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->creator = $creator;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }
}
