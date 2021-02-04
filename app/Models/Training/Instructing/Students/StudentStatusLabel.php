<?php

namespace App\Models\Training\Instructing\Students;

use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

/**
 * App\Models\Training\Instructing\Students\StudentStatusLabel
 *
 * @property int $id
 * @property string $name
 * @property string|null $fa_icon
 * @property string|null $colour
 * @property string|null $description
 * @property int $restricted
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|StudentStatusLabelLink[] $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereColour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereFaIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereRestricted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentStatusLabel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StudentStatusLabel extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'name', 'fa_icon', 'colour', 'description', 'restricted',
    ];

    public function students()
    {
        return $this->hasMany(StudentStatusLabelLink::class);
    }

    public function labelHtml()
    {
        $html = "<span style='font-weight: 400' class='badge rounded shadow-none ";

        //Colour
        if ($this->colour) {
            $html .= $this->colour." text-white'>";
        } else {
            $html .= "grey lighten-3 text-black'>";
        }

        //Icon
        if ($this->fa_icon) {
            $html .= "<i class='".$this->fa_icon." fa-fw'></i>&nbsp;";
        }

        $html .= $this->name.'</span>';

        return new HtmlString($html);
    }
}
