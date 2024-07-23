<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'diagnostic';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'diagnostic_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'diagnosis',
        'date',
    ];


    /**
     * Get the histories for the diagnostic.
     */
    public function histories()
    {
        return $this->hasMany(CitizenHistory::class, 'diagnostic_id', 'diagnostic_id');
    }
}
