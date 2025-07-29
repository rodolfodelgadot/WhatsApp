<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnType extends Migration
{
	public function up(): void
	{
		Schema::table ( 'whatsapp_gateway', function (Blueprint $table) {
			 $table->bigInteger('sender')->change();
		} );
	}
}
