<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_paid', 8, 2);
            $table->string('payment_method')->default('khalti');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('khalti_token')->nullable();
            $table->string('khalti_idx')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->json('payment_response')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'listing_id']);
            $table->index('payment_status');
        });
    }
    public function down(): void { Schema::dropIfExists('unlocks'); }
};
