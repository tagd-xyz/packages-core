<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tagd\Core\Models\Item\Item as Model;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
return new class extends Migration
{
    /**
     * Get table name
     */
    private function tableName(): string
    {
        return (new Model())->getTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('retailer_id')->constrained();
            $table->string('name');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->json('properties');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName());
    }
};
