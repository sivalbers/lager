<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lagerort;

class LagerorteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nr' => 1000, 'bezeichnung' => 'Lager Ecopark'],
            ['nr' => 2000, 'bezeichnung' => 'Lager Werneuchen'],
            ['nr' => 2010, 'bezeichnung' => 'Externer Lagerort'],
            ['nr' => 2020, 'bezeichnung' => 'WE-Reklamation'],
            ['nr' => 2030, 'bezeichnung' => 'WA-Reklamation'],
            ['nr' => 2040, 'bezeichnung' => 'Mat. beim Hersteller'],
            ['nr' => 2050, 'bezeichnung' => 'QM zerstör. Prüf Werneuchen'],
            ['nr' => 2099, 'bezeichnung' => 'Differenzen EWM Werneuchen'],
            ['nr' => 3000, 'bezeichnung' => 'Lager Bremen'],
            ['nr' => 3010, 'bezeichnung' => 'externer Lagerort'],
            ['nr' => 3020, 'bezeichnung' => 'WE-Reklamation'],
            ['nr' => 3030, 'bezeichnung' => 'WA-Reklamation'],
            ['nr' => 3040, 'bezeichnung' => 'Mat. beim Hersteller'],
            ['nr' => 3050, 'bezeichnung' => 'zerstörte WE QM Prüfung'],
            ['nr' => 3098, 'bezeichnung' => 'Ersatzteile Automatiklager Bremen'],
            ['nr' => 3099, 'bezeichnung' => 'Differenzen EWM Bremen'],
            ['nr' => 3100, 'bezeichnung' => 'Zählerlager BHV'],
            ['nr' => 3350, 'bezeichnung' => 'Metropolpark Wesernetz'],
            ['nr' => 4000, 'bezeichnung' => 'Lager Bloh'],
            ['nr' => 4010, 'bezeichnung' => 'externer Lagerort'],
            ['nr' => 4020, 'bezeichnung' => 'WE-Reklamation'],
            ['nr' => 4030, 'bezeichnung' => 'WA-Reklamation'],
            ['nr' => 4040, 'bezeichnung' => 'Mat. beim Hersteller'],
            ['nr' => 4050, 'bezeichnung' => 'zerstörte WE QM Prüfung'],
            ['nr' => 4099, 'bezeichnung' => 'Differenzen EWM Bloh'],
            ['nr' => 4100, 'bezeichnung' => 'Prüfstelle GNI 9'],
            ['nr' => 4200, 'bezeichnung' => 'Weißenmoorstr'],
            ['nr' => 4350, 'bezeichnung' => 'Metropolpark EWE NETZ GmbH'],
            ['nr' => 4400, 'bezeichnung' => 'Bürgerparkst. CLP'],
            ['nr' => 4500, 'bezeichnung' => 'Am Gaswerkgr. HB'],
            ['nr' => 4600, 'bezeichnung' => 'Werneuchen (AS-HL)'],
        ];

        foreach ($data as $entry) {
            Lagerort::updateOrCreate(['nr' => $entry['nr']], $entry);
        }
    }
}
