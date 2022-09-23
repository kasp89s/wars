<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BarItemsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BarItemsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BarItemsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\BarItems::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bar-items');
        CRUD::setEntityNameStrings('bar items', 'bar items');
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
            'name'      => 'name',
            'label'     => 'Название'
        ]);
//        CRUD::column('image');
        $this->crud->addColumn([
            'name'      => 'image',
            'label'     => 'Картинка',
            'type'      => 'image',
            'prefix'      => 'storage/',
        ]);
        CRUD::addColumn([
            'name' => 'amount',
            'label' => 'Цена'
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
//        $this->crud->hasUploadFields()
        CRUD::setValidation(BarItemsRequest::class);

        CRUD::field('name');
//        CRUD::field([   // Upload
//            'name'      => 'image',
//            'label'     => 'Image',
//            'type'      => 'upload',
//            'upload'    => true,
//            'disk'      => 'uploads', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
//            // optional:
//            'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
//        ]);
//        CRUD::field([
//            'name'      => 'image',
////            'label'     => 'Картинка',
//            'type'      => 'upload',
//            'upload'    => true
//        ]);
        $this->crud->addField([   // Upload
            'name'      => 'image',
            'label'     => 'Image',
            'type'      => 'upload',
            'upload'    => true,
//            'disk'      => 'uploads', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
            // optional:
//            'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
        ]);
        CRUD::field('amount');

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
