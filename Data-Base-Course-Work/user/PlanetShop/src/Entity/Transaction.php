<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_customer;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_space_object;

    /**
     * @ORM\Column(type="datetime")
     */
    private $paid_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCustomer(): ?int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(int $id_customer): self
    {
        $this->id_customer = $id_customer;

        return $this;
    }

    public function getIdSpaceObject(): ?int
    {
        return $this->id_space_object;
    }

    public function setIdSpaceObject(int $id_space_object): self
    {
        $this->id_space_object = $id_space_object;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paid_at;
    }

    public function setPaidAt(\DateTimeInterface $paid_at): self
    {
        $this->paid_at = $paid_at;

        return $this;
    }
}
