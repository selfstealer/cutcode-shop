<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;

class HomeController extends Controller
{
    public function __invoke() : mixed
    {
        $categories = CategoryViewModel::make()
            ->homePage();

        $products = Product::query()
            ->homePage()
            ->get();

        $brands = BrandViewModel::make()
            ->homePage();

        return view('index', compact(
            'categories',
            'products',
            'brands'
        ));
    }
}
