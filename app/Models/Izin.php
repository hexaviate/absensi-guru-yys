<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $guarded = [];

    /**
     * Get the user that owns the Izin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the instansi that owns the Izin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }
}
