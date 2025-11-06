<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Controlador para la gestión de secciones del diario digital
 * Maneja operaciones CRUD para las secciones que organizan los artículos
 * Incluye funcionalidades de búsqueda, filtrado y validación de datos
 */
class SectionController extends Controller
{
    /**
     * Muestra el listado de todas las secciones
     * Incluye funcionalidades de búsqueda, filtrado por estado y ordenamiento
     * 
     * @param Request $request - Datos de la petición con filtros opcionales
     * @return \Illuminate\View\View - Vista del listado con secciones paginadas
     */
    public function index(Request $request)
    {
        // Consulta base con conteo de artículos por sección
        $query = Section::withCount('articles');

        // Filtro de búsqueda por nombre, descripción o slug
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por estado activo/inactivo
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Ordenamiento según parámetro recibido
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'articles_count':
                $query->orderBy('articles_count', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        // Paginación manteniendo parámetros de consulta
        $sections = $query->paginate(10)->appends($request->query());

        return view('admin.sections.index', compact('sections'));
    }

    /**
     * Muestra el formulario para crear una nueva sección
     * 
     * @return \Illuminate\View\View - Vista del formulario de creación
     */
    public function create()
    {
        return view('admin.sections.form');
    }

    /**
     * Almacena una nueva sección en la base de datos
     * Valida datos, genera slug automáticamente si no se proporciona
     * y asegura que el slug sea único
     * 
     * @param Request $request - Datos del formulario de creación
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:sections',
            'description' => 'nullable|string|max:1000',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Obtener datos validados
        $sectionData = $request->only([
            'name', 'slug', 'description', 'seo_title', 'meta_description',
            'is_active', 'is_featured', 'order'
        ]);

        // Generar slug automáticamente si no se proporciona
        if (empty($sectionData['slug'])) {
            $sectionData['slug'] = Str::slug($sectionData['name']);
        } else {
            $sectionData['slug'] = Str::slug($sectionData['slug']);
        }

        // Asegurar que el slug sea único añadiendo número consecutivo si es necesario
        $originalSlug = $sectionData['slug'];
        $counter = 1;
        while (Section::where('slug', $sectionData['slug'])->exists()) {
            $sectionData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Procesar meta_keywords como array separado por comas
        if ($request->meta_keywords) {
            $sectionData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        }

        // Establecer valores por defecto para campos booleanos
        $sectionData['is_active'] = $request->boolean('is_active', true);
        $sectionData['is_featured'] = $request->boolean('is_featured', false);
        $sectionData['order'] = $request->get('order', 0);

        // Crear nueva sección
        Section::create($sectionData);

        return redirect()->route('admin.sections.index')
                        ->with('success', 'Sección creada exitosamente.');
    }

    /**
     * Muestra una sección específica con sus artículos (vista pública)
     * Utilizada para mostrar la página de una sección en el frontend
     * 
     * @param string $slug - Slug de la sección a mostrar
     * @return \Illuminate\View\View - Vista pública de la sección con artículos
     */
    public function show(string $slug)
    {
        // Buscar sección por slug
        $section = Section::where('slug', $slug)->firstOrFail();
        
        // Obtener artículos publicados de la sección con paginación
        $articles = $section->articles()
                           ->where('status', 'published')
                           ->orderBy('published_at', 'desc')
                           ->paginate(10);

        return view('sections.show', compact('section', 'articles'));
    }

    /**
     * Muestra el formulario para editar una sección existente
     * 
     * @param Section $section - Instancia de la sección a editar
     * @return \Illuminate\View\View - Vista del formulario de edición
     */
    public function edit(Section $section)
    {
        return view('admin.sections.form', compact('section'));
    }

    /**
     * Actualiza una sección existente en la base de datos
     * Valida datos y actualiza slug asegurando unicidad excluyendo la sección actual
     * 
     * @param Request $request - Datos del formulario de edición
     * @param Section $section - Instancia de la sección a actualizar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function update(Request $request, Section $section)
    {
        // Validación incluyendo regla de unicidad que excluye la sección actual
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('sections')->ignore($section->id)],
            'description' => 'nullable|string|max:1000',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Obtener datos validados
        $sectionData = $request->only([
            'name', 'slug', 'description', 'seo_title', 'meta_description',
            'is_active', 'is_featured', 'order'
        ]);

        // Generar o normalizar slug
        if (empty($sectionData['slug'])) {
            $sectionData['slug'] = Str::slug($sectionData['name']);
        } else {
            $sectionData['slug'] = Str::slug($sectionData['slug']);
        }

        // Asegurar unicidad del slug excluyendo la sección actual
        $originalSlug = $sectionData['slug'];
        $counter = 1;
        while (Section::where('slug', $sectionData['slug'])->where('id', '!=', $section->id)->exists()) {
            $sectionData['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Procesar meta_keywords como array o establecer null
        if ($request->meta_keywords) {
            $sectionData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        } else {
            $sectionData['meta_keywords'] = null;
        }

        // Procesar campos booleanos
        $sectionData['is_active'] = $request->boolean('is_active');
        $sectionData['is_featured'] = $request->boolean('is_featured');
        $sectionData['order'] = $request->get('order', 0);

        // Actualizar sección
        $section->update($sectionData);

        return redirect()->route('admin.sections.index')
                        ->with('success', 'Sección actualizada exitosamente.');
    }

    /**
     * Elimina una sección de la base de datos
     * Verifica que no tenga artículos asociados antes de permitir eliminación
     * 
     * @param Section $section - Instancia de la sección a eliminar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito o error
     */
    public function destroy(Section $section)
    {
        // Verificar si la sección tiene artículos asociados
        if ($section->articles()->count() > 0) {
            return redirect()->route('admin.sections.index')
                            ->with('error', 'No se puede eliminar la sección porque tiene artículos asociados.');
        }

        // Eliminar sección
        $section->delete();

        return redirect()->route('admin.sections.index')
                        ->with('success', 'Sección eliminada exitosamente.');
    }
}
