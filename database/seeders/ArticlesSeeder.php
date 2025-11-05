<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Str;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las secciones
        $sections = Section::all();
        
        // Obtener el usuario administrador
        $adminUser = User::first();
        
        if (!$adminUser) {
            $this->command->error('No se encontró un usuario. Ejecuta primero AdminUserSeeder.');
            return;
        }

        $articles = [
            // Economía
            [
                'title' => 'Nueva reforma educativa aprobada por el gobierno',
                'excerpt' => 'El parlamento aprueba una amplia reforma del sistema educativo que afectará a todos los niveles de enseñanza, desde primaria hasta universitaria.',
                'body' => 'El gobierno ha aprobado una reforma educativa integral que transformará el sistema de enseñanza en los próximos años. La nueva legislación incluye cambios significativos en el currículo, métodos de evaluación y formación docente.

Entre las principales medidas se encuentra la implementación de nuevas tecnologías en el aula, la reducción del número de estudiantes por clase y una mayor inversión en infraestructura educativa.

Los sindicatos de profesores han mostrado su apoyo a la mayoría de las medidas, aunque expresan preocupación por los plazos de implementación. El ministro de Educación aseguró que se proporcionará toda la formación necesaria para que los docentes puedan adaptarse a los nuevos métodos.

La reforma entrará en vigor de manera gradual a partir del próximo curso académico, comenzando por los centros piloto seleccionados en diferentes regiones del país.',
                'section' => 'Economía',
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['educación', 'gobierno', 'reforma', 'política']
            ],
            
            // Sociedad
            [
                'title' => 'Inteligencia Artificial revoluciona el sector sanitario',
                'excerpt' => 'Los nuevos sistemas de IA están mejorando significativamente el diagnóstico médico y la atención al paciente en hospitales de todo el mundo.',
                'body' => 'La inteligencia artificial está transformando la medicina moderna de maneras que eran impensables hace una década. Los algoritmos de aprendizaje automático ahora pueden diagnosticar enfermedades con una precisión que rivaliza con la de los especialistas humanos.

En radiología, los sistemas de IA pueden detectar tumores en imágenes médicas con una tasa de precisión del 94%, superando en algunos casos la capacidad humana. Esto no solo mejora los resultados para los pacientes, sino que también reduce significativamente los tiempos de diagnóstico.

Los hospitales que han implementado estas tecnologías reportan una reducción del 30% en errores de diagnóstico y una mejora del 25% en la eficiencia del tratamiento. Sin embargo, los expertos enfatizan que la IA complementa, no reemplaza, el juicio clínico humano.

La implementación de estos sistemas requiere una inversión considerable en tecnología y formación, pero los beneficios a largo plazo para el sistema sanitario son evidentes.',
                'section' => 'Sociedad',
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['inteligencia artificial', 'medicina', 'tecnología', 'salud']
            ],
            
            // Deportes
            [
                'title' => 'Campeonato mundial de fútbol: España clasifica a semifinales',
                'excerpt' => 'La selección española consigue una victoria histórica ante Brasil y se acerca al título mundial tras 12 años de espera.',
                'body' => 'España ha conseguido una de las victorias más importantes de su historia futbolística al derrotar a Brasil por 2-1 en los cuartos de final del campeonato mundial. Este triunfo les asegura un puesto en las semifinales del torneo.

El partido, disputado en un ambiente eléctrico, vio a España tomar la delantera en el minuto 23 con un gol de su estrella mediocampista. Brasil empató antes del descanso, pero España selló la victoria con un gol en los últimos minutos del encuentro.

El seleccionador español elogió la mentalidad de su equipo: "Hemos demostrado que podemos competir con las mejores selecciones del mundo. El trabajo de estos años está dando sus frutos."

España se enfrentará ahora a Francia en las semifinales, un duelo que promete ser uno de los mejores partidos del torneo. Los aficionados españoles ya se preparan para lo que podría ser el regreso de España a una final mundial.',
                'section' => 'Deportes',
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['fútbol', 'mundial', 'España', 'deportes']
            ],
            
            // Entretenimiento
            [
                'title' => 'Nueva exposición en el Museo Nacional celebra el arte contemporáneo',
                'excerpt' => 'Una muestra única reúne obras de los artistas más influyentes de las últimas décadas, explorando temas como la tecnología y la globalización.',
                'body' => 'El Museo Nacional inaugura esta semana "Voces del Siglo XXI", una exposición que reúne obras de más de 50 artistas contemporáneos de todo el mundo. La muestra explora cómo el arte ha evolucionado en respuesta a los cambios tecnológicos y sociales de las últimas décadas.

Entre las obras destacadas se encuentra una instalación interactiva que utiliza realidad aumentada para crear una experiencia inmersiva única. Los visitantes pueden interactuar con las obras a través de dispositivos móviles, creando una nueva forma de experimentar el arte.

La curadora de la exposición explicó: "Queremos mostrar cómo los artistas contemporáneos están redefiniendo los límites tradicionales del arte, incorporando nuevas tecnologías y abordando temas globales como el cambio climático y la diversidad cultural."

La exposición estará abierta al público durante seis meses y se espera que atraiga a más de 200,000 visitantes. Se han organizado talleres y conferencias para complementar la experiencia.',
                'section' => 'Entretenimiento',
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['arte', 'exposición', 'cultura', 'museo']
            ],
            
            // Política Nacional
            [
                'title' => 'El mercado bursátil registra ganancias históricas este trimestre',
                'excerpt' => 'Los principales índices bursátiles alcanzan máximos históricos impulsados por el crecimiento del sector tecnológico y las políticas económicas favorables.',
                'body' => 'Los mercados financieros han cerrado el trimestre con ganancias récord, superando todas las expectativas de los analistas. El índice principal ha subido un 15% en los últimos tres meses, marcando el mejor trimestre en cinco años.

El sector tecnológico ha liderado las ganancias, con empresas de software y semiconductores registrando aumentos de hasta el 30%. Los inversores muestran confianza en las perspectivas futuras de estos sectores.

Los expertos atribuyen este crecimiento a varios factores: la estabilidad de las políticas monetarias, el aumento del consumo y la recuperación de los sectores más afectados por crisis anteriores.

Sin embargo, algunos analistas advierten sobre la posibilidad de una corrección en los próximos meses y recomiendan cautela a los inversores. "Es importante recordar que los mercados son cíclicos", comenta un economista senior.

Las proyecciones para el próximo trimestre se mantienen optimistas, aunque con expectativas más moderadas.',
                'section' => 'Política Nacional',
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['bolsa', 'economía', 'inversión', 'mercados']
            ],
            
            // Internacionales
            [
                'title' => 'El futuro del trabajo remoto: desafíos y oportunidades',
                'excerpt' => 'Análisis sobre cómo el trabajo a distancia está transformando las empresas y qué medidas deben tomar para adaptarse a esta nueva realidad.',
                'body' => 'El trabajo remoto ha dejado de ser una excepción para convertirse en la norma para millones de profesionales en todo el mundo. Esta transformación plantea importantes preguntas sobre el futuro del trabajo y la organización empresarial.

Las ventajas son evidentes: mayor flexibilidad para los empleados, reducción de costos operativos para las empresas y acceso a talento global. Sin embargo, también surgen desafíos significativos relacionados con la cultura empresarial y la colaboración.

Las empresas que han tenido éxito en esta transición han invertido en tecnología adecuada y han redefinido sus procesos de comunicación. La clave está en mantener la productividad sin sacrificar la conexión humana.

Mirando hacia el futuro, es probable que veamos un modelo híbrido como estándar, donde los empleados alternen entre trabajo presencial y remoto. Esto requerirá nuevas habilidades de gestión y una mentalidad empresarial diferente.

La pregunta no es si el trabajo remoto llegó para quedarse, sino cómo podemos aprovecharlo al máximo manteniendo los valores que hacen exitosas a las organizaciones.',
                'section' => 'Internacionales',
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['trabajo remoto', 'futuro', 'empresas', 'internacional']
            ],
            
            // Política Local
            [
                'title' => 'Nuevas medidas para mejorar el transporte público local',
                'excerpt' => 'El ayuntamiento anuncia inversiones en infraestructura y tecnología para modernizar el sistema de transporte urbano.',
                'body' => 'El gobierno local ha presentado un plan integral para mejorar el transporte público que incluye la renovación de la flota de autobuses, la implementación de nuevas rutas y la digitalización del sistema de pago.

El proyecto, con una inversión de 50 millones de euros, contempla la incorporación de 100 nuevos autobuses eléctricos que reemplazarán gradualmente a los vehículos más antiguos. Esto reducirá significativamente las emisiones de carbono del transporte público.

Además, se implementará un sistema de pago digital que permitirá a los usuarios utilizar sus teléfonos móviles o tarjetas contactless para acceder al transporte. El nuevo sistema también incluirá una aplicación móvil con información en tiempo real sobre horarios y rutas.

El alcalde destacó que estas mejoras responden a las demandas ciudadanas por un transporte más eficiente y sostenible. "Queremos que el transporte público sea la primera opción para nuestros ciudadanos", declaró en rueda de prensa.',
                'section' => 'Política Local',
                'status' => 'published',
                'is_featured' => false,
                'tags' => ['transporte', 'política local', 'sostenibilidad', 'tecnología']
            ],
            
            // Valle de Uco
            [
                'title' => 'Temporada de vendimia registra excelentes resultados en Valle de Uco',
                'excerpt' => 'Los productores locales celebran una cosecha excepcional que promete vinos de alta calidad para la próxima temporada.',
                'body' => 'La temporada de vendimia 2024 en Valle de Uco ha concluido con resultados extraordinarios, registrando una de las mejores cosechas de los últimos años tanto en cantidad como en calidad de uva.

Las condiciones climáticas favorables durante todo el ciclo vegetativo, con un invierno frío y un verano con temperaturas moderadas, han permitido una maduración óptima de las uvas. Los productores destacan especialmente la calidad de las variedades Malbec y Cabernet Sauvignon.

El presidente de la Asociación de Viticultores del Valle de Uco comentó: "Esta vendimia confirma el potencial único de nuestro terroir. La combinación de altitud, clima y suelos nos permite producir vinos de clase mundial."

Los enólogos predicen que los vinos de esta cosecha tendrán características excepcionales, con gran potencial de guarda y complejidad aromática. Se espera que estos vinos refuercen la reputación internacional del Valle de Uco como una de las regiones vitivinícolas más prestigiosas.',
                'section' => 'Valle de Uco',
                'status' => 'published',
                'is_featured' => true,
                'tags' => ['vendimia', 'valle de uco', 'vino', 'agricultura']
            ]
        ];

        foreach ($articles as $articleData) {
            // Buscar la sección por nombre
            $section = $sections->where('name', $articleData['section'])->first();
            
            if (!$section) {
                $this->command->warn("Sección '{$articleData['section']}' no encontrada. Saltando artículo: {$articleData['title']}");
                continue;
            }

            // Calcular tiempo de lectura
            $wordCount = str_word_count(strip_tags($articleData['body']));
            $readingTime = max(1, ceil($wordCount / 200));

            Article::create([
                'title' => $articleData['title'],
                'slug' => Str::slug($articleData['title']),
                'excerpt' => $articleData['excerpt'],
                'body' => $articleData['body'],
                'section_id' => $section->id,
                'author_id' => $adminUser->id,
                'status' => $articleData['status'],
                'published_at' => $articleData['status'] === 'published' ? now()->subDays(rand(1, 30)) : null,
                'is_featured' => $articleData['is_featured'],
                'allow_comments' => true,
                'reading_time' => $readingTime,
                'views_count' => rand(50, 1000),
                'tags' => $articleData['tags'],
                'seo_title' => $articleData['title'],
                'meta_description' => $articleData['excerpt'],
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now()->subDays(rand(0, 10))
            ]);
        }

        $this->command->info('Se han creado ' . count($articles) . ' artículos de ejemplo.');
    }
}
