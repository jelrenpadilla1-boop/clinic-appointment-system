<?php
// database/migrations/xxxx_add_all_columns_to_doctors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Add all columns without specifying position
            // MySQL will add them at the end automatically
            $table->string('qualification')->nullable();
            $table->integer('experience')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->integer('max_patients')->nullable()->default(20);
            $table->json('services')->nullable();
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'qualification',
                'experience',
                'bio',
                'fee',
                'max_patients',
                'services'
            ]);
        });
    }
};