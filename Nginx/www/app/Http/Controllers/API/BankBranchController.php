<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\NearestBankBranchService;
use Illuminate\Http\Request;

class BankBranchController extends Controller
{
    public function __construct(private NearestBankBranchService $nearestBankBranchService)
    {
    }

    public function getNearestBankBranches(Request $request)
    {
        $userLatitude = $request->input('latitude');
        $userLongitude = $request->input('longitude');

        $nearestBranches = $this->nearestBankBranchService->findNearestBankBranches($userLatitude, $userLongitude);

        return response()->json($nearestBranches);
    }
}
