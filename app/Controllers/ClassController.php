<?php
namespace App\Controllers;

use App\Repositories\ClassRepository;
use App\Exceptions\NotFoundException;

/**
 * Controller pentru vizualizarea și filtrarea claselor de fitness
 * Barem Nota 2.02 (Scenariul 1)
 */
class ClassController extends BaseController
{
    private ClassRepository $classRepo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->classRepo = new ClassRepository();
    }

    public function index(): void
    {
        $catId = $this->request->get('category');
        $selectedCat = $catId ? (int)$catId : null;

        $classes = $this->classRepo->getAllWithDetails($selectedCat);
        $categories = $this->classRepo->getCategories();

        $this->render('classes/index', [
            'pageTitle'        => 'Clase Fitness & Antrenamente de Grup',
            'classes'          => $classes,
            'categories'       => $categories,
            'selectedCategory' => $selectedCat
        ]);
    }

    public function show(string $id): void
    {
        $class = $this->classRepo->findById((int)$id);
        if (!$class) {
            throw new NotFoundException("Clasa de antrenament solicitată nu există.");
        }

        $this->render('classes/show', [
            'pageTitle' => $class->getTitle() . ' - Titan Gym',
            'class'     => $class
        ]);
    }
}
