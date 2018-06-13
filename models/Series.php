<?php

namespace GinoPane\BlogTaxonomy\Models;

use Model;
use Cms\Classes\Controller;
use RainLab\Blog\Models\Post;
use GinoPane\BlogTaxonomy\Plugin;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Database\Traits\Validation;

/**
 * Class Series
 *
 * @package GinoPane\BlogTaxonomy\Models
 */
class Series extends Model
{
    use Validation;
    use Sluggable;
    use PostsRelationScopeTrait;

    const TABLE_NAME = 'ginopane_blogtaxonomy_series';

    /**
     * The database table used by the model
     *
     * @var string
     */
    public $table = self::TABLE_NAME;

    /**
     * Relations
     *
     * @var array
     */
    public $hasMany = [
        'posts' => [
            Post::class,
            'key' => self::TABLE_NAME . "_id"
        ],
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public $rules = [
        'title' => "required|unique:" . self::TABLE_NAME . "|min:3|regex:/^[a-z0-9\- ]+$/i",
        'slug'  => "required|unique:" . self::TABLE_NAME . "|min:3|regex:/^[a-z0-9\-]+$/i"
    ];

    /**
     * Validation messages
     *
     * @var array
     */
    public $customMessages = [
        'title.required' => Plugin::LOCALIZATION_KEY . 'lang.form.name_required',
        'title.unique'   => Plugin::LOCALIZATION_KEY . 'lang.form.name_unique',
        'title.regex'    => Plugin::LOCALIZATION_KEY . 'lang.form.name_invalid',
        'title.min'      => Plugin::LOCALIZATION_KEY . 'lang.form.name_too_short',
    ];

    /**
     * The attributes on which the post list can be ordered
     *
     * @var array
     */
    //@todo localize sorting options
    public static $sortingOptions = [
        'title asc' => 'Title (ascending)',
        'title desc' => 'Title (descending)',
        'created_at asc' => 'Created (ascending)',
        'created_at desc' => 'Created (descending)',
        'posts_count asc' => 'Post Count (ascending)',
        'posts_count desc' => 'Post Count (descending)',
        'random' => 'Random'
    ];

    /**
     * @var array
     */
    protected $slugs = ['slug' => 'title'];

    /**
     * @return mixed
     */
    public function getPostCountAttribute()
    {
        return $this->posts()->isPublished()->count();
    }

    /**
     * Sets the URL attribute with a URL to this object
     *
     * @param string                $pageName
     * @param Controller            $controller
     *
     * @return void
     */
    public function setUrl($pageName, $controller): void
    {
        $params = [
            'slug' => $this->slug,
        ];

        $this->url = $controller->pageUrl($pageName, $params);
    }
}
