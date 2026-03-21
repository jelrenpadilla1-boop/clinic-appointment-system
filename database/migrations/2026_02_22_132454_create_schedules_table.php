<?php
// database/migrations/2024_01_01_000004_create_schedules_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0-6 (Sunday-Saturday)
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_patients')->default(10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};