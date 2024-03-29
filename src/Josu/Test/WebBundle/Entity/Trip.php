<?php

namespace Josu\Test\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Josu\Test\WebBundle\Entity\Passenger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trip
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Trip
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 3)
     * @ORM\Column(name="departure_airport", type="string", length=3)
     */
    private $departureAirport;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 3)
     * @ORM\Column(name="destination_airport", type="string", length=3)
     */
    private $destinationAirport;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="departure_date", type="datetime")
     */
    private $departureDate;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="arrival_date", type="datetime")
     */
    private $arrivalDate;

    /**
     * @ORM\ManyToMany(targetEntity="Passenger", inversedBy="null", cascade={"persist"})
     * @ORM\JoinTable(name="trip_passenger",
     * joinColumns={@ORM\JoinColumn(name="trip_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="passenger_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $passengers;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy=null)
     **/
    private $customer;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set departureAirport
     *
     * @param string $departureAirport
     * @return Trip
     */
    public function setDepartureAirport($departureAirport)
    {
        $this->departureAirport = $departureAirport;

        return $this;
    }

    /**
     * Get departureAirport
     *
     * @return string
     */
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }

    /**
     * Set destinationAirport
     *
     * @param string $destinationAirport
     * @return Trip
     */
    public function setDestinationAirport($destinationAirport)
    {
        $this->destinationAirport = $destinationAirport;

        return $this;
    }

    /**
     * Get destinationAirport
     *
     * @return string
     */
    public function getDestinationAirport()
    {
        return $this->destinationAirport;
    }

    /**
     * Set departureDate
     *
     * @param \DateTime $departureDate
     * @return Trip
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     * @return Trip
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passengers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add passengers
     *
     * @param \Josu\Test\WebBundle\Entity\Passenger $passengers
     * @return Trip
     */
    public function addPassenger(\Josu\Test\WebBundle\Entity\Passenger $passengers)
    {
        $this->passengers[] = $passengers;

        return $this;
    }

    /**
     * Remove passengers
     *
     * @param \Josu\Test\WebBundle\Entity\Passenger $passengers
     */
    public function removePassenger(\Josu\Test\WebBundle\Entity\Passenger $passengers)
    {
        $this->passengers->removeElement($passengers);
    }

    /**
     * Get passengers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassengers()
    {
        return $this->passengers;
    }

    /**
     * Set customer
     *
     * @param \Josu\Test\WebBundle\Entity\Customer $customer
     * @return Trip
     */
    public function setCustomer(\Josu\Test\WebBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Josu\Test\WebBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

}
