<?php
declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Client\ValueObject\Age;
use App\Domain\Client\ValueObject\Income;
use App\Domain\Client\ValueObject\Pin;
use App\Domain\Client\ValueObject\Region;
use App\Domain\Client\ValueObject\Score;

final class Client
{
    private string $id;
    private string $name;
    private Age $age;
    private Region $region;
    private Income $income;
    private Score $score;
    private Pin $pin;
    private string $email;
    private string $phone;

    public function __construct(
        string $id,
        string $name,
        Age $age,
        Region $region,
        Income $income,
        Score $score,
        Pin $pin,
        string $email,
        string $phone
    ) {
        $this->id     = $id;
        $this->name   = $name;
        $this->age    = $age;
        $this->region = $region;
        $this->income = $income;
        $this->score  = $score;
        $this->pin    = $pin;
        $this->email  = $email;
        $this->phone  = $phone;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function age(): Age
    {
        return $this->age;
    }

    public function region(): Region
    {
        return $this->region;
    }

    public function income(): Income
    {
        return $this->income;
    }

    public function score(): Score
    {
        return $this->score;
    }

    public function pin(): Pin
    {
        return $this->pin;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function phone(): string
    {
        return $this->phone;
    }
}
