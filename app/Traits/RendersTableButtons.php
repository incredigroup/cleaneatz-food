<?php

namespace App\Traits;

use Illuminate\Support\Facades\View;

trait RendersTableButtons
{
    public function renderEditButton($routeName, $id)
    {
        return View::make('admin.components.buttons.edit', compact('routeName', 'id'))->render();
    }

    public function renderDeleteButton($routeName, $id)
    {
        return View::make('admin.components.buttons.delete', compact('routeName', 'id'))->render();
    }

    public function renderEditDeleteButtons($routeName, $id)
    {
        return '<div class="table-actions">' .
            $this->renderEditButton($routeName, $id) . ' ' . $this->renderDeleteButton($routeName, $id) .
            '</div>';
    }

    public function renderStoreEditButton($storeLocation, $routeName, $id)
    {
        return View::make('admin.components.buttons.edit', compact('storeLocation', 'routeName', 'id'))
            ->render();
    }

    public function renderStoreDeleteButton($storeLocation, $routeName, $id)
    {
        return View::make('admin.components.buttons.delete', compact('storeLocation','routeName', 'id'))
            ->render();
    }

    public function renderStoreEditDeleteButtons($storeLocation, $routeName, $id)
    {
        return '<div class="table-actions">' .
            $this->renderStoreEditButton($storeLocation, $routeName, $id) . ' ' .
            $this->renderStoreDeleteButton($storeLocation, $routeName, $id) .
            '</div>';
    }
}
