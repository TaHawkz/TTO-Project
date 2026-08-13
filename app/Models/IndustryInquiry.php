<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryInquiry extends Model
{
    protected $fillable = [
        'company_name', 'contact_name', 'email', 'phone', 'inquiry_type', 'message',
    ];
}
