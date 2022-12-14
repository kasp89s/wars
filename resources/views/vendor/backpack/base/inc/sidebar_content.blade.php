{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}">
        <i class="nav-icon la la-user"></i> Пользователи</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('receipts') }}">
        <i class="nav-icon la la-inbox"></i> Чеки</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('receipts-price') }}">
        <i class="nav-icon la la-dollar"></i> Цены на чеки</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('bar-items') }}">
        <i class="nav-icon la la-opencart"></i> Товары бара</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('bar-items-sold') }}">
        <i class="nav-icon la la-cart-arrow-down"></i> Проданые товары</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('game-users') }}"><i class="nav-icon la la-question"></i> Game users</a></li>