<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'questions';
    public $timestamps = false;

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function correct_answer()
    {
        return $this->hasOne(Answer::class, 'id', 'correct_answer');
    }
}
