<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    const CREATED_AT = 'create_time';
	const UPDATED_AT = 'update_time';

    const STATUS_OFF = 1;
	const STATUS_ON = 1;

	public function tag() {
		return $this->belongsTo(Tag::class);
	}
}
