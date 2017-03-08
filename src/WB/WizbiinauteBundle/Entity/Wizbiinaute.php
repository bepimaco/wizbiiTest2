<?php

namespace WB\WizbiinauteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wizbiinaute
 *
 * @ORM\Table(name="wb_wizzbeenaute")
 * @ORM\Entity(repositoryClass="WB\WizbiinauteBundle\Repository\WizbiinauteRepository")
 */
class Wizbiinaute
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="wui", type="string", length=255)
     */
    private $wui;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Wizbiinaute
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Wizbiinaute
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set wui
     *
     * @param string $wui
     *
     * @return Wizbiinaute
     */
    public function setWui($wui)
    {
        $this->wui = $wui;

        return $this;
    }

    /**
     * Get wui
     *
     * @return string
     */
    public function getWui()
    {
        return $this->wui;
    }
}

