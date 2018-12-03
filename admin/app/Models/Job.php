<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    const CREATED_AT = 'create_time';
	const UPDATED_AT = 'update_time';

	public function tag() {
		return $this->belongsTo(Tag::class);
	}
}
