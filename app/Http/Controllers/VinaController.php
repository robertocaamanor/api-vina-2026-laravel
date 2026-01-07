<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private $festivalData = [
        "nombre" => "Festival Internacional de la Canción de Viña del Mar",
        "edicion" => "LXV (65ª)",
        "animadores" => ["Karen Doggenweiler", "Rafael Araneda"],
        "programacion" => [
            [
                "dia" => "Domingo", 
                "fecha" => "2026-02-22", 
                "artistas" => [
                    ['nombre' => 'Gloria Estefan', 'tipo' => 'Música'],
                    ['nombre' => 'Matteo Bocelli', 'tipo' => 'Música'],
                    ['nombre' => 'Stefan Kramer', 'tipo' => 'Humor']
                ]
            ],
            [
                "dia" => "Lunes", 
                "fecha" => "2026-02-23", 
                "artistas" => [
                    ['nombre' => 'Pet Shop Boys', 'tipo' => 'Música'],
                    ['nombre' => 'Bomba Estéreo', 'tipo' => 'Música'],
                    ['nombre' => 'Rodrigo Villegas', 'tipo' => 'Humor']
                ]
            ],
            [
                "dia" => "Martes", 
                "fecha" => "2026-02-24", 
                "artistas" => [
                    ['nombre' => 'Jesse & Joy', 'tipo' => 'Música'],
                    ['nombre' => 'NMIXX', 'tipo' => 'Música'],
                    ['nombre' => 'Esteban Düch', 'tipo' => 'Humor']
                ]
            ],
            [
                "dia" => "Miércoles", 
                "fecha" => "2026-02-25", 
                "artistas" => [
                    ['nombre' => 'Juanes', 'tipo' => 'Música'],
                    ['nombre' => 'Ke Personajes', 'tipo' => 'Música'],
                    ['nombre' => 'Asskha Sumathra', 'tipo' => 'Humor']
                ]
            ],
            [
                "dia" => "Jueves", 
                "fecha" => "2026-02-26", 
                "artistas" => [
                    ['nombre' => 'Mon Laferte', 'tipo' => 'Música'],
                    ['nombre' => 'Yandel Sinfónico', 'tipo' => 'Música'],
                    ['nombre' => 'Piare con Pe', 'tipo' => 'Humor']
                ]
            ],
            [
                "dia" => "Viernes", 
                "fecha" => "2026-02-27", 
                "artistas" => [
                    ['nombre' => 'Paulo Londra', 'tipo' => 'Música'],
                    ['nombre' => 'Pablo Chill-E', 'tipo' => 'Música'],
                    ['nombre' => 'Milo J', 'tipo' => 'Música'],
                    ['nombre' => 'Pastor Rocha', 'tipo' => 'Humor']
                ]
            ]
        ],
        "competencia_folclorica" => [
            ["pais" => "Argentina", "artista" => "Campedrinos", "cancion" => "La Zamba"],
            ["pais" => "Chile", "artista" => "A Los 4 Vientos", "cancion" => "Valoración"],
            ["pais" => "Colombia", "artista" => "Rebolú", "cancion" => "Los Herederos"],
            ["pais" => "Ecuador", "artista" => "Brenda", "cancion" => "Capullito"],
            ["pais" => "México", "artista" => "Majo Cornejo", "cancion" => "Ningún Color Tiene Dueño"],
            ["pais" => "España", "artista" => "María Peláe", "cancion" => "Que Vengan A Por Mi"]
        ],
        "competencia_internacional" => [
            ["pais" => "Estonia", "artista" => "Vanilla Ninja", "cancion" => "Ready To Go"],
            ["pais" => "España", "artista" => "Antoñito Molina", "cancion" => "Me Prometo"],
            ["pais" => "Italia", "artista" => "Chiara Grispo", "cancion" => "Grazie A(d)dio"],
            ["pais" => "Chile", "artista" => "Son Del Valle", "cancion" => "El Ciclo"],
            ["pais" => "República Dominicana", "artista" => "Johnny Sky", "cancion" => "Call On Me"],
            ["pais" => "México", "artista" => "Trex", "cancion" => "La Ruta Correcta"]
        ]
    ];

    public function getParrilla()
    {
        return response()->json([
            'success' => true,
            'data' => $this->festivalData
        ], 200);
    }

    public function getDia($dia)
    {
        $resultado = collect($this->festivalData['programacion'])->first(function ($item) use ($dia) {
            return strtolower($item['dia']) === strtolower($dia);
        });

        if (!$resultado) {
            return response()->json([
                'success' => false,
                'error' => 'Día no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resultado
        ], 200);
    }

    public function competenciaFolclorica()
    {
        return response()->json([
            'success' => true,
            'data' => $this->festivalData['competencia_folclorica']
        ], 200);
    }

    public function competenciaInternacional()
    {
        return response()->json([
            'success' => true,
            'data' => $this->festivalData['competencia_internacional']
        ], 200);
    }
}
