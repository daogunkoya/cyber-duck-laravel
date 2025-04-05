<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coffee_sales', function (Blueprint $table) {
            $table->id();$table->foreignIdFor(User::class)->after('id')
            ->constrained()
            ->cascadeOnDelete();
            $table->foreignId('coffee_product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('unit_cost', 8, 2);
            $table->decimal('selling_price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffee_sales');
    }
};
