<?php

namespace Database\Seeders;

use App\Models\Expertise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpertisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            'Agriculture; plantations; other rural sectors',
            'Basic Metal Production',
            'Chemical industries',
            'Commerce',
            'Construction',
            'Education',
            'Financial services; professional services',
            'Food; drink; tobacco',
            'Forestry; wood; pulp and paper',
            'Health services',
            'Hotels; tourism; catering',
            'Mining (coal; other mining)',
            'Mechanical and electrical engineering',
            'Media; culture; graphical',
            'Oil and gas production; oil refining',
            'Postal and telecommunications services',
            'Public service',
            'Shipping; ports; fisheries; inland waterways',
            'Textiles; clothing; leather; footwear',
            'Transport (including civil aviation; railways; road transport)',
            'Transport equipment manufacturing',
            'Utilities (water; gas; electricity)',
        ];

        foreach ($fields as $field) {
            Expertise::create([
                'field' => $field,
            ]);
        }
    }
}
