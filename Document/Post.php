<?php

namespace Bundle\Balibali\BlogBundle\Document;

/**
 * @mongodb:Document(collection="posts")
 */
class Post
{
    /** @mongodb:Id */
    protected $id;

    /** @mongodb:String */
    protected $slug;

    /**
     * @mongodb:String
     * @Validation({ @NotBlank })
     */
    protected $title;

    /**
     * @mongodb:String
     * @Validation({ @NotBlank })
     */
    protected $body;

    /**
     * @mongodb:Date
     * @mongodb:Index(order="desc")
     */
    protected $publishedAt;

    /** @mongodb:String */
    protected $format;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);
    }

    public function slugify($text, $default = 'blog-post')
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        $text = trim($text, '-');

        return $text ? $text : $default;
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

    public function getFormat()
    {
        return $this->format ? $this->format : 'html';
    }

    public function setFormat($format)
    {
        if ($format === 'markdown') {
            $this->format = $format;
        }
    }
}
