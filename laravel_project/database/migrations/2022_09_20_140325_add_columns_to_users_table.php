<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender', 20)->after('role_id')->nullable();
            $table->string('phone', 20)->after('gender')->nullable();
            $table->integer('category_id')->after('phone')->nullable();
            $table->decimal('hourly_rate', 8, 2)->after('category_id')->default(0)->nullable();
            $table->string('experience_year',50)->after('hourly_rate')->nullable();
            $table->string('availability',50)->after('experience_year')->nullable();
            $table->string('address', 160)->after('availability')->nullable();
            $table->integer('city_id')->after('address')->nullable();
            $table->string('post_code', 10)->after('city_id')->nullable();
            $table->integer('state_id')->after('post_code')->nullable();
            $table->integer('country_id')->after('state_id')->nullable();
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
            $table->dropColumn('gender');
            $table->dropColumn('phone');
            $table->dropColumn('category_id');
            $table->dropColumn('hourly_rate');
            $table->dropColumn('experience_year');
            $table->dropColumn('availability');
            $table->dropColumn('address');
            $table->dropColumn('city_id');
            $table->dropColumn('post_code');
            $table->dropColumn('state_id');
            $table->dropColumn('country_id');
        });
    }
}
