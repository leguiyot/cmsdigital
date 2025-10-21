# Agente de Desarrollo para Aplicación de CMS oara diario Digital en Laravel

Este documento define la estructura, módulos y pautas para el desarrollo de un sistema de gestión de contenidos (CMS) para un diario digital de alto prestigio, utilizando Laravel como framework principal. El agente responsable combina experiencia técnica con una visión estratégica en branding, marketing y comunicación digital.
### 1. Visión General del Proyecto
El objetivo es construir una plataforma editorial robusta, escalable y visualmente coherente con la identidad del medio. El CMS permitirá gestionar publicaciones, autores, secciones, medios multimedia, campañas promocionales y posicionamiento SEO, garantizando una experiencia fluida tanto para el equipo editorial como para los lectores.
Tecnologías Principales:
- Backend: PHP 8.1+ / Laravel 10+
- Frontend: Blade con Alpine.js o Vue.js (opcional).
- Framework CSS: Tailwind CSS o Bootstrap 5.
- Base de Datos: MySQL o PostgreSQL.
- SEO y Analytics: Integración con Google Analytics, Open Graph, y Schema.org.

### 2. Definición de Módulos

### Módulo 1: Gestión de Artículos
- Descripción: CRUD completo para artículos periodísticos.
- Modelo: Article (title, slug, excerpt, body, status, published_at, author_id, section_id, cover_image, tags, seo_title, meta_description).
- Estados: Borrador, Revisión, Publicado, Archivado.
- Controlador: ArticleController con lógica para flujos editoriales.
- Funcionalidad Clave:
- Editor enriquecido (WYSIWYG) con soporte para imágenes, videos y bloques embebidos.
- Programación de publicaciones.
- Vista previa en tiempo real.
- Optimización SEO por artículo.
### Módulo 2: Gestión de Autores y Roles Editoriales
- Descripción: Administración de periodistas, editores y colaboradores.
- Modelo: Extensión del modelo User con campos como bio, avatar, social_links, role.
- Roles Sugeridos:
- Redactor: Puede crear y editar sus artículos.
- Editor: Puede revisar, aprobar y publicar artículos.
- Administrador: Acceso completo al sistema.
- Funcionalidad Clave: Historial de publicaciones por autor y asignación de artículos.
### Módulo 3: Secciones y Categorías
- Descripción: Organización jerárquica del contenido.
- Modelo: Section (name, slug, description, parent_id, order).
- Funcionalidad Clave:
- Navegación dinámica basada en secciones.
- Filtros por categoría en frontend.
- Posibilidad de destacar secciones en portada.
### Módulo 4: Biblioteca Multimedia
- Descripción: Repositorio centralizado de imágenes, videos y documentos.
- Modelo: Media (file_path, type, alt_text, caption, uploaded_by).
- Funcionalidad Clave:
- Subida múltiple con compresión automática.
- Etiquetado y búsqueda por metadatos.
- Integración directa con el editor de artículos.
### Módulo 5: Portada y Destacados
- Descripción: Gestión visual de la portada del diario.
- Modelo: FeaturedBlock (title, article_id, position, style, active).
- Funcionalidad Clave:
- Editor visual tipo "drag & drop" para armar la portada.
- Bloques configurables: carruseles, destacados, columnas, banners.
- Control de vigencia y rotación automática.
### Módulo 6: Comentarios y Moderación
- Descripción: Sistema de comentarios para fomentar la participación.
- Modelo: Comment (article_id, user_id, body, status, parent_id).
- Funcionalidad Clave:
- Moderación manual o automática (palabras clave, IPs).
- Respuestas anidadas.
- Notificaciones a autores y editores.
### Módulo 7: SEO y Compartibilidad
- Descripción: Herramientas para mejorar el posicionamiento y la difusión.
- Funcionalidad Clave:
- Campos SEO personalizables por artículo.
- Generación automática de Open Graph y Twitter Cards.
- Sitemap.xml y robots.txt dinámicos.
- Integración con Google Search Console.
### Módulo 8: Estadísticas y Analítica
- Descripción: Panel de métricas para el equipo editorial.
- Controlador: AnalyticsController.
- Componentes:
- Visitas por artículo, sección y autor.
- Tasa de rebote, tiempo de lectura, CTR de destacados.
- Ranking de artículos más leídos.
- Exportación de datos en CSV.
### Módulo 9: Campañas y Publicidad
- Descripción: Gestión de banners y campañas promocionales.
- Modelo: AdCampaign (name, image, link, start_date, end_date, location, impressions, clicks).
- Funcionalidad Clave:
- Posicionamiento por zonas (header, sidebar, entre artículos).
- Estadísticas de rendimiento.
- Activación/desactivación automática por fechas.
### Módulo 10: Diseño Responsive y Marca
- Descripción: Interfaz adaptable y coherente con la identidad del diario.
- Implementación:
- Layout mobile-first con componentes reutilizables.
- Paleta de colores, tipografías y estilos definidos por branding.
- Accesibilidad (WCAG 2.1) y performance optimizada.

### 3. Hoja de Ruta de Desarrollo (Sugerida)
- Fase 1: Fundamentos y Autenticación
- Configuración del proyecto Laravel.
- Instalación de Laravel Breeze/Jetstream.
- Implementación de roles y permisos editoriales.
- Creación de modelos base: User, Article, Section, Media.
- Fase 2: Núcleo Editorial
- Desarrollo del CRUD de artículos con editor enriquecido.
- Gestión de autores y secciones.
- Biblioteca multimedia integrada.
- Fase 3: Experiencia de Lectura
- Construcción de portada dinámica.
- Implementación de comentarios y SEO.
- Diseño responsive y branding.
- Fase 4: Analítica y Monetización
- Integración con Google Analytics.
- Módulo de campañas publicitarias.
- Panel de estadísticas y exportación de datos.
