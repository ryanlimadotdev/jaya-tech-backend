<?php

declare(strict_types=1);

namespace App\Domain\UseCases\FindOrCreatePayer;

enum FindOrCreatePayerError
{
	case InfrastructureProblems;
	case InvalidDataProvided;
	case ConflictingDataProvided;
}