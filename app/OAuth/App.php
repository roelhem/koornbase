<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:24
 */

namespace App\OAuth;


use App\Traits\HasDescription;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

/**
 * App\OAuth\App
 *
 * @property-read string $name_short
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OAuth\App findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\OAuth\App onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OAuth\App whereSlug($slug)
 * @method static \Illuminate\Database\Query\Builder|\App\OAuth\App withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\OAuth\App withoutTrashed()
 * @mixin \Eloquent
 */
class App extends Model
{

    use SoftDeletes, Userstamps, Sluggable;

    use HasShortName, HasDescription;


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'oauth_app';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name','name_short','description'];

}