<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->date('move_in_date');
            $table->date('move_out_date')->nullable();
            $table->decimal('monthly_rent', 10, 2);
            $table->enum('status', ['inquiry', 'confirmed', 'cancelled', 'completed'])->default('inquiry');
            $table->text('message')->nullable();
            $table->text('owner_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'rewarded', 'expired'])->default('pending');
            $table->decimal('reward_amount', 8, 2)->default(0);
            $table->string('reward_type')->default('credit');
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();
            $table->unique(['referrer_id', 'referred_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('bookings');
    }
};
