<?php

declare(strict_types=1);

namespace MobilityWork\Service;

class ZendeskService
{
    private ZendeskAPIInterface $zendeskAPI;
    private ReservationRepository $reservationRepository;
    private HotelContractService $hotelContractService;

    public function __construct(
        ZendeskAPIInterface $zendeskAPI,
        ReservationRepository $reservationRepository,
        HotelContractService $hotelContractService
    ) {
        $this->zendeskAPI = $zendeskAPI;
        $this->reservationRepository = $reservationRepository;
        $this->hotelContractService = $hotelContractService;
    }

    public function createCustomerTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        string $reservationNumber,
        Hotel $hotel,
        Language $language
    ): bool {
        $reservation = null;

        if (!empty($reservationNumber)) {
            $reservation = $this->reservationRepository->getByRef($reservationNumber);

            if ($reservation != null) {
                if ($hotel == null) {
                    $hotel = $reservation->getHotel();
                }
            }
        }

        $customFields = [];
        $customFields['80924888'] = 'customer';
        $customFields['80531327'] = $reservationNumber;

        if ($hotel != null) {
            $hotelContact = $this->hotelContractService->getMainHotelContact($hotel);
            $customFields['80531267'] = $hotelContact != null ? $hotelContact->getEmail() : null;
            $customFields['80918668'] = $hotel->getName();
            $customFields['80918648'] = $hotel->getAddress();
        }

        if ($reservation != null) {
            $roomName = $reservation->getRoom()->getName() . ' (' . $reservation->getRoom()->getType() . ')';
            $customFields['80531287'] = $roomName;
            $customFields['80531307'] = $reservation->getBookedDate()->format('Y-m-d');
            $customFields['80924568'] = $reservation->getRoomPrice() . ' ' . $reservation->getHotel()->getCurrency()->getCode();
            $customFields['80918728'] = $reservation->getBookedStartTime()->format('H:i') . ' - ' . $reservation->getBookedEndTime()->format('H:i');
        }

        $customFields['80918708'] = $language->getName();

        $userId = $this->zendeskAPI->createUser(
            [
                'email' => $email,
                'name' => $firstName . ' ' . strtoupper($lastName),
                'phone' => !empty($phoneNumber) ? $phoneNumber : ($reservation != null ? $reservation->getCustomer()->getSimplePhoneNumber() : ''),
                'role' => 'end-user',
            ]
        );

        $this->zendeskAPI->createTicket(
            [
                'requester_id' => $userId,
                'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body' => $message,
                    ],
                'priority' => 'normal',
                'type' => 'question',
                'status' => 'new',
                'custom_fields' => $customFields,
            ]
        );

        return true;
    }

    public function createHotelTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $city,
        string $website,
        string $hotelName,
        string $message,
        Language $language
    ): bool {
        $customFields = [];
        $customFields['80924888'] = 'hotel';
        $customFields['80918668'] = $hotelName;
        $customFields['80918648'] = $city;
        $customFields['80918708'] = $language->getName();

        $userId = $this->zendeskAPI->createUser(
            [
                'email' => $email,
                'name' => $firstName . ' ' . strtoupper($lastName),
                'phone' => $phoneNumber,
                'role' => 'end-user',
                'user_fields' => ['website' => $website],
            ]
        );

        $this->zendeskAPI->createTicket(
            [
                'requester_id' => $userId,
                'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body' => $message,
                    ],
                'priority' => 'normal',
                'type' => 'question',
                'status' => 'new',
                'custom_fields' => $customFields,
            ]
        );

        return true;
    }

    public function createPressTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $city,
        $media,
        string $message,
        Language $language
    ): bool {
        $customFields = [];
        $customFields['80924888'] = 'press';
        $customFields['80918648'] = $city;
        $customFields['80918708'] = $language->getName();

        $userId = $this->zendeskAPI->createUser(
            [
                'email' => $email,
                'name' => $firstName . ' ' . strtoupper($lastName),
                'phone' => $phoneNumber,
                'role' => 'end-user',
                'user_fields' => ['press_media' => $media],
            ]
        );

        $this->zendeskAPI->createTicket(
            [
                'requester_id' => $userId,
                'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body' => $message,
                    ],
                'priority' => 'normal',
                'type' => 'question',
                'status' => 'new',
                'custom_fields' => $customFields,
            ]
        );

        return true;
    }

    public function createPartnersTicket(
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        Language $language
    ): bool {
        $customFields = [];
        $customFields['80924888'] = 'partner';
        $customFields['80918708'] = $language->getName();

        $userId = $this->zendeskAPI->createUser(
            [
                'email' => $email,
                'name' => $firstName . ' ' . strtoupper($lastName),
                'phone' => $phoneNumber,
                'role' => 'end-user',
            ]
        );

        $this->zendeskAPI->createTicket(
            [
                'requester_id' => $userId,
                'subject' => strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message,
                'comment' =>
                    [
                        'body' => $message,
                    ],
                'priority' => 'normal',
                'type' => 'question',
                'status' => 'new',
                'custom_fields' => $customFields,
            ]
        );

        return true;
    }
}