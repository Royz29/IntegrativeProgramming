<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuditLog extends Model
{
    use HasFactory;

    protected $table = 'user_audit_logs'; // set the table name

    protected $fillable = [
        'user_id', // foreign key referencing the users table
        'action', // e.g. created, updated, deleted     
        'created_at', // automatically set by Laravel
        'old_values', // serialized array of the old attribute values before the action was taken
        'new_values', // serialized array of the new attribute values after the action was taken
        'user_agent', // the user agent of the user who performed the action
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
