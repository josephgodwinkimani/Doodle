<?php

declare(strict_types=1);

// src/Entities/Product.php

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 */
class Product
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /** @ORM\Column(type="string") */
    private $name;
    /**
     * @var string|null
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string")
     */
    private $createdBy;
    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;
    /**
     * @var string $createdFromIp
     *
     * @Gedmo\IpTraceable(on="create")
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $createdFromIp;

    /**
     * @var string|null
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string")
     */
    private $updatedBy;
    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;
    /**
     * @var string $updatedFromIp
     *
     * @Gedmo\IpTraceable(on="update")
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $updatedFromIp;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getCreatedFromIp()
    {
        return $this->createdFromIp;
    }

    public function getUpdatedFromIp()
    {
        return $this->updatedFromIp;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    // Setters
    public function setName($name): void
    {
        if (is_string($name)) {
            $this->name = $name;
        }
    }

    public function setCreatedBy($createdBy)
    {
        if (is_string($createdBy)) {
            $this->createdBy = $createdBy;
        }
    }

    public function setUpdatedBy($updatedBy)
    {
        if (is_string($updatedBy)) {
            $this->updatedBy = $updatedBy;
        }
    }

    public function setDeletedAt(?\DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

}
