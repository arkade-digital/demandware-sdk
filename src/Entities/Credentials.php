<?php

namespace Arkade\Demandware\Entities;

class Credentials extends AbstractEntity
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var bool
     */
    protected $locked;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $passwordQuestion;

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return Credentials
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     * @return Credentials
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return Credentials
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordQuestion()
    {
        return $this->passwordQuestion;
    }

    /**
     * @param string $passwordQuestion
     * @return Credentials
     */
    public function setPasswordQuestion($passwordQuestion)
    {
        $this->passwordQuestion = $passwordQuestion;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Credentials
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }


}
