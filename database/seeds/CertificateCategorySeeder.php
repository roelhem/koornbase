<?php

use Illuminate\Database\Seeder;

use App\CertificateCategory;

class CertificateCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       CertificateCategory::create([
           'name' => 'Instructie Verantwoordelijk Alcoholschenken',
           'name_short' => 'IVA',
           'description' => 'Certificaat die verplicht is om alcohol te schenken.'
       ]);

       CertificateCategory::create([
           'name' => 'Rijbewijs'
       ]);

       CertificateCategory::create([
           'name' => 'Eerste Hulp Bij Ongeluk',
           'name_short' => 'EHBO',
           'default_expire_years' => 4
       ]);
    }
}
