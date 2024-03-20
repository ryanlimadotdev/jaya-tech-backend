<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DB\DB;

class CreateTablePayment extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		DB::execute('
			CREATE TABLE payments (
				id                 VARCHAR(36) NOT NULL PRIMARY KEY,
				transaction_amount DECIMAL(10, 2) NOT NULL,
				installments       INT NOT NULL,
				token              VARCHAR(36) NOT NULL,
				payment_method_id  VARCHAR(36) NOT NULL,
				notification_url   VARCHAR(2048) NOT NULL,
				status             VARCHAR(32) NULL,
				created_at         DATETIME NOT NULL,
				updated_at         DATETIME NOT NULL,
				payer_id           VARCHAR(36) NOT NULL,
				CONSTRAINT payments_payer_id_fk FOREIGN KEY (payer_id) REFERENCES payer(id)
			) COLLATE = utf8mb4_unicode_ci; 
		');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
}
