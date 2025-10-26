<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function tapel()
    {
        return $this->belongsTo(Tapel::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
