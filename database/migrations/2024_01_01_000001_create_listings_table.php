<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('city');
            $table->string('area');
            $table->string('exact_address');
            $table->string('phone', 20);
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->decimal('approx_lat', 10, 7);
            $table->decimal('approx_lng', 10, 7);
            $table->string('image_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('unlock_fee', 8, 2)->default(50.00);
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->enum('room_type', ['single', 'double', 'apartment', 'hostel'])->default('single');
            $table->json('amenities')->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('views')->default(0);
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('city');
            $table->index('is_available');
        });
    }
    public function down(): void { Schema::dropIfExists('listings'); }
};
