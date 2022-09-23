<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\DB;

class BarItemsSold extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'bar_items_sold';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $item = BarItems::find($model->itemId);
            $model->totalAmount = $item->amount * $model->count;
        });

        self::updating(function($model){
            $item = BarItems::find($model->itemId);
            $model->totalAmount = $item->amount * $model->count;
        });
    }

    public function getItemAttribute($value)
    {
        $item = BarItems::find($this->itemId);

        return $item->name;
    }

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
            $result[$hour] = $result[$hour] + $item->totalAmount;
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
        $res = DB::table('bar_items_sold')->select(DB::raw('sum(`totalAmount`) as totalAmount'))
            ->whereRaw('created_at between ? and ?',
                [date('Y-m-d 00:00:00', time()), date('Y-m-d 23:59:59', time())])->first();

        return (int)$res->totalAmount ?? 0;
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
