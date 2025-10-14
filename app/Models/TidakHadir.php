<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TidakHadir extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function tapel()
    {
        return $this->belongsTo(Tapel::class);
    }
}
