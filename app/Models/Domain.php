<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'tld',
        'domain_created_date',
        'domain_expiry_date',
        'domain_registrar',
        'domain_registrar_url',
        'domain_whois_server',
    ];

}
