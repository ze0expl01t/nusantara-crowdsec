<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'crowdsec_audit_logs';
    protected $fillable = ['action', 'target', 'actor', 'meta'];
    protected $casts = ['meta' => 'array'];
}
