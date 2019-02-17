<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::dropIfExists('users');

        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('role')->insert(
            array(
                'name' => 'admin'
            )
        );

        \Illuminate\Support\Facades\DB::table('role')->insert(
            array(
                'name' => 'market'
            )
        );

        \Illuminate\Support\Facades\DB::table('role')->insert(
            array(
                'name' => 'investor'
            )
        );

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedInteger('role')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role')
                ->references('id')->on('role')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('role');
    }
}
