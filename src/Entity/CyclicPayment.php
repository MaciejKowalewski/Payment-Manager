<?php

namespace App\Entity;

use App\Repository\CyclicPaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CyclicPaymentRepository::class)]
class CyclicPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $days = null;

    #[ORM\Column(nullable: true)]
    private ?int $months = null;

    #[ORM\Column(nullable: true)]
    private ?int $years = null;

    #[ORM\ManyToOne(inversedBy: 'cyclicPayments')]
    private ?Payments $Payment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(?int $days): static
    {
        $this->days = $days;

        return $this;
    }

    public function getMonths(): ?int
    {
        return $this->months;
    }

    public function setMonths(?int $months): static
    {
        $this->months = $months;

        return $this;
    }

    public function getYears(): ?int
    {
        return $this->years;
    }

    public function setYears(?int $years): static
    {
        $this->years = $years;

        return $this;
    }

    public function getPayment(): ?Payments
    {
        return $this->Payment;
    }

    public function setPayment(?Payments $Payment): static
    {
        $this->Payment = $Payment;

        return $this;
    }
}
