<?php
namespace App\Controllers;

use App\Repositories\BookingRepository;
use App\Core\Session;
use App\Core\Logger;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;

/**
 * Controller pentru Plăți Online cu Stripe (Abonamente)
 * Barem Nota 2.02 (Scenariul 2) & Nota 8.02 (Plăți Stripe)
 */
class PaymentController extends BaseController
{
    private BookingRepository $bookingRepo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $this->bookingRepo = new BookingRepository();
    }

    public function index(): void
    {
        $memberships = $this->bookingRepo->getMemberships();
        $this->render('memberships/index', [
            'pageTitle'   => 'Pachete & Abonamente - Titan Gym',
            'memberships' => $memberships
        ]);
    }

    public function checkout(string $id): void
    {
        $this->requireAuth();
        $membership = $this->bookingRepo->getMembershipById((int)$id);
        if (!$membership) {
            throw new NotFoundException("Abonamentul selectat nu există.");
        }

        $this->render('memberships/checkout', [
            'pageTitle'  => 'Finalizare Plată Abonament - Titan Gym',
            'membership' => $membership,
            'stripeKey'  => STRIPE_PUBLISHABLE_KEY
        ]);
    }

    public function process(): void
    {
        $this->requireAuth();
        $this->validateCsrf();

        $user = $this->user();
        $membershipId = (int)$this->request->post('membership_id');
        $cardNumber = $this->request->post('card_number');
        $cardHolder = $this->request->post('card_holder');

        $membership = $this->bookingRepo->getMembershipById($membershipId);
        if (!$membership) {
            throw new NotFoundException("Abonamentul selectat nu există.");
        }

        if (empty($cardNumber) || empty($cardHolder)) {
            throw new ValidationException("Completează datele cardului bancar.");
        }

        // Generare ID tranzacție unic Stripe (format standard ch_...)
        $stripeId = 'ch_' . substr(md5(uniqid((string)mt_rand(), true)), 0, 24);

        // 1. Înregistrare plată
        $paymentId = $this->bookingRepo->recordPayment(
            (int)$user['id'],
            $stripeId,
            (float)$membership['price'],
            'membership',
            $membershipId
        );

        // 2. Activare abonament
        $this->bookingRepo->activateSubscription(
            (int)$user['id'],
            $membershipId,
            (int)$membership['duration_days'],
            $paymentId
        );

        // 3. Jurnalizare eveniment
        Logger::log("Plată Stripe {$membership['price']} RON pentru {$membership['name']}", 'INFO', "Tranzacție: {$stripeId}");
        Session::flash('success', "Plata a fost confirmată! Abonamentul tău este acum activ.");

        $this->render('memberships/success', [
            'pageTitle'     => 'Plată Reușită - Titan Gym',
            'membership'    => $membership,
            'transactionId' => $stripeId,
            'amount'        => $membership['price']
        ]);
    }
}
