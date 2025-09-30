<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCommentLogTrigger extends Migration
{
    public function up()
    {
        // Trigger for INSERT operation
  
        // Trigger for soft DELETE (before update when deleted_at is set)
       
    }

    public function down()
    {
     
    }
}
