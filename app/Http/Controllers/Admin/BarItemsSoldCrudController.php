<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BarItemsSoldRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BarItemsSoldCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BarItemsSoldCrudController extends CrudController
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
        CRUD::setModel(\App\Models\BarItemsSold::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bar-items-sold');
        CRUD::setEntityNameStrings('bar items sold', 'bar items solds');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        CRUD::addColumn(['name' => 'item', 'label' => "Товар"]);
        CRUD::addColumn(['name' => 'count', 'label' => "Количество"]);
        CRUD::addColumn(['name' => 'totalAmount', 'label' => "Стоимость"]);
        CRUD::addColumn(['name' => 'created_at', 'label' => "Время продажи"]);
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
        CRUD::setValidation(BarItemsSoldRequest::class);

        $this->crud->addField([  // Select
            'label'     => "Товар",
            'type'      => 'select',
            'name'      => 'itemId', // the db column for the foreign key
            'model'     => "App\Models\BarItems", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'options'   => (function ($query) {
                return $query->orderBy('id', 'DESC')->get();
            })
        ]);

        $this->crud->addField([
            'label'     => "Количество",
            'type'      => 'number',
            'name'      => 'count'
        ]);
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
}
