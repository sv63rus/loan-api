<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Client\Client as DomainClient;
use App\Domain\Client\ValueObject\Age;
use App\Domain\Client\ValueObject\Income;
use App\Domain\Client\ValueObject\Pin;
use App\Domain\Client\ValueObject\Region;
use App\Domain\Client\ValueObject\Score;
use App\Domain\Service\CreditEligibilityService;
use App\Domain\Specification\AgeSpecification;
use App\Domain\Specification\IncomeSpecification;
use App\Domain\Specification\RegionSpecification;
use App\Domain\Specification\ScoreSpecification;
use App\Domain\Specification\SpecificationInterface;
use PHPUnit\Framework\TestCase;

final class CreditEligibilityServiceTest extends TestCase
{
    private CreditEligibilityService $service;

    protected function setUp(): void
    {
        $specs = [
            new ScoreSpecification(500),
            new IncomeSpecification(1000),
            new AgeSpecification(18, 60),
            new RegionSpecification([Region::PR, Region::BR, Region::OS]),
            new class () implements SpecificationInterface {
                public function isSatisfiedBy(mixed $candidate): bool
                {
                    return true;
                }
            },
        ];

        $this->service = new CreditEligibilityService($specs);
    }

    public function testEligibleClient(): void
    {
        $client = new DomainClient(
            '1',
            'Alice',
            new Age(30),
            Region::BR,
            new Income(2000),
            new Score(700),
            new Pin('123-45-6789'),
            'alice@example.com',
            '+1234567890'
        );

        $this->assertTrue(
            $this->service->isEligible($client),
            'Client meeting all criteria should be eligible'
        );
    }

    public function testScoreTooLow(): void
    {
        $client = new DomainClient(
            '2',
            'Bob',
            new Age(30),
            Region::BR,
            new Income(2000),
            new Score(400),
            new Pin('123-45-6789'),
            'bob@example.com',
            '+1234567890'
        );

        $this->assertFalse(
            $this->service->isEligible($client),
            'Client with low score should be ineligible'
        );
    }

    public function testIncomeTooLow(): void
    {
        $client = new DomainClient(
            '3',
            'Charlie',
            new Age(30),
            Region::BR,
            new Income(500),
            new Score(700),
            new Pin('123-45-6789'),
            'charlie@example.com',
            '+1234567890'
        );

        $this->assertFalse(
            $this->service->isEligible($client),
            'Client with low income should be ineligible'
        );
    }

    public function testAgeOutOfRange(): void
    {
        $young = new DomainClient(
            '4',
            'Dave',
            new Age(17),
            Region::BR,
            new Income(2000),
            new Score(700),
            new Pin('123-45-6789'),
            'dave@example.com',
            '+1234567890'
        );
        $old = new DomainClient(
            '5',
            'Eve',
            new Age(61),
            Region::BR,
            new Income(2000),
            new Score(700),
            new Pin('123-45-6789'),
            'eve@example.com',
            '+1234567890'
        );

        $this->assertFalse(
            $this->service->isEligible($young),
            'Client below minimum age should be ineligible'
        );
        $this->assertFalse(
            $this->service->isEligible($old),
            'Client above maximum age should be ineligible'
        );
    }

    public function testRegionNotAllowed(): void
    {
        $client = new DomainClient(
            '6',
            'Frank',
            new Age(30),
            Region::OS,
            new Income(2000),
            new Score(700),
            new Pin('123-45-6789'),
            'frank@example.com',
            '+1234567890'
        );

        $this->assertTrue(
            $this->service->isEligible($client),
            'Allowed region should be eligible'
        );

        $other = new DomainClient(
            '7',
            'Gina',
            new Age(30),
            Region::fromString('BR'),
            new Income(2000),
            new Score(700),
            new Pin('123-45-6789'),
            'gina@example.com',
            '+1234567890'
        );

        $specs = [
            new ScoreSpecification(500),
            new IncomeSpecification(1000),
            new AgeSpecification(18, 60),
            new class () implements SpecificationInterface {
                public function isSatisfiedBy(mixed $c): bool
                {
                    return false;
                }
            },
            new class () implements SpecificationInterface {
                public function isSatisfiedBy(mixed $c): bool
                {
                    return true;
                }
            },
        ];
        $service = new CreditEligibilityService($specs);

        $this->assertFalse(
            $service->isEligible($other),
            'Client from disallowed region should be ineligible'
        );
    }
}
