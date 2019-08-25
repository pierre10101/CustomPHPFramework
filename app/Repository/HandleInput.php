<?php

namespace App\Repository;

use App\Contracts\HandleCommandDataInterface;
use App\Helpers\ParkingLotMigrations;

class HandleInput implements HandleCommandDataInterface
{

    public function determine(string $function, array $input = []): string
    {

        $function = lcfirst(trim(preg_replace('/\n/', '', $function)));

        if (method_exists(HandleInput::class, $function)) {
            return $this->$function($input);
        }

        return "Sorry, unfortunately that command does not exist!\nPlease enter a new command or terminate the shell?\n";

    }

    private function create_parking_lot(array $data): string
    {
        if (count($data) === 0) {
            return "Error, Please try again.\n";
        }
        var_dump(ParkingLotMigrations::checkIfParkingSlotsCreated());
        if (!ParkingLotMigrations::checkIfParkingSlotsCreated()) {
            ParkingLotMigrations::createParkingLot();
            ParkingLotMigrations::insertParkingSlots($data[0]);
            return 'Created a parking lot with 6 slots';
        }

        return 'Parking Lot already exists. Do you want to delete existing parking lot?';
    }
    private function park(array $data): string
    {
        return 'park' . $data[0] . $data[1];
    }
    private function leave(array $data): string
    {
        return 'leave' . $data[0];
    }
    private function status(): string
    {
        return 'status';
    }
    private function registration_numbers_for_cars_with_colour(): string
    {
        return 'registration_numbers_for_cars_with_colour';

    }
    private function slot_numbers_for_cars_with_colour(): string
    {
        return 'slot_numbers_for_cars_with_colour';

    }
    private function slot_number_for_registration_number(): string
    {
        return 'slot_number_for_registration_number';

    }
    private function reset(): string
    {
        return 'reset';

    }

}
