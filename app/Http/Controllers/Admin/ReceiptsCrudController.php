<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReceiptsRequest;
use App\Models\BarItemsSold;
use App\Models\Receipts;
use App\Models\ReceiptsPrice;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
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
    public function createReceipt(ReceiptsRequest $request) {
        $prices = ReceiptsPrice::all();
        $mapped = [];

        foreach ($prices as $price) {
            $mapped[$price->time] = $price->price;
        }

        $receipt = new Receipts();
        $receipt->code = uniqid();
        $receipt->amount = $mapped[$request->input('time')];
        $receipt->time = $request->input('time');
        $receipt->timeLeft = $request->input('time');
        $receipt->created_at = time();
        $receipt->updated_at = time();
        $receipt->save();

        return response()->json([
            'code' => $receipt->code,
            'amount' => $receipt->amount,
            'time' => $receipt->time,
            'date' => date('d.m.Y H:i', time())
        ]);
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
