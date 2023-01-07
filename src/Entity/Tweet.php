<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'tweets')]
    private ?self $parent_id = null;

    #[ORM\OneToMany(mappedBy: 'parent_id', targetEntity: self::class)]
    private Collection $tweets;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getParentId(): ?self
    {
        return $this->parent_id;
    }

    public function setParentId(?self $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(self $tweet): self
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets->add($tweet);
            $tweet->setParentId($this);
        }

        return $this;
    }

    public function removeTweet(self $tweet): self
    {
        if ($this->tweets->removeElement($tweet)) {
            // set the owning side to null (unless already changed)
            if ($tweet->getParentId() === $this) {
                $tweet->setParentId(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
