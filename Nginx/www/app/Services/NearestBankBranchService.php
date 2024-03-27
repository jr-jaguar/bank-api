<?php

namespace App\Services;

use App\Models\BankBranch;
use Illuminate\Support\Collection;

class NearestBankBranchService
{
    public function findNearestBankBranches($userLatitude, $userLongitude): Collection
    {
        $branches = BankBranch::all();
        $distances = [];

        foreach ($branches as $branch) {
            $branchLatitude = (float)$branch->lat;
            $branchLongitude = (float)$branch->lng;
            $distance = $this->calculateDistance($userLatitude, $userLongitude, $branchLatitude, $branchLongitude);
            $distances[$branch->id] = $distance;
        }

        asort($distances);

        $nearestBranchesIds = array_slice(array_keys($distances), 0, 10);
        $nearestBranches = $branches->whereIn('id', $nearestBranchesIds)->map(
            function ($branch) use ($distances) {
                $branch->distance = $distances[$branch->id];
                return $branch;
            }
        )->sortBy('distance');
        ;


        return $nearestBranches;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float|int
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin(
            $dLon / 2
        );
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
}
