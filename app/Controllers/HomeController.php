<?php
namespace App\Controllers;

use App\Repositories\ClassRepository;
use App\Repositories\BookingRepository;

/**
 * Controller Pagina Principală (Homepage)
 */
class HomeController extends BaseController
{
    private ClassRepository $classRepo;
    private BookingRepository $bookingRepo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->classRepo = new ClassRepository();
        $this->bookingRepo = new BookingRepository();
    }

    public function index(): void
    {
        $classes = $this->classRepo->getAllWithDetails();
        $trainers = $this->classRepo->getTrainers();
        $memberships = $this->bookingRepo->getMemberships();

        $this->render('home/index', [
            'pageTitle'   => 'Titan Gym & Fitness - Performanță și Pasiune',
            'classes'     => array_slice($classes, 0, 3),
            'allClasses'  => $classes,
            'trainers'    => $trainers,
            'memberships' => $memberships
        ]);
    }
}
