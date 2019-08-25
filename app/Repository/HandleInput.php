<?php

namespace App\Repository;

use App\Contracts\HandleCommandDataInterface;
use App\Helpers\ParkingLotMigrations;
use App\Models\VehicleModal;

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
        if (!ParkingLotMigrations::checkIfParkingSlotsCreated()) {
            ParkingLotMigrations::createParkingLot();
            ParkingLotMigrations::insertParkingSlots($data[1]);
            return "Created a parking lot with $data[1] slots\n";
        }

        return "Parking Lot already exists. Delete existing parking lot to continue or enter another command.\n";
    }
    private function park(array $data): string
    {
        if (count($data) < 3) {
            return "Error, Please try again.\n";
        }
        $result = VehicleModal::create($data[1], $data[2]);
        if ($result !== "This is an existing vehicle!" && $result !== 'None') {
            return "Allocated slot number: " . $result . "\n";
        }
        return "Allocated slot number: " . VehicleModal::update($data[1], $data[2]) . "\n";

    }
    private function leave(array $data): string
    {
        if (count($data) === 0) {
            return "Error, Please try again.\n";
        }
        $response = VehicleModal::delete($data[1]);
        if ($response !== 'Does not exist') {
            return "Slot number $response is free\n";
        }
        return "The slot number does not exist!\n";
    }
    private function status(): string
    {
        return "Slot number " . VehicleModal::status() . " is free.\n";
    }
    private function registration_numbers_for_cars_with_colour($parameter): mixed
    {
        if (count($parameter) === 0) {
            return "Error, Please try again.\n";
        }

        echo "Slot No. Registration No\n";

        foreach (VehicleModal::Query($parameter, "vehicle_colour") as $value) {
            echo $value;
        }

    }
    private function slot_numbers_for_cars_with_colour($parameter): string
    {
        if (count($parameter) === 0) {
            return "Error, Please try again.\n";
        }

        echo "Slot No. Registration No\n";

        foreach (VehicleModal::Query($parameter, 'vehicle_colour') as $value) {
            echo $value;
        }

    }
    private function slot_number_for_registration_number($parameter): string
    {

        if (count($parameter) === 0) {
            return "Error, Please try again.\n";
        }

        return 'slot_number_for_registration_number';

    }
    private function reset(): string
    {
        ParkingLotMigrations::dropParkingLot();

        return "Parking Lot Successfully Deleted\n";

    }

}
