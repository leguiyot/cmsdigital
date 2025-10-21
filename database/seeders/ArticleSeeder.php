<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use App\Models\Section;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $sections = Section::all();

        if ($users->isEmpty() || $sections->isEmpty()) {
            $this->command->info('No hay usuarios o secciones. Ejecuta primero los otros seeders.');
            return;
        }

        $articles = [
            [
                'title' => 'El futuro de la inteligencia artificial en el periodismo digital',
                'slug' => 'futuro-inteligencia-artificial-periodismo-digital',
                'excerpt' => 'La IA está transformando la manera en que se produce y consume información en los medios digitales modernos.',
                'body' => '<p>La inteligencia artificial está revolucionando el mundo del periodismo digital de maneras que apenas estamos comenzando a comprender. Desde la automatización de tareas rutinarias hasta la personalización de contenido, la IA está redefiniendo lo que significa ser periodista en la era digital.</p><p>Los algoritmos de procesamiento de lenguaje natural ahora pueden generar resúmenes de eventos deportivos, informes financieros y incluso noticias de última hora con una velocidad y precisión impresionantes. Sin embargo, el papel del periodista humano sigue siendo crucial para proporcionar contexto, análisis y la perspectiva humana que ninguna máquina puede replicar.</p><p>Las redacciones modernas están adoptando herramientas de IA para optimizar sus flujos de trabajo, desde la verificación de hechos hasta la distribución de contenido en múltiples plataformas. Esta transformación no solo mejora la eficiencia, sino que también permite a los periodistas centrarse en lo que mejor saben hacer: contar historias que importan.</p>',
                'section_slug' => 'tecnologia',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Análisis: Las últimas reformas económicas y su impacto en el mercado',
                'slug' => 'analisis-reformas-economicas-impacto-mercado',
                'excerpt' => 'Un análisis profundo de las recientes medidas económicas implementadas por el gobierno y sus efectos en los sectores productivos.',
                'body' => '<p>Las reformas económicas anunciadas la semana pasada han generado un intenso debate entre economistas y empresarios. Estas medidas, que incluyen modificaciones en la política fiscal y nuevos incentivos para la inversión extranjera, prometen transformar el panorama económico del país.</p><p>Los sectores más beneficiados por estas reformas incluyen la tecnología, la manufactura y los servicios financieros. Los expertos estiman que estas medidas podrían generar un crecimiento del PIB del 3.5% en los próximos dos años, superando las expectativas iniciales.</p><p>Sin embargo, no todas las opiniones son favorables. Algunos economistas advierten sobre los riesgos de inflación y la necesidad de implementar medidas complementarias para garantizar que el crecimiento sea sostenible a largo plazo.</p>',
                'section_slug' => 'economia',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'La nueva generación de artistas digitales revoluciona el mundo del arte',
                'slug' => 'nueva-generacion-artistas-digitales-revoluciona-arte',
                'excerpt' => 'Jóvenes creadores están utilizando herramientas digitales para crear obras que desafían las definiciones tradicionales del arte.',
                'body' => '<p>El mundo del arte está experimentando una revolución silenciosa liderada por una nueva generación de artistas que han crecido en la era digital. Estos creadores utilizan tecnologías como la realidad virtual, la inteligencia artificial y el blockchain para crear obras que desafían las definiciones tradicionales del arte.</p><p>Las galerías tradicionales están comenzando a reconocer el valor de estas nuevas formas de expresión. Muchas han comenzado a dedicar espacios especiales para exhibiciones digitales, mientras que otras han creado plataformas virtuales completamente nuevas.</p><p>El arte digital no solo está cambiando lo que consideramos arte, sino también cómo lo experimentamos. Las obras interactivas permiten a los espectadores convertirse en participantes activos, creando una experiencia única para cada persona.</p>',
                'section_slug' => 'cultura',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Deportes: La temporada de fútbol promete grandes sorpresas',
                'slug' => 'deportes-temporada-futbol-grandes-sorpresas',
                'excerpt' => 'Los equipos se preparan para una temporada llena de emociones, con nuevos fichajes y estrategias renovadas.',
                'body' => '<p>La nueva temporada de fútbol está a punto de comenzar y las expectativas son altas. Los equipos han realizado importantes inversiones en nuevos jugadores y han renovado sus estrategias tácticas para enfrentar los desafíos que se avecinan.</p><p>Los fichajes más destacados de esta temporada incluyen jugadores internacionales de primer nivel que prometen elevar la calidad del juego local. Los entrenadores han expresado su optimismo sobre las posibilidades de crear un espectáculo deportivo memorable para los aficionados.</p><p>Las predicciones para esta temporada varían, pero los expertos coinciden en que será una de las más competitivas de los últimos años. La paridad entre los equipos principales sugiere que el título podría decidirse en las últimas jornadas.</p>',
                'section_slug' => 'deportes',
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Educación digital: El futuro del aprendizaje post-pandemia',
                'slug' => 'educacion-digital-futuro-aprendizaje-post-pandemia',
                'excerpt' => 'Las instituciones educativas han adoptado nuevas metodologías digitales que están transformando la experiencia de aprendizaje.',
                'body' => '<p>La pandemia aceleró la adopción de tecnologías educativas de una manera sin precedentes. Lo que comenzó como una medida de emergencia se ha convertido en una transformación permanente del sector educativo.</p><p>Las plataformas de aprendizaje en línea han evolucionado para ofrecer experiencias más interactivas y personalizadas. Los educadores han desarrollado nuevas metodologías que combinan lo mejor de la educación presencial y virtual.</p><p>Los estudiantes de hoy tienen acceso a recursos educativos de una calidad y variedad que era impensable hace apenas unos años. Esta democratización del conocimiento está creando oportunidades sin precedentes para el desarrollo personal y profesional.</p>',
                'section_slug' => 'sociedad',
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Análisis político: Los desafíos del nuevo gobierno municipal',
                'slug' => 'analisis-politico-desafios-nuevo-gobierno-municipal',
                'excerpt' => 'Las nuevas autoridades locales enfrentan importantes retos en infraestructura, seguridad y desarrollo económico.',
                'body' => '<p>El nuevo gobierno municipal ha asumido sus funciones con una agenda ambiciosa que incluye proyectos de infraestructura, mejoras en seguridad ciudadana y programas de desarrollo económico local.</p><p>Entre los principales desafíos destacan la modernización del transporte público, la implementación de nuevos sistemas de seguridad y la creación de espacios públicos que mejoren la calidad de vida de los ciudadanos.</p><p>Los primeros 100 días de gobierno serán cruciales para establecer las bases de estas iniciativas. La ciudadanía espera ver resultados concretos que justifiquen las promesas de campaña.</p>',
                'section_slug' => 'politica',
                'status' => 'published',
                'is_featured' => false,
            ],
        ];

        foreach ($articles as $articleData) {
            $section = $sections->firstWhere('slug', $articleData['section_slug']);
            $author = $users->random();

            if ($section) {
                // Calcular tiempo de lectura
                $wordCount = str_word_count(strip_tags($articleData['body']));
                $readingTime = ceil($wordCount / 200); // 200 palabras por minuto

                $article = Article::create([
                    'title' => $articleData['title'],
                    'slug' => $articleData['slug'],
                    'excerpt' => $articleData['excerpt'],
                    'body' => $articleData['body'],
                    'status' => $articleData['status'],
                    'published_at' => $articleData['status'] === 'published' ? now()->subDays(rand(1, 30)) : null,
                    'author_id' => $author->id,
                    'section_id' => $section->id,
                    'is_featured' => $articleData['is_featured'],
                    'views_count' => rand(50, 1000),
                    'reading_time' => $readingTime,
                ]);

                $this->command->info("Artículo creado: {$article->title}");
            }
        }
    }
}
