<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WhitelistedIp extends Model
{
    protected $table = 'crowdsec_whitelisted_ips';
    protected $fillable = ['ip', 'label', 'note', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];
}
