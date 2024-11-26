<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';


    protected $fillable = [
        'name',
        'description'
    ];

    public function citizens()
    {
        return $this->belongsToMany(CitizenDetails::class, 'citizen_service', 'service_id', 'citizen_id');
    }
    public function citizenHistories()
{
    return $this->belongsToMany(CitizenHistory::class, 'citizen_history_service', 'service_id', 'citizen_history_id');
}
public function services()
{
    return $this->belongsToMany(Services::class, 'citizen_service', 'citizen_id', 'services_id');
}

}
