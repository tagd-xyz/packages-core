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
            $table->foreignUuid('item_id')->nullable()->constrained();
            $table->foreignUuid('consumer_id')->nullable()->constrained();
            $table->foreignUuid('reseller_id')->nullable()->constrained();
            $table->string('slug');
            $table->json('meta')->nullable();
            $table->json('stats')->nullable();
            $table->string('status')->nullable();
            $table->datetime('status_at')->nullable();
            // $table->datetime('expired_at')->nullable();
            // $table->datetime('cancelled_at')->nullable();
            // $table->datetime('transferred_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
        });

        Schema::table($this->tableName(), function (Blueprint $table) {
            $table->after('slug', function ($table) {
                $table->foreignUuid('parent_id')->nullable()->references('id')->on('tagds');
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
