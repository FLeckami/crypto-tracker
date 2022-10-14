<?php

namespace App\Entity;

use App\Repository\CryptoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptoRepository::class)]
class Crypto
{
    private $list_abbr = [
        'Bitcoin' => 'BTC',
        'Ethereum' => 'ETH',
        'Ripple' => 'XRP'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $crypto_name = null;

    #[ORM\Column(length: 3)]
    private ?string $crypto_abbr = null;

    #[ORM\Column]
    private ?float $crypto_qty = null;

    #[ORM\Column]
    private ?float $buying_price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $Id): self
    {
        $this->Id = $Id;

        return $this;
    }

    public function getCryptoName(): ?string
    {
        return $this->crypto_name;
    }

    public function setCryptoName(string $crypto_name): self
    {
        $this->crypto_name = $crypto_name;
        $this->crypto_abbr = $this->list_abbr[$crypto_name];

        return $this;
    }

    public function getCryptoQty(): ?float
    {
        return $this->crypto_qty;
    }

    public function setCryptoQty(float $crypto_qty): self
    {
        $this->crypto_qty = $crypto_qty;

        return $this;
    }

    public function getBuyingPrice(): ?float
    {
        return $this->buying_price;
    }

    public function setBuyingPrice(float $buying_price): self
    {
        $this->buying_price = $buying_price;

        return $this;
    }

    public function getCryptoAbbr(): ?string
    {
        return $this->crypto_abbr;
    }

    public function setCryptoAbbr(string $crypto_abbr): self
    {
        $this->crypto_abbr = $crypto_abbr;

        return $this;
    }
}
