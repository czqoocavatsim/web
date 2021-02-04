<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\UserNote
 *
 * @property int $id
 * @property int $user_id
 * @property int $author
 * @property string $content
 * @property int $confidential
 * @property string $timestamp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Users\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereConfidential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserNote whereUserId($value)
 * @mixin \Eloquent
 */
class UserNote extends Model
{
    protected $fillable = [
        'user_id', 'author', 'content', 'confidential', 'timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
