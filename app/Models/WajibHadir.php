<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WajibHadir extends Model
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
