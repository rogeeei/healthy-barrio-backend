<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medicine';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'medicine_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'usage_description',
        'quantity',
        'expiration_date',
        'batch_no',
        'location',
        'medicine_status',
    ];
}
