<?php

namespace DevAjMeireles\PagHiper\Billet\Actions\HighOrderInteraction;

use DevAjMeireles\PagHiper\Core\Exceptions\BadModelPreparationException;
use Illuminate\Database\Eloquent\Model;

class HighOrderCreateBillet
{
    private const PROPERTIES = ['name', 'email', 'document'];

    public function __construct(
        protected readonly Model $model
    ) {
    }

    public function execute(): array
    {
        $data = [];

        foreach (self::PROPERTIES as $property) {
            if (!property_exists($this->model, $property)) {
                continue;
            }

            if (method_exists($this->model, 'pagHiper' . $property)) {
                $data[$property] = $this->model->{$property}();
            } else {
                $data[$property] = $this->model->{$property};
            }
        }

        return array_values($this->validated($data));
    }

    /** @throws BadModelPreparationException */
    private function validated(array $data): array
    {
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['document'])) {
            throw new BadModelPreparationException(get_class($this->model));
        }

        return $data;
    }
}
