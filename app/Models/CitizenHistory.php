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
    'visit_date',
    'firstname',
    'middle_name',
    'lastname',
    'address',
    'date_of_birth',
    'gender',
    'citizen_status',
    'blood_type',
    'height',
    'weight',
    'allergies',
    'condition',
    'medication',
    'emergency_contact_name',
    'emergency_contact_no',
    'services_availed',
];


    /**
     * Get the citizen that owns the history.
     */
    public function citizen()
    {
        return $this->belongsTo(CitizenDetails::class, 'citizen_id', 'citizen_id');
    }

     // Define the relationship with the Diagnostics model
    public function diagnostics()
    {
        return $this->hasMany(Diagnostic::class); // Assuming you have a Diagnostic model
    }
public function services()
{
    return $this->belongsToMany(Services::class, 'citizen_history_service', 'citizen_history_id', 'service_id');
}
 public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class, 'diagnostic_id'); // Foreign key is diagnostic_id in the citizen_histories table
    }
}
