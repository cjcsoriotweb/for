<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\ToolController;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');





Route::get('/openapi.json', function () {
    return response()->json([
        "openapi" => "3.0.3",
        "info" => [
            "title" => "Laravel Tool Server (no-auth)",
            "version" => "1.1.0",
            "description" => "API de démonstration pour Open WebUI (sans authentification)"
        ],
        "servers" => [
            [ "url" => "http://localhost:8000/api" ]
        ],
        "paths" => [
            "/users" => [
                "get" => [
                    "summary" => "Lister les utilisateurs",
                    "operationId" => "listUsers",
                    "responses" => [
                        "200" => [
                            "description" => "Liste d'utilisateurs",
                            "content" => [
                                "application/json" => [
                                    "schema" => [
                                        "type" => "array",
                                        "items" => [
                                            "type" => "object",
                                            "properties" => [
                                                "id" => [ "type" => "integer" ],
                                                "name" => [ "type" => "string" ],
                                                "email" => [ "type" => "string", "format" => "email" ]
                                            ],
                                            "required" => ["id", "name", "email"]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
                                        ],
            "/formations" => [
                "get" => [
                    "summary" => "Lister les formations",
                    "operationId" => "listFormations",
                    "responses" => [
                        "200" => [
                            "description" => "Liste de formations",
                            "content" => [
                                "application/json" => [
                                    "schema" => [
                                        "type" => "array",
                                        "items" => [
                                            "type" => "object",
                                            "properties" => [
                                                "title" => [ "type" => "string" ],
                                                "description" => [ "type" => "string" ],
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]);
});




Route::get('/users', function () {
    // Renvoie uniquement des champs non sensibles
    return User::query()
        ->select(['id', 'name', 'email'])
        ->orderBy('id')
        ->get();
});

Route::get('/formations', function () {
    return Formation::get();
});