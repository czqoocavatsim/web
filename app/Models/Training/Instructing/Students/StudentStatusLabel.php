<?php

namespace App\Models\Training\Instructing\Students;

use App\Models\Training\Instructing\Links\StudentStatusLabelLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class StudentStatusLabel extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'name', 'fa_icon', 'colour', 'description', 'restricted'
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
            $html .= $this->colour . " text-white'>";
        } else {
            $html .= "grey lighten-3 text-black'>";
        }

        //Icon
        if ($this->fa_icon) {
            $html .= "<i class='" . $this->fa_icon . " fa-fw'></i>&nbsp;";
        }

        $html .= $this->name . "</span>";

        return new HtmlString($html);
    }
}
