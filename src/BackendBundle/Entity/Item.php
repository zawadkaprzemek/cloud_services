<?php

namespace App\BackendBundle\Entity;

use App\BackendBundle\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api"})
     * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Nazwa produktu musi mieć conajmniej {{ limit }} znaków",
     *      maxMessage = "Nazwa produktu może mieć maksymalnie {{ limit }} znaków"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"api"})
     * @Assert\Range(
     *     min=0,
     *     notInRangeMessage = "Wartość musi być większa równa {{min}}",
     * )
     */
    private $amount;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
