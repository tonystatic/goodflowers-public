<?php

declare(strict_types=1);

namespace App\Support\Seo;

class MetaSet
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var array */
    private $keywords;

    /** @var string */
    private $imageUrl;

    public function __construct(string $title, string $description, array $keywords, string $imageUrl)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->imageUrl = $imageUrl;
    }

    /**
     * @param string $title
     * @return MetaSet
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $description
     * @return MetaSet
     */
    public function setDescription(string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param array $keywords
     * @return MetaSet
     */
    public function setKeywords(array $keywords) : self
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * @param array $keywords
     * @return MetaSet
     */
    public function addKeywords(array $keywords) : self
    {
        $this->keywords = \array_merge($this->keywords, $keywords);

        return $this;
    }

    /**
     * @param string $imageUrl
     * @return MetaSet
     */
    public function setImageUrl(string $imageUrl) : self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getKeywords() : array
    {
        return $this->keywords;
    }

    /**
     * @return string
     */
    public function getImageUrl() : string
    {
        return $this->imageUrl;
    }
}
