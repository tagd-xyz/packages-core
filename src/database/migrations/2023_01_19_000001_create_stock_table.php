<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tagd\Core\Models\Item\Stock as Model;

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
            $table->unsignedBigInteger('type_id');
            $table->text('description')->nullable();
            $table->json('properties');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table($this->tableName(), function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('item_types');
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
