<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->text('domain')->nullable();
            $table->text('tld')->nullable();
            $table->text('domain_created_date')->nullable();
            $table->text('domain_expiry_date')->nullable();
            $table->text('domain_registrar')->nullable();
            $table->text('domain_registrar_url')->nullable();
            $table->text('domain_whois_server')->nullable();
            $table->timestamp('domain_last_scrape_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
    }
};
