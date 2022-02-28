<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Article extends Model
{
    use HasFactory, Sortable;
    protected $fillable = ['title', 'body', 'user_id'];
    public $sortable = ['title', 'created_at', 'updated_at'];
}
