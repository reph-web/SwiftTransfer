<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: 'email', 
    message: 'This email already used, please log in'
)]
#[UniqueEntity(
        fields: 'username', 
        message: 'This username already used'
)]
    
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // Create new property "contact" which is a jointable of contact list membership
    // 
    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: "user_contact",
        joinColumns: [new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "contact_user_id", referencedColumnName: "id")])]
    private Collection $contact;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'members')]
    private Collection $groups;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'sender')]
    private Collection $transactions_sended;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'receiver')]
    private Collection $transactions_received;

    #[ORM\Column]
    private ?float $balance = null;

    #[ORM\Column(length: 40)]
    private ?string $username = null;

    #[ORM\Column(length: 160, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(length: 40)]
    private ?string $displayedName = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'user')]
    private Collection $notifications;

    /**
     * @var Collection<int, Billing>
     */
    #[ORM\OneToMany(targetEntity: Billing::class, mappedBy: 'user')]
    private Collection $billings;

    public function __construct()
    {
        $this->contact = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->transactions_sended = new ArrayCollection();
        $this->transactions_received = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->billings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, self>
     */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    public function addContact(self $contact): static
    {
        if (!$this->contact->contains($contact)) {
            $this->contact->add($contact);
        }

        return $this;
    }

    public function removeContact(self $contact): static
    {
        $this->contact->removeElement($contact);

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addMember($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        if ($this->groups->removeElement($group)) {
            $group->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactionSended(): Collection
    {
        return $this->transactions_sended;
    }

    public function addTransactionSended(Transaction $transaction): static
    {
        if (!$this->transactions_sended->contains($transaction)) {
            $this->transactions_sended->add($transaction);
            $transaction->setSender($this);
        }

        return $this;
    }

    public function removeTransactionSended(Transaction $transaction): static
    {
        if ($this->transactions_sended->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getSender() === $this) {
                $transaction->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactionsReceived(): Collection
    {
        return $this->transactions_received;
    }

    public function addTransactionsReceived(Transaction $transactionsReceived): static
    {
        if (!$this->transactions_received->contains($transactionsReceived)) {
            $this->transactions_received->add($transactionsReceived);
            $transactionsReceived->setReceiver($this);
        }

        return $this;
    }

    public function removeTransactionsReceived(Transaction $transactionsReceived): static
    {
        if ($this->transactions_received->removeElement($transactionsReceived)) {
            // set the owning side to null (unless already changed)
            if ($transactionsReceived->getReceiver() === $this) {
                $transactionsReceived->setReceiver(null);
            }
        }

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getDisplayedName(): ?string
    {
        return $this->displayedName;
    }

    public function setDisplayedName(string $displayedName): static
    {
        $this->displayedName = $displayedName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function __toString(): string
    {
        // Retourne le username
        return $this->username ;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Billing>
     */
    public function getBillings(): Collection
    {
        return $this->billings;
    }

    public function addBilling(Billing $billing): static
    {
        if (!$this->billings->contains($billing)) {
            $this->billings->add($billing);
            $billing->setUser($this);
        }

        return $this;
    }

    public function removeBilling(Billing $billing): static
    {
        if ($this->billings->removeElement($billing)) {
            // set the owning side to null (unless already changed)
            if ($billing->getUser() === $this) {
                $billing->setUser(null);
            }
        }

        return $this;
    }
}
