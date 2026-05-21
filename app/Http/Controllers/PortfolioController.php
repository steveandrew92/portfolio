<?php

namespace App\Http\Controllers;

use App\Services\PortfolioDataService;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(
        private readonly PortfolioDataService $portfolio,
    ) {}

    public function index(): View
    {
        return view('portfolio.index', [
            'data' => $this->portfolio->all(),
        ]);
    }
}
