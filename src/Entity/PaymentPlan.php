<?php

namespace App\Entity;

use App\Repository\PaymentPlanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentPlanRepository::class)]
class PaymentPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $PaymentDate = null;

    #[ORM\ManyToOne(inversedBy: 'PaymentPlan')]
    private ?Payments $payments = null;

    #[ORM\Column]
    private ?int $Amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->PaymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $PaymentDate): static
    {
        $this->PaymentDate = $PaymentDate;

        return $this;
    }

    public function getPayments(): ?Payments
    {
        return $this->payments;
    }

    public function setPayments(?Payments $payments): static
    {
        $this->payments = $payments;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->Amount;
    }

    public function setAmount(int $Amount): static
    {
        $this->Amount = $Amount;

        return $this;
    }
}
