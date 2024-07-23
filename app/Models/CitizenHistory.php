<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'citizen_history';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'citizen_history_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'citizen_id',
        'diagnostic_id',
        'date',
    ];

    /**
     * Get the citizen that owns the history.
     */
    public function citizen()
    {
        return $this->belongsTo(CitizenDetails::class, 'citizen_id', 'citizen_id');
    }

    /**
     * Get the diagnostic that owns the history.
     */
    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class, 'diagnostic_id', 'diagnostic_id');
    }
}
