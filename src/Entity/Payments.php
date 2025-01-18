<?php

namespace App\Entity;

use App\Repository\PaymentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?paymentuser $email = null;

    #[ORM\Column(length: 255)]
    private ?string $PaymentName = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentType = null;

    /**
     * @var Collection<int, CyclicPayment>
     */
    #[ORM\OneToMany(targetEntity: CyclicPayment::class, mappedBy: 'Payment')]
    private Collection $cyclicPayments;

    /**
     * @var Collection<int, PaymentPlan>
     */
    #[ORM\OneToMany(targetEntity: PaymentPlan::class, mappedBy: 'payments')]
    private Collection $PaymentPlan;

    #[ORM\Column(nullable: true)]
    private ?bool $isPaid = null;

    public function __construct()
    {
        $this->cyclicPayments = new ArrayCollection();
        $this->PaymentPlan = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?paymentuser
    {
        return $this->email;
    }

    public function setEmail(?paymentuser $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPaymentName(): ?string
    {
        return $this->PaymentName;
    }

    public function setPaymentName(string $PaymentName): static
    {
        $this->PaymentName = $PaymentName;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * @return Collection<int, CyclicPayment>
     */
    public function getCyclicPayments(): Collection
    {
        return $this->cyclicPayments;
    }

    public function addCyclicPayment(CyclicPayment $cyclicPayment): static
    {
        if (!$this->cyclicPayments->contains($cyclicPayment)) {
            $this->cyclicPayments->add($cyclicPayment);
            $cyclicPayment->setPayment($this);
        }

        return $this;
    }

    public function removeCyclicPayment(CyclicPayment $cyclicPayment): static
    {
        if ($this->cyclicPayments->removeElement($cyclicPayment)) {
            // set the owning side to null (unless already changed)
            if ($cyclicPayment->getPayment() === $this) {
                $cyclicPayment->setPayment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaymentPlan>
     */
    public function getPaymentPlan(): Collection
    {
        return $this->PaymentPlan;
    }

    public function addPaymentPlan(PaymentPlan $paymentPlan): static
    {
        if (!$this->PaymentPlan->contains($paymentPlan)) {
            $this->PaymentPlan->add($paymentPlan);
            $paymentPlan->setPayments($this);
        }

        return $this;
    }

    public function removePaymentPlan(PaymentPlan $paymentPlan): static
    {
        if ($this->PaymentPlan->removeElement($paymentPlan)) {
            // set the owning side to null (unless already changed)
            if ($paymentPlan->getPayments() === $this) {
                $paymentPlan->setPayments(null);
            }
        }

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setPaid(?bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }
}
