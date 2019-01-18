<?php

namespace netis\custom\db\mssql;

use DateTimeImmutable;

final class ColumnSchema extends \yii\db\ColumnSchema
{
    const TYPE_DATE      = 'date';
    const TYPE_DATETIME  = 'datetime';
    const TYPE_TIMESTAMP = 'timestamp';

    /**
     * @param mixed $value
     *
     * @return mixed|string
     */
    public function phpTypecast($value)
    {
        if (!in_array($this->dbType, [self::TYPE_DATE, self::TYPE_DATETIME, self::TYPE_TIMESTAMP])) {
            return parent::phpTypecast($value);
        }

        try {
            return new DateTimeImmutable($value);
        } catch (\Exception $e) {
            return parent::phpTypecast($value);
        }
    }

    /**
     * @param mixed $value
     *
     * @return mixed|string
     */
    public function dbTypecast($value)
    {
        switch ($this->dbType) {
            case self::TYPE_DATE:
                return $value instanceof DateTimeImmutable ? $value->format('Y-m-d') : parent::dbTypecast($value);
            case self::TYPE_DATETIME || self::TYPE_TIMESTAMP:
                return $value instanceof DateTimeImmutable ? $value->format('Y-m-d H:i:s.v') : parent::dbTypecast($value);
            default:
                return parent::dbTypecast($value);
        }
    }
}
