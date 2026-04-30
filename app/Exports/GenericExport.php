<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Collection $records;
    protected array      $columns;  // ['db_column' => 'Label']
    protected array      $relations; // ['relation.field' => 'Label']

    public function __construct(
        Builder|Collection $query,
        array $columns = [],
        array $relations = []
    ) {
        $this->records   = $query instanceof Builder ? $query->get() : $query;
        $this->columns   = $columns;
        $this->relations = $relations;
    }

    public function collection(): Collection
    {
        return $this->records;
    }

    public function headings(): array
    {
        return array_merge(
            array_values($this->columns),
            array_values($this->relations)
        );
    }

    public function map($row): array
    {
        $data = [];

        foreach (array_keys($this->columns) as $col) {
            $value = $row->{$col};
            if ($value instanceof \Carbon\Carbon) {
                $value = $value->format('d/m/Y H:i');
            } elseif (is_bool($value)) {
                $value = $value ? 'Oui' : 'Non';
            }
            $data[] = $value;
        }

        foreach (array_keys($this->relations) as $path) {
            [$rel, $field] = explode('.', $path, 2);
            $related = $row->{$rel};
            if ($related instanceof Collection) {
                $data[] = $related->pluck($field)->implode(', ');
            } elseif ($related) {
                $data[] = $related->{$field} ?? '';
            } else {
                $data[] = '';
            }
        }

        return $data;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
