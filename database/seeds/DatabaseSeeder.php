<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("ALTER TABLE restaurantes AUTO_INCREMENT = 0;"); 
        DB::statement("ALTER TABLE categorias AUTO_INCREMENT = 0;"); 
        \App\restaurante::query()->delete();
        \App\administrador::query()->delete();        
        \App\categoria::query()->delete();        
        \App\plato::query()->delete();

        $this->call(restaurante1Seeder::class);
        $this->call(administrador1Seeder::class);
        $this->call(categoria1Seeder::class);
        $this->call(categoria2Seeder::class);
   
    }
}
