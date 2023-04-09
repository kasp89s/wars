<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReceiptsRequest;
use App\Models\BarItemsSold;
use App\Models\Receipts;
use App\Models\ReceiptsPrice;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * Class ReceiptsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReceiptsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    const TIME_PER_AMOUNT = 1.4;

    const ACTION_TIME_PER_AMOUNT = 2;

    protected static $_priceMap = [
        30 => 30,
        45 => 60,
        90 => 120,
        110 => 180,
        200 => 360
    ];

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Receipts::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/receipts');
        CRUD::setEntityNameStrings('receipts', 'receipts');

        Widget::add()->type('script')->content(asset('assets/js/app.js'));
        $this->crud->setListView('admin.receipts-list');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name'      => 'created_at',
            'label'     => 'Время продажи'
        ]);

        CRUD::addColumn([
            'name'      => 'code',
            'label'     => 'Код активации'
        ]);

        CRUD::addColumn([
            'name'      => 'amount',
            'label'     => 'Цена'
        ]);

        CRUD::addColumn([
            'name'      => 'time',
            'label'     => 'Время'
        ]);

        CRUD::addColumn([
            'name'      => 'timeLeft',
            'label'     => 'Время (осталось)'
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ReceiptsRequest::class);

        CRUD::field('code');
        CRUD::field('time');
        CRUD::field('timeLeft');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Создание чека.
     *
     * @param ReceiptsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createReceipt(ReceiptsRequest $request) : JsonResponse {
        $receipt = new Receipts();
        $receipt->code = $this->generateUID();
        $receipt->amount = $request->input('price');
        $receipt->time = isset(self::$_priceMap[$request->input('price')]) ? self::$_priceMap[$request->input('price')] : $request->input('price') * self::TIME_PER_AMOUNT;
        $receipt->timeLeft = $receipt->time;
        $receipt->created_at = time();
        $receipt->updated_at = time() - 600;
        $receipt->save();

        return response()->json([
            'code' => $receipt->code,
            'amount' => $receipt->amount,
            'isAction' => 0,
            'time' => $receipt->time,
            'date' => date('d.m.Y H:i', time())
        ]);
    }

    /**
     * Создание децкого чека.
     *
     * @param ReceiptsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createReceiptAction(ReceiptsRequest $request) : JsonResponse {

        $receipt = new Receipts();
        $receipt->code = $this->generateUID();
        $receipt->amount = $request->input('price');
        $receipt->time = $request->input('price') * self::ACTION_TIME_PER_AMOUNT;
        $receipt->timeLeft = $receipt->time;
        $receipt->isAction = 1;
        $receipt->created_at = time();
        $receipt->updated_at = time() - 600;
        $receipt->save();

        return response()->json([
            'code' => $receipt->code,
            'amount' => $receipt->amount,
            'isAction' => 1,
            'time' => $receipt->time,
            'date' => date('d.m.Y H:i', time())
        ]);
    }

    public function generateUID()
    {
        $numbers =
        [
            0 => 'Z',
            1 => 'H',
            2 => 'R',
            3 => 'Q',
            4 => 'T',
            5 => 'A',
            6 => 'B',
            7 => 'N',
            8 => 'S',
            9 => 'W',
        ];

        $hours = [
            0 => 'Q',
            1 => 'W',
            2 => 'E',
            3 => 'R',
            4 => 'T',
            5 => 'Y',
            6 => 'U',
            7 => 'I',
            8 => 'O',
            9 => 'P',
            10 => 'A',
            11 => 'S',
            12 => 'D',
            13 => 'F',
            14 => 'G',
            15 => 'H',
            16 => 'J',
            17 => 'K',
            18 => 'L',
            19 => 'Z',
            20 => 'X',
            21 => 'C',
            22 => 'V',
            23 => 'B',
            ];

        $minutes = date('i', time());
        $seconds = date('s', time());

        $day = date('z', time());


        return $day . $hours[date('G', time())] .
        mb_substr($minutes, 0, 1) . $numbers[mb_substr($minutes, 1, 1)] .
        mb_substr($seconds, 0, 1) . $numbers[mb_substr($seconds, 1, 1)];
    }

    /**
     * Инфо для лавной.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMainInfo()
    {
        return response()->json([
            'recPerDay' => Receipts::getStatsByDay(),
            'barPerDay' => BarItemsSold::getStatsByDay(),
            'recTotalAmountByDay' => Receipts::getTotalAmountByDay(),
            'barTotalAmountByDay' => BarItemsSold::getTotalAmountByDay()
        ]);
    }
}
