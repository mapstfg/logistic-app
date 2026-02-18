<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('document_id')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->integer('stock_bodega')->default(0);
            $table->integer('stock_farmacia')->default(0);
            $table->integer('min_stock')->default(0);
            $table->date('expires_at')->nullable();
            $table->string('location')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->integer('stock_bodega')->default(0);
            $table->integer('stock_farmacia')->default(0);
            $table->integer('min_stock')->default(0);
            $table->date('expires_at')->nullable();
            $table->string('location')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('doctor_id')->constrained('users');
            $table->enum('status', ['PENDIENTE', 'ENTREGADA', 'CANCELADA'])->default('PENDIENTE');
            $table->dateTime('issued_at')->nullable(); // Make issued_at nullable or handle it
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->string('item_type'); // MEDICINE or SUPPLY
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->integer('delivered_quantity')->default(0);
            $table->timestamps();
        });

        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('from_location', ['BODEGA', 'FARMACIA']);
            $table->enum('to_location', ['BODEGA', 'FARMACIA']);
            $table->enum('status', ['PENDIENTE', 'COMPLETADO', 'CANCELADO'])->default('PENDIENTE');
            $table->dateTime('transfer_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained()->onDelete('cascade');
            $table->string('item_type'); // MEDICINE or SUPPLY
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('movement_type', ['IN', 'OUT', 'TRANSFER', 'ADJUSTMENT']);
            $table->string('item_type'); // MEDICINE or SUPPLY
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('patient_id')->nullable()->constrained();
            $table->foreignId('prescription_id')->nullable()->constrained();
            $table->foreignId('transfer_id')->nullable()->constrained();
            $table->dateTime('happened_at');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('transfer_items');
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('supplies');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('patients');
    }
};
