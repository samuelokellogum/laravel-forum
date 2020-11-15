<?php

namespace TeamTeaTime\Forum\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;
use Kalnoy\Nestedset\NodeTrait;

use TeamTeaTime\Forum\Support\Web\Forum;
use TeamTeaTime\Forum\Support\Traits\CachesData;

class Category extends BaseModel
{
    use CachesData, NodeTrait;

    protected $table = 'forum_categories';
    protected $fillable = [
        'title',
        'description',
        'accepts_threads',
        'newest_thread_id',
        'latest_active_thread_id',
        'thread_count',
        'post_count',
        'is_private',
        'color'
    ];
    protected $appends = ['route'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->perPage = config('forum.general.pagination.categories');
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function newestThread(): HasOne
    {
        return $this->hasOne(Thread::class, 'id', 'newest_thread_id');
    }

    public function latestActiveThread(): HasOne
    {
        return $this->hasOne(Thread::class, 'id', 'latest_active_thread_id');
    }

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->where('parent_id', 0);
    }

    public function scopeAcceptsThreads(Builder $query): Builder
    {
        return $query->where('accepts_threads', 1);
    }

    public function scopeIsPrivate(Builder $query): Builder
    {
        return $query->where('is_private', 1);
    }

    public function getRouteAttribute(): string
    {
        return Forum::route('category.show', $this);
    }

    public function getNewestThreadId(): ?int
    {
        $thread = $this->threads()->orderBy('created_at', 'desc')->first();
        return $thread ? $thread->id : null;
    }

    public function getLatestActiveThreadId(): ?int
    {
        $thread = $this->threads()->orderBy('updated_at', 'desc')->first();
        return $thread ? $thread->id : null;
    }
}
