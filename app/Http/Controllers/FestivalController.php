<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FestivalController extends Controller
{
    /**
     * Constructor - Aplica middleware JWT a todas las rutas
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Datos maestros del Festival del Huaso de Olmué 2026
     */
    private function getFestivalData()
    {
        return [
            'nombre' => 'LV Festival del Huaso de Olmué 2026',
            'ubicacion' => 'Anfiteatro El Patagual, Olmué, Chile',
            'animadores' => ['María Luisa Godoy', 'Eduardo Fuentes'],
            'programacion' => [
                [
                    'fecha' => '2026-01-15',
                    'dia' => 'Jueves',
                    'artistas' => [
                        ['nombre' => 'Bafona', 'tipo' => 'Obertura'],
                        ['nombre' => 'Myriam Hernández', 'tipo' => 'Música'],
                        ['nombre' => 'Paul Vásquez "El Flaco"', 'tipo' => 'Humor'],
                        ['nombre' => 'Nicole', 'tipo' => 'Música']
                    ]
                ],
                [
                    'fecha' => '2026-01-16',
                    'dia' => 'Viernes',
                    'artistas' => [
                        ['nombre' => 'Los Patiperros y Hijos de Mariana de Osorio', 'tipo' => 'Obertura'],
                        ['nombre' => 'Luck Ra', 'tipo' => 'Música (Argentina)'],
                        ['nombre' => 'Erwin Padilla', 'tipo' => 'Humor'],
                        ['nombre' => 'Alanys Lagos y Toly Fu', 'tipo' => 'Música Urbano/Folclor']
                    ]
                ],
                [
                    'fecha' => '2026-01-17',
                    'dia' => 'Sábado',
                    'artistas' => [
                        ['nombre' => 'Los de San Pablo', 'tipo' => 'Obertura'],
                        ['nombre' => 'Américo', 'tipo' => 'Música'],
                        ['nombre' => 'León Murillo', 'tipo' => 'Humor'],
                        ['nombre' => 'Gepe', 'tipo' => 'Música']
                    ]
                ],
                [
                    'fecha' => '2026-01-18',
                    'dia' => 'Domingo',
                    'artistas' => [
                        ['nombre' => 'Silvanita y Los del Quincho y Campeones de Cueca', 'tipo' => 'Obertura'],
                        ['nombre' => 'Ráfaga', 'tipo' => 'Música'],
                        ['nombre' => 'Felipe Parra', 'tipo' => 'Humor (Imitación)'],
                        ['nombre' => 'Entremares', 'tipo' => 'Música']
                    ]
                ]
            ],
            'competencia_folclorica' => [
                ['titulo' => 'Misiones', 'interprete' => 'Martín Acertijo'],
                ['titulo' => 'Se enamoró la paloma', 'interprete' => 'María Teresa Lagos y Voces del Río'],
                ['titulo' => 'Vuelve a mi lado', 'interprete' => 'Huamancuri'],
                ['titulo' => 'La curandera María', 'interprete' => 'Los Mismos de Siempre'],
                ['titulo' => 'Me voy pa\' Chile', 'interprete' => 'Fernanda Riffo'],
                ['titulo' => 'Vamos juntos a Chiloé', 'interprete' => 'Los Palmeros de Rancagua'],
                ['titulo' => 'Cuando me voy pa\' la quinta', 'interprete' => 'Ignacio Hernández y Los de Chile'],
                ['titulo' => 'Diablo Miguel', 'interprete' => 'Jilatas']
            ],
            'jurado' => [
                'Carolina Urrejola',
                'Gonzalo Fouilloux',
                'Manuel Caro "Dunga"',
                'Pablo Flamm',
                'Wladimir Campos'
            ]
        ];
    }

    /**
     * Retorna la parrilla completa del festival
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->getFestivalData()
        ], 200);
    }

    /**
     * Retorna solo la información de la competencia
     */
    public function competencia()
    {
        $data = $this->getFestivalData();
        return response()->json([
            'success' => true,
            'data' => $data['competencia_folclorica']
        ], 200);
    }
}
