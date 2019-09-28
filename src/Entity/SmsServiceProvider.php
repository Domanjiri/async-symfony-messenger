<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SmsServiceProviderRepository")
 */
class SmsServiceProvider
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alias;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total_call;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $failed_calls;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gateway;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getTotalCall(): ?int
    {
        return $this->total_call;
    }

    public function setTotalCall(?int $total_call): self
    {
        $this->total_call = $total_call;

        return $this;
    }

    public function getFailedCalls(): ?int
    {
        return $this->failed_calls;
    }

    public function setFailedCalls(?int $failed_calls): self
    {
        $this->failed_calls = $failed_calls;

        return $this;
    }

    public function getGateway(): ?string
    {
        return $this->gateway;
    }

    public function setGateway(string $gateway): self
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
