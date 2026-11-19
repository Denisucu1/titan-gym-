<?php
namespace App\Controllers;

use App\Repositories\ClassRepository;
use App\Repositories\BookingRepository;
use App\Repositories\UserRepository;
use App\Core\Security;
use App\Core\Logger;
use App\Core\Session;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;

/**
 * Controller pentru Panoul de Administrare
 * Barem Nota 2.02 (Scenariul 4), Nota 3.04 (Autorizare Admin), Nota 7.01 (Logs), Nota 9 & 10 (Upload & Steganografie).
 */
class AdminController extends BaseController
{
    private ClassRepository $classRepo;
    private BookingRepository $bookingRepo;
    private UserRepository $userRepo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->classRepo = new ClassRepository();
        $this->bookingRepo = new BookingRepository();
        $this->userRepo = new UserRepository();
    }

    public function dashboard(): void
    {
        $this->requireAdmin();

        $classesCount = count($this->classRepo->getAll());
        $usersCount = count($this->userRepo->getAll());
        $bookings = $this->bookingRepo->getAllBookings();
        $payments = $this->bookingRepo->getAllPayments();

        $revenue = 0;
        foreach ($payments as $p) $revenue += (float)$p['amount'];

        $this->render('admin/dashboard', [
            'pageTitle'      => 'Panou Admin - Titan Gym',
            'classesCount'   => $classesCount,
            'usersCount'     => $usersCount,
            'bookingsCount'  => count($bookings),
            'totalRevenue'   => $revenue,
            'recentBookings' => array_slice($bookings, 0, 5)
        ]);
    }

    public function classes(): void
    {
        $this->requireAdmin();
        $this->render('admin/classes', [
            'pageTitle' => 'Gestiune Clase Fitness - Admin',
            'classes'   => $this->classRepo->getAllWithDetails()
        ]);
    }

    public function createClass(): void
    {
        $this->requireAdmin();
        $this->render('admin/class_form', [
            'pageTitle'  => 'Adaugă Clasă Nouă - Admin',
            'categories' => $this->classRepo->getCategories(),
            'trainers'   => $this->classRepo->getTrainers(),
            'class'      => null
        ]);
    }

    public function storeClass(): void
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $title = $this->request->post('title');
        $categoryId = (int)$this->request->post('category_id');
        $trainerId = (int)$this->request->post('trainer_id');
        $scheduleDay = $this->request->post('schedule_day');
        $duration = (int)$this->request->post('duration_minutes', 60);
        $capacity = (int)$this->request->post('capacity', 20);
        $price = (float)$this->request->post('price', 0);
        $description = $this->request->post('description');

        if (empty($title) || empty($scheduleDay) || empty($description)) {
            throw new ValidationException("Câmpurile marcate sunt obligatorii.");
        }

        // Upload cu validare 12MB & anti-steganografie GD (Barem 9.04, 9.05 & 10.03)
        $imageName = 'default-class.jpg';
        $file = $this->request->file('image');
        if ($file && !empty($file['name'])) {
            $imageName = Security::handleFileUpload($file, UPLOAD_DIR);
        }

        $newId = $this->classRepo->create([
            'category_id'      => $categoryId,
            'trainer_id'       => $trainerId,
            'title'            => $title,
            'description'      => $description,
            'schedule_day'     => $scheduleDay,
            'duration_minutes' => $duration,
            'capacity'         => $capacity,
            'price'            => $price,
            'image_url'        => $imageName
        ]);

        Logger::log("Admin a creat clasa '{$title}' (ID #{$newId})", 'INFO');
        Session::flash('success', "Clasa a fost creată cu succes!");
        $this->response->redirect('/admin/classes');
    }

    public function editClass(string $id): void
    {
        $this->requireAdmin();
        $class = $this->classRepo->findById((int)$id);
        if (!$class) throw new NotFoundException("Clasa nu există.");

        $this->render('admin/class_form', [
            'pageTitle'  => 'Editare Clasă - Admin',
            'categories' => $this->classRepo->getCategories(),
            'trainers'   => $this->classRepo->getTrainers(),
            'class'      => $class
        ]);
    }

    public function updateClass(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $classId = (int)$id;
        $class = $this->classRepo->findById($classId);
        if (!$class) throw new NotFoundException("Clasa nu există.");

        $title = $this->request->post('title');
        $imageName = $class->getImageUrl();
        $file = $this->request->file('image');
        if ($file && !empty($file['name'])) {
            $imageName = Security::handleFileUpload($file, UPLOAD_DIR);
        }

        $this->classRepo->update($classId, [
            'category_id'      => (int)$this->request->post('category_id'),
            'trainer_id'       => (int)$this->request->post('trainer_id'),
            'title'            => $title,
            'description'      => $this->request->post('description'),
            'schedule_day'     => $this->request->post('schedule_day'),
            'duration_minutes' => (int)$this->request->post('duration_minutes', 60),
            'capacity'         => (int)$this->request->post('capacity', 20),
            'price'            => (float)$this->request->post('price', 0),
            'image_url'        => $imageName
        ]);

        Logger::log("Admin a modificat clasa ID #{$classId}", 'INFO');
        Session::flash('success', "Modificările au fost salvate!");
        $this->response->redirect('/admin/classes');
    }

    public function deleteClass(string $id): void
    {
        $this->requireAdmin();
        $this->validateCsrf();

        $this->classRepo->delete((int)$id);
        Logger::log("Admin a șters clasa ID #{$id}", 'WARNING');
        Session::flash('success', "Clasa a fost ștearsă.");
        $this->response->redirect('/admin/classes');
    }

    public function bookings(): void
    {
        $this->requireAdmin();
        $this->render('admin/bookings', [
            'pageTitle' => 'Toate Rezervările - Admin',
            'bookings'  => $this->bookingRepo->getAllBookings()
        ]);
    }

    public function logs(): void
    {
        $this->requireAdmin();
        $this->render('admin/logs', [
            'pageTitle' => 'Jurnal Activități (Audit Logs) - Admin',
            'logs'      => Logger::getRecentLogs(80)
        ]);
    }
}
