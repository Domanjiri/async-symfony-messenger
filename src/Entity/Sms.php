<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Validator\Constraints as SmsAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SmsRepository")
 * @ORM\Table(name="sms",indexes={@ORM\Index(name="number_idx", columns={"number"})})
 */
class Sms
{
    const SMS_PENDING = 2;
    const SMS_SUCCESS = 1;
    const SMS_FAILED = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @Assert\Type(type="string")
     * @ORM\Column(type="string", length=255)
     */
    protected $uniqueId;

    /**
     * @Assert\NotBlank
     * @SmsAssert\IranMobileNumber
     * @ORM\Column(type="integer")
     */
    protected $number;

    /**
     * @Assert\Type(type="string")
     * @Assert\Length(max = 255)
     * @ORM\Column(type="string", length=255)
     */
     protected $text;

     /**
      * @ORM\Column(type="integer")
      */
    protected $timestamp;
    
    // TODO:protected $lastChange;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SmsServiceProvider", inversedBy="sms")
     */
    protected $smsServiceProvider;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function __construct()
    {
        $this->uniqueId = uniqid();
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setUniqueId(string $uniqueId): self
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp ?? time();
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSmsServiceProvider(): ?SmsServiceProvider
    {
        return $this->smsServiceProvider;
    }

    public function setSmsServiceProvider(?SmsServiceProvider $smsServiceProvider): self
    {
        $this->smsServiceProvider = $smsServiceProvider;

        return $this;
    }
}

