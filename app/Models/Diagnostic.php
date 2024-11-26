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
        'citizen_id',
    ];


    /**
     * Get the histories for the diagnostic.
     */
    public function histories()
    {
        return $this->hasMany(CitizenHistory::class, 'diagnostic_id', 'diagnostic_id');
    }

    public function diagnostics()
    {
        return $this->hasMany(Diagnostic::class, 'citizen_id', 'citizen_id');
    }
     public function citizen()
    {
        return $this->belongsTo(CitizenDetails::class, 'citizen_id');
    }
     public function citizenDetails()
    {
        return $this->belongsTo(CitizenDetails::class, 'citizen_id'); // Foreign key is citizen_id in the diagnostics table
    }

    public function citizenHistories()
    {
        return $this->hasMany(CitizenHistory::class, 'diagnostic_id'); // Foreign key is diagnostic_id in the citizen_histories table
    }
}
