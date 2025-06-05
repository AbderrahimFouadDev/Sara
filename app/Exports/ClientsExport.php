<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Client::all();
    }

    public function headings(): array
    {
        return [
            'Date de création',
            'Nom du client',
            'Email',
            'Téléphone',
            'Balance',
            'Statut',
            'Adresse',
            'Notes'
        ];
    }

    public function map($client): array
    {
        return [
            $client->created_at->format('d/m/Y'),
            $client->client_name,
            $client->email,
            $client->telephone,
            $client->balance,
            $client->client_actif ? 'Actif' : 'Inactif',
            $client->adresse,
            $client->notes
        ];
    }
} 