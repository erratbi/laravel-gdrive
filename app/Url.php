<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property string link
 * @property string name
 * @property string status
 * @property int size
 */
class Url extends Model {
    public $guarded = [];
}
