<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DB\DB;

class CreateTablePayer extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
	    DB::execute('
			CREATE TABLE payer (
				id                    VARCHAR(36) COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
			    email                 VARCHAR(256) NOT NULL,
			    identification_type   VARCHAR(8) NOT NULL,
			    identification_number VARCHAR(14) NOT NULL,
			    entity_type           VARCHAR(32) NOT NULL,
			    type                  VARCHAR(32) NOT NULL,
			    CONSTRAINT payer_pk UNIQUE (identification_number),
			    CONSTRAINT payer_pk2 UNIQUE (email)
	  		); 
		');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payer');
    }
}
