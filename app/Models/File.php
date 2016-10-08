<?php

/**
 * This file is part of sleeti.
 * Copyright (C) 2016  Eliot Partridge
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Sleeti\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * File model, many-to-one with User
 */
class File extends Model
{
	protected $table = 'uploaded_files';

	protected $fillable = [
		'owner_id',
		'filename',
		'ext', // At some point, there was likely a reason for me storing this. Dunno what it is now.
		'privacy_state',
	];

	public function user() {
		return $this->belongsTo('Sleeti\\Models\\User', 'owner_id', 'id');
	}

	public function getPath() {
		return $this->user->id . '/' . $this->id . ($this->filename !== null ? '-' . $this->filename : '') . ($this->ext !== null ? '.' . $this->ext : '');
	}
}
