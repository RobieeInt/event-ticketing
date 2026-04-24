<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerApplication extends Model
{
    protected $fillable = [
        'user_id','company_name','phone','reason',
        'status','reviewed_at','reviewed_by','review_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
