<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tapel extends Model
{
    use SoftDeletes;
    protected $table = 'tapels';

    protected $fillable = [
        'kode',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    // Accessor untuk mendapatkan tahun ajaran dalam format yang mudah dibaca
    public function getTahunAjaranAttribute()
    {
        return $this->kode;
    }

    // Method untuk mendapatkan range tanggal berdasarkan tahun ajaran
    public function getDateRange()
    {
        // Support both formats: 2025-2026 and 2025/2026
        $years = preg_split('/[-\/]/', $this->kode);

        if (count($years) == 2) {
            $startYear = trim($years[0]);
            $endYear = trim($years[1]);

            return [
                'start' => $startYear . '-07-01',  // 1 Juli tahun pertama
                'end' => $endYear . '-06-30'       // 30 Juni tahun kedua
            ];
        }

        return null;
    }

    // Scope untuk mendapatkan tapel aktif (bisa disesuaikan dengan logika bisnis)
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at')
            ->orderBy('kode', 'desc');
    }


    /**
     * Get all of the jadwal for the Tapel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Get all of the kaldik for the Tapel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kaldik(): HasMany
    {
        return $this->hasMany(Kaldik::class);
    }

    /**
     * Get all of the hariLibur for the Tapel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hariLibur(): HasMany
    {
        return $this->hasMany(HariLibur::class);
    }

    public function izin()
    {
        return $this->hasMany(Izin::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function tidakHadir()
    {
        return $this->hasMany(TidakHadir::class);
    }

    public function tapel()
    {
        return $this->hasMany(Event::class);
    }

    public function WajibHadir()
    {
        return $this->hasMany(WajibHadir::class);
    }
}
