<?php
namespace App\Controllers;

use App\Repositories\BookingRepository;
use App\Repositories\ClassRepository;
use App\Core\Session;
use App\Core\Logger;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;

/**
 * Controller pentru Rezervări Clase
 * Barem Nota 2.02 (Scenariul 3)
 */
class BookingController extends BaseController
{
    private BookingRepository $bookingRepo;
    private ClassRepository $classRepo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->bookingRepo = new BookingRepository();
        $this->classRepo = new ClassRepository();
    }

    public function myBookings(): void
    {
        $this->requireAuth();
        $user = $this->user();
        $bookings = $this->bookingRepo->getUserBookings((int)$user['id']);

        $this->render('bookings/my_bookings', [
            'pageTitle' => 'Rezervările Mele - Titan Gym',
            'bookings'  => $bookings
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $user = $this->user();
        $classId = (int)$this->request->post('class_id');
        $date = $this->request->post('booking_date');

        if (empty($classId) || empty($date)) {
            throw new ValidationException("Data și clasa sunt obligatorii.");
        }

        $class = $this->classRepo->findById($classId);
        if (!$class) {
            throw new NotFoundException("Clasa nu a fost găsită.");
        }

        if ($class->isFull()) {
            throw new ValidationException("Ne pare rău, toate locurile pentru această clasă sunt ocupate.");
        }

        if ($this->bookingRepo->isAlreadyBooked((int)$user['id'], $classId, $date)) {
            throw new ValidationException("Ești deja înscris la această clasă în data selectată.");
        }

        $bId = $this->bookingRepo->createBooking((int)$user['id'], $classId, $date);
        Logger::log("Rezervare nouă #{$bId} la {$class->getTitle()}", 'INFO');
        Session::flash('success', "Rezervarea la clasa {$class->getTitle()} a fost confirmată!");

        $this->response->redirect('/my-bookings');
    }

    public function cancel(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $user = $this->user();
        $bookingId = (int)$this->request->post('booking_id');

        $this->bookingRepo->cancelBooking($bookingId, (int)$user['id'], $this->isAdmin());
        Logger::log("Rezervare #{$bookingId} anulată de user #{$user['id']}", 'INFO');
        Session::flash('success', "Rezervarea a fost anulată cu succes.");

        $this->response->redirect('/my-bookings');
    }
}
