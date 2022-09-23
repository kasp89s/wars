<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipts extends Model {
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;

    protected $table = 'receipts';

    public static function getStatsByDay()
    {
        $result = [];

        $data = self::whereRaw('`created_at` between ? and ?',
            [date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time())])->get();

        for ($i = (int) $data->first()->created_at->isoFormat('H'); $i <= (int) $data->last()->created_at->isoFormat('H'); $i++) {
            $result[$i] = 0;
        }

        foreach ($data as $item) {
            $hour = (int) $item->created_at->isoFormat('H');
            $result[$hour] = $result[$hour] + $item->amount;
        }

        $response = [];
        foreach ($result as $t => $v) {
            $response['labels'][] = $t;
            $response['data'][] = $v;
        }

        return $response;
    }

    public static function getTotalAmountByDay()
    {
        $res = DB::table('receipts')->select(DB::raw('sum(`amount`) as totalAmount'))
            ->whereRaw('created_at between ? and ?',
                [date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time())])->first();

        return (int)$res->totalAmount ?? 0;
    }
}
