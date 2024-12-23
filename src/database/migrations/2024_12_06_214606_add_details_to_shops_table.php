<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('address');
            $table->string('tell', 15)->nullable();
            $table->string('email')->nullable();
            $table->json('regular_holidays')->nullable();
            $table->json('closed_days')->nullable()->after('regular_holidays');
            $table->text('holidays_message')->nullable();
            $table->time('open_hours')->nullable();
            $table->time('close_hours')->nullable();
            $table->string('representative_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
            'address', 'tell', 'email',
            'regular_holidays','closed_days', 'holidays_message',
            'open_hours', 'close_hours', 'representative_name'
            ]);
        });
    }
}
