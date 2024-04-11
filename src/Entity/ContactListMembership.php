<?php

namespace App\Entity;

use App\Repository\ContactListMembershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactListMembershipRepository::class)]
class ContactListMembership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $user_id;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $contact_id;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->contact_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        $this->user_id->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getContactId(): Collection
    {
        return $this->contact_id;
    }

    public function addContactId(User $contactId): static
    {
        if (!$this->contact_id->contains($contactId)) {
            $this->contact_id->add($contactId);
        }

        return $this;
    }

    public function removeContactId(User $contactId): static
    {
        $this->contact_id->removeElement($contactId);

        return $this;
    }
}
