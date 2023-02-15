<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machines extends Model {
    protected $table = 'machines';

    protected $fillable = ['macAddress', 'timeLeft'];

    public static function updateTime($macAddress, $time)
    {
        $model = self::query()->where(['macAddress' => $macAddress])->get()->first();
        if (empty($model->id)) {
            $create = self::create([
                    'macAddress' => $macAddress,
                    'timeLeft' => $time,
                    'created_at' => date('Y-m-d H:i:s', time()),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]
            );

            return $create;
        }

        $model->timeLeft = $time;
        $model->updated_at = date('Y-m-d H:i:s', time());
        $model->save();

        return $model;
    }
}
