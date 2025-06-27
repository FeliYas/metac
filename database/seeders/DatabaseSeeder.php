<?php

namespace Database\Seeders;

use App\Models\Calidad;
use App\Models\Contacto;
use App\Models\Contenido;
use App\Models\Internacional;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Nosotros;
use App\Models\Tarjeta;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios
        User::create([
            'name' => 'Pablo',
            'email' => 'pablo@osole.com',
            'password' => Hash::make('pablopablo'),
            'role' => 'admin',
        ]);


        // Crear logos para las secciones
        $logoSecciones = ['login', 'dashboard', 'footer', 'navbar', 'home'];
        foreach ($logoSecciones as $seccion) {
            Logo::create([
                'path' => "logos/{$seccion}-logo.png",
                'seccion' => $seccion,
            ]);
        }

        // Crear metadatos para las secciones
        $metadatos = [
            [
                'seccion' => 'home',
                'keyword' => 'metac, inicio, productos plásticos',
                'descripcion' => 'Página principal de Metac - Empresa líder en productos plásticos de alta calidad'
            ],
            [
                'seccion' => 'nosotros',
                'keyword' => 'empresa, historia, metac, quienes somos',
                'descripcion' => 'Conoce más sobre Metac, nuestra historia y compromiso con la calidad'
            ],
            [
                'seccion' => 'productos',
                'keyword' => 'productos plásticos, catálogo, metac',
                'descripcion' => 'Catálogo completo de productos plásticos de alta calidad de Metac'
            ],
            [
                'seccion' => 'industrias',
                'keyword' => 'industrias, sectores, aplicaciones, metac, soluciones plásticas',
                'descripcion' => 'Descubre las industrias y sectores donde Metac ofrece soluciones plásticas innovadoras y de alta calidad.'
            ],
            [
                'seccion' => 'calidad',
                'keyword' => 'calidad, certificaciones, control, metac, estándares',
                'descripcion' => 'Conoce el compromiso de Metac con la calidad, nuestros procesos de control y certificaciones que garantizan productos superiores.'
            ],
            [
                'seccion' => 'novedades',
                'keyword' => 'noticias, novedades, actualizaciones metac',
                'descripcion' => 'Últimas noticias y novedades de Metac'
            ],
            [
                'seccion' => 'contacto',
                'keyword' => 'contacto, ubicación, teléfono, email metac',
                'descripcion' => 'Información de contacto y ubicación de Metac'
            ]
        ];
        foreach ($metadatos as $metadato) {
            Metadato::create($metadato);
        }

        // Crear datos de ejemplo para nosotros
        Nosotros::create([
            'path' => 'images/nosotros-banner.jpg',
            'titulo' => 'Sobre Metac',
            'descripcion' => 'Somos una empresa líder en la fabricación de productos plásticos de alta calidad, comprometidos con la innovación y la excelencia.',
            'path2' => 'images/nosotros-banner.jpg',
            'titulo2' => 'Sobre Metac',
            'descripcion2' => 'Somos una empresa líder en la fabricación de productos plásticos de alta calidad, comprometidos con la innovación y la excelencia.',
        ]);
        // Crear tarjetas de ejemplo
        $tarjetas = [
            [
                'path' => 'images/tarjeta-productos.jpg',
                'titulo' => 'Productos de Calidad',
                'descripcion' => 'Fabricamos productos plásticos con los más altos estándares de calidad y durabilidad.'
            ],
            [
                'path' => 'images/tarjeta-innovacion.jpg',
                'titulo' => 'Innovación Constante',
                'descripcion' => 'Invertimos en tecnología de punta para ofrecer soluciones innovadoras a nuestros clientes.'
            ],
            [
                'path' => 'images/tarjeta-servicio.jpg',
                'titulo' => 'Servicio Excepcional',
                'descripcion' => 'Nuestro equipo está comprometido con brindar el mejor servicio al cliente.'
            ]
        ];

        foreach ($tarjetas as $tarjeta) {
            Tarjeta::create($tarjeta);
        }

        // Crear contenido de ejemplo
        Contenido::create([
            'path' => 'images/contenido-home-banner.jpg',
            'titulo' => 'Bienvenidos a Metac',
            'descripcion' => 'Tu socio confiable en soluciones plásticas de alta calidad para todas las industrias.'
        ]);

        Contenido::create([
            'path' => 'images/contenido-secundario.jpg',
            'logo' => 'images/contenido-secundario.jpg',
            'titulo' => 'Compromiso con la Innovación',
        ]);
        
        Calidad::create([
            'path' => 'images/calidad-home-banner.jpg',
            'titulo' => 'Bienvenidos a Metac',
            'descripcion' => 'Tu socio confiable en soluciones plásticas de alta calidad para todas las industrias.'
        ]);
    }
}
