<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VinaController extends Controller
{
    private array $festivalData = [
        "nombre" => "Festival Internacional de la Canción de Viña del Mar",
        "edicion" => "LXV (65ª)",
        "animadores" => ["Karen Doggenweiler", "Rafael Araneda"],
        "programacion" => [
            ["dia" => "Domingo", "fecha" => "22 de febrero", "cantantes" => ["Gloria Estefan", "Matteo Bocelli"], "humorista" => "Stefan Kramer"],
            ["dia" => "Lunes", "fecha" => "23 de febrero", "cantantes" => ["Pet Shop Boys", "Bomba Estéreo"], "humorista" => "Rodrigo Villegas"],
            ["dia" => "Martes", "fecha" => "24 de febrero", "cantantes" => ["Jesse & Joy", "NMIXX"], "humorista" => "Esteban Düch"],
            ["dia" => "Miércoles", "fecha" => "25 de febrero", "cantantes" => ["Juanes", "Ke Personajes"], "humorista" => "Asskha Sumathra"],
            ["dia" => "Jueves", "fecha" => "26 de febrero", "cantantes" => ["Mon Laferte", "Yandel Sinfónico"], "humorista" => "Piare con Pe"],
            ["dia" => "Viernes", "fecha" => "27 de febrero", "cantantes" => ["Paulo Londra", "Pablo Chill-E", "Milo J"], "humorista" => "Pastor Rocha"],
        ],
        "competencia_folclorica" => [
            ["pais" => "Chile", "artista" => "A Los 4 Vientos", "cancion" => "Valoración"],
            ["pais" => "Argentina", "artista" => "Campedrinos", "cancion" => "La Zamba"],
        ],
        "competencia_internacional" => [
            ["pais" => "España", "artista" => "Antoñito Molina", "cancion" => "Me Prometo"],
            ["pais" => "Italia", "artista" => "Chiara Grispo", "cancion" => "Grazie A(d)dio"],
        ],
    ];

    public function getParrilla()
    {
        return response()->json($this->festivalData);
    }

    public function getDia($dia)
    {
        $resultado = collect($this->festivalData['programacion'])->first(function ($item) use ($dia) {
            return strtolower($item['dia']) === strtolower($dia);
        });

        if (!$resultado) {
            return response()->json(['error' => 'Día no encontrado'], 404);
        }

        return response()->json($resultado);
    }
}
