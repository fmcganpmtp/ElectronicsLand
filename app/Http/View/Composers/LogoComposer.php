<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Category;

class SiteLogo
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $top_logo =  Generalsetting::where('item','company_logo')->first();
        $view->with('top_logo', $top_logo);
    }
}