<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenDetails extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'citizen_details';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'citizen_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'middle_name',
        'lastname',
        'suffix',
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

    // public function histories()
    // {
    //     return $this->hasMany(CitizenHistory::class, 'citizen_id', 'citizen_id');
    // }

}
