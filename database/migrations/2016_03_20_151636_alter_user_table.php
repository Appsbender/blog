<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');

            $table->string('username')->after('id');
            $table->string('first_name')->after('username');
            $table->string('last_name')->after('first_name');
            $table->string('email_address')->unique()->after('last_name');
            $table->boolean('deleted_flag')->default(false)->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('email_address');
            $table->dropColumn('deleted_flag');

            $table->string('name')->after('id');
            $table->string('email')->unique()->after('name');
        });
    }
}
