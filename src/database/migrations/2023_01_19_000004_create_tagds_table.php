<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tagd\Core\Models\Item\Tagd as Model;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
return new class extends Migration
{
    /**
     * Get table name
     *
     * @return string
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
            $table->foreignUuid('item_id')->constrained()->nullable();
            $table->foreignUuid('consumer_id')->constrained()->nullable();
            $table->string('slug');
            $table->datetime('activated_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
        });

        Schema::table($this->tableName(), function (Blueprint $table) {
            $table->after('slug', function ($table) {
                $table->foreignUuid('prev_id')->nullable()->references('id')->on('tagds');
                $table->foreignUuid('next_id')->nullable()->references('id')->on('tagds');
            });
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
