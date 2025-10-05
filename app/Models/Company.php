<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'logo',
        'website'
    ];

    /**
     * Get employees for this company
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Route notifications for the mail channel
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }
}
