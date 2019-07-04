<?php

namespace App\Commands;

use Exception;
use ReflectionClass;
use Framework\Command;
use ReflectionException;
use Framework\Database\Model;

class BuildSchema extends Command
{
    /**
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating schema.');

        foreach (glob(app()->getRoot() . '/app/Models/*.php') as $file)
        {
            $class = 'App\\Models\\' . basename($file, '.php');

            if (class_exists($class))
            {
                try {
                    $reflection = new ReflectionClass($class);
                } catch (ReflectionException $e) {
                    $this->error(' |-- Unable to handle ' . $class);
                    continue;
                }

                $this->info('-- Discovered ' . $class);

                if (!$reflection->isSubclassOf(Model::class)) {
                    $this->error(' |-- Skipping ' . $class . ' as it does not extend ' . Model::class);
                    continue;
                }

                /** @var Model $model */
                $model = $reflection->newInstance();

                // Discover columns and their types
                $columns = $model->attributes && $model->schema ?
                    $columns = array_merge(array_flip($model->attributes), $model->schema) :
                    $model->attributes;

                array_map(function($item) {
                    return is_array($item) ? $item : ['type' => 'varchar(255)'];
                }, $columns);

                $this->info(' | Table: ' . $model->getTable());
                $this->info(' | Primary Key: ' . $model->getPrimaryKey());
                $this->info(' | Columns: ');
                if ($columns) {
                    foreach ($columns as $column) {
                        $this->info(' |-- ' . (is_array($column) ? json_encode($column) : $column));
                    }

                    // Insert the table
                    try {
                        $query = app()->getConnection()->getInstance()->query(// filmid int auto_increment primary key
                            "create table if not exists {$model->getTable()} (" .
                            implode(', ', array_map(function($key, $column) {
                                    return implode(' ', array_filter([
                                        $key,
                                        $column['type'],
                                        $column['increment'] ? 'auto_increment' : null,
                                        $column['primary'] ? 'primary key' : null,
                                        $column['primary'] ? null : ($column['null'] ? 'null' : 'not null'),
                                    ]));
                                }, array_keys($columns), $columns)
                            ) . ")"
                        )->execute();
                    } catch (Exception $exception) {
                        $this->error(' |-- ' .$exception->getMessage());
                    }

                    $this->info(
                        $query ?
                            ' |-- Successfully created ' . $model->getTable() :
                            ' |-- There was an error creating ' . $model->getTable()
                    );
                } else {
                    $this->info(' |-- No column\'s were discovered.');
                }
            }
        }

    }
}