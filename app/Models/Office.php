<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['name', 'location', 'support_score'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
