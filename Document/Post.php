<?php

namespace Bundle\Balibali\BlogBundle\Document;

/**
 * @Document(collection="posts")
 */
class Post
{
    /** @Id */
    protected $id;

    /**
     * @String
     * @Validation({ @NotBlank })
     */
    protected $title;

    /**
     * @String
     * @Validation({ @NotBlank })
     */
    protected $body;

    /**
     * @Date
     * @Index(order="desc")
     */
    protected $publishedAt;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
}
