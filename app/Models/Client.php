<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MediaFile;

class Client extends Model
{
    use HasFactory, MediaFile;
}
