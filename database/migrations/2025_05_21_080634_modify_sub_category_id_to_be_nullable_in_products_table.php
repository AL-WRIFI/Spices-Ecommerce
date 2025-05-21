<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['sub_category_id']);
            
            // Change the column to be nullable
            $table->foreignId('sub_category_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('sub_categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['sub_category_id']);
            
            // Change the column back to be non-nullable
            $table->foreignId('sub_category_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('sub_categories')
                  ->onDelete('cascade');
        });
    }
};

