<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'code', 'capacity', 'starts_at'];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Počet aktivně obsazených míst
    public function activeEnrollmentsCount(): int
    {
        return $this->enrollments()
            ->whereIn('status', ['enrolled', 'completed'])
            ->count();
    }
}
