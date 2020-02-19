<?php

declare(strict_types=1);

namespace App\Support\Auth\Social;

class User
{
    /** @var string|null */
    protected $email;

    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string|null */
    protected $link;

    public function __construct(?string $email, string $id, string $name, ?string $link)
    {
        $this->email = $email !== null ? clear_email($email) : null;
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
    }

    /**
     * @return string|null
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getLink() : ?string
    {
        return $this->link;
    }
}
