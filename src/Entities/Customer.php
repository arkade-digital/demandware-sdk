<?php

namespace Arkade\Demandware\Entities;

use Carbon\Carbon;

class Customer extends AbstractEntity
{
    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $customerNo;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int 1:M 2:F
     */
    protected $gender;

    /**
     * @var Carbon
     */
    protected $birthday;

    /**
     * @var string
     */
    protected $companyName;

    /**
     * @var string
     */
    protected $jobTitle;

    /**
     * @var string
     */
    protected $phoneHome;

    /**
     * @var string
     */
    protected $phoneMobile;

    /**
     * @var string
     */
    protected $salutation;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * @var Address
     */
    protected $primaryAddress;

    /**
     * @var LoyaltyCartridge
     */
    protected $loyaltyCartridge;

    /**
     * @var Carbon
     */
    protected $creationDate;

    /**
     * @var Carbon
     */
    protected $lastModified;

    /**
     * @var Carbon
     */
    protected $lastLoginTime;

    /**
     * @var Carbon
     */
    protected $lastVisitTime;

    /**
     * @var Carbon
     */
    protected $previousLoginTime;

    /**
     * @var Carbon
     */
    protected $previousVisitTime;

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     * @return Customer
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerNo()
    {
        return $this->customerNo;
    }

    /**
     * @param string $customerNo
     * @return Customer
     */
    public function setCustomerNo($customerNo)
    {
        $this->customerNo = $customerNo;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Customer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Customer
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Customer
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     * @return Customer
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param Carbon $birthday
     * @return Customer
     */
    public function setBirthday(Carbon $birthday = null)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     * @return Customer
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     * @return Customer
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneHome()
    {
        return $this->phoneHome;
    }

    /**
     * @param string $phoneHome
     * @return Customer
     */
    public function setPhoneHome($phoneHome)
    {
        $this->phoneHome = $phoneHome;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneMobile()
    {
        return $this->phoneMobile;
    }

    /**
     * @param string $phoneMobile
     * @return Customer
     */
    public function setPhoneMobile($phoneMobile)
    {
        $this->phoneMobile = $phoneMobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param string $salutation
     * @return Customer
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     * @return Customer
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Customer
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Credentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * @param Credentials $credentials
     * @return Customer
     */
    public function setCredentials(Credentials $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * @return Address
     */
    public function getPrimaryAddress()
    {
        return $this->primaryAddress;
    }

    /**
     * @param Address $primaryAddress
     * @return Customer
     */
    public function setPrimaryAddress(Address $primaryAddress)
    {
        $this->primaryAddress = $primaryAddress;
        return $this;
    }

    /**
     * @return LoyaltyCartridge
     */
    public function getLoyaltyCartridge()
    {
        return $this->loyaltyCartridge;
    }

    /**
     * @param LoyaltyCartridge $loyaltyCartridge
     * @return Customer
     */
    public function setLoyaltyCartridge($loyaltyCartridge)
    {
        $this->loyaltyCartridge = $loyaltyCartridge;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param Carbon $creationDate
     * @return Customer
     */
    public function setCreationDate(Carbon $creationDate = null)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param Carbon $lastModified
     * @return Customer
     */
    public function setLastModified(Carbon $lastModified = null)
    {
        $this->lastModified = $lastModified;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * @param Carbon $lastLoginTime
     * @return Customer
     */
    public function setLastLoginTime(Carbon $lastLoginTime = null)
    {
        $this->lastLoginTime = $lastLoginTime;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getLastVisitTime()
    {
        return $this->lastVisitTime;
    }

    /**
     * @param Carbon $lastVisitTime
     * @return Customer
     */
    public function setLastVisitTime(Carbon $lastVisitTime = null)
    {
        $this->lastVisitTime = $lastVisitTime;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getPreviousLoginTime()
    {
        return $this->previousLoginTime;
    }

    /**
     * @param Carbon $previousLoginTime
     * @return Customer
     */
    public function setPreviousLoginTime(Carbon $previousLoginTime = null)
    {
        $this->previousLoginTime = $previousLoginTime;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getPreviousVisitTime()
    {
        return $this->previousVisitTime;
    }

    /**
     * @param Carbon $previousVisitTime
     * @return Customer
     */
    public function setPreviousVisitTime(Carbon $previousVisitTime = null)
    {
        $this->previousVisitTime = $previousVisitTime;
        return $this;
    }

    /**
     * @return Array
     */
    public function jsonSerialize()
    {
        $result = get_object_vars($this);

        if(!is_null($result['birthday'])) $result['birthday'] = $result['birthday']->toDateString();

        return $result;
    }

}
