<?php
/**
 * Date:    25.01.18
 *
 * @author: dolphin54rus <dolphin54rus@gmail.com>
 */

namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Lang;

trait PaginationHelper
{
    /**
     * Check exist page number
     *
     * @param \Illuminate\Http\Request                                                             $request
     * @param \Illuminate\Pagination\AbstractPaginator|\Illuminate\Pagination\LengthAwarePaginator $pager
     */
    public function existPageNumber(Request $request, AbstractPaginator $pager)
    {
        $pageNumber = $request->input($pager->getPageName(), 1);
        if ($pageNumber < 1 or $pageNumber > $pager->lastPage()) {
            abort(404);
        }
    }
}