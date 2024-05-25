<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
  use HasFactory;
  protected $table = 'students';
  protected $fillable = ['name', 'course'];

  public function up()
  {
      Schema::create('students', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('course');
          $table->timestamps();
      });
  }
}
