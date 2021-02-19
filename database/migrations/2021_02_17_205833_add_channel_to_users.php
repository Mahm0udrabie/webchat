<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'channel')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string("channel");
        });
    }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('users', 'channel')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('channel');
            });
        }
    }
}
