<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Models\User;
use App\Repositories\UserPreferenceRepository;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function index(Request $request)
    {
        $userPreference = $request->user()->userPreference ?? [];
        return response()->json($userPreference);
    }

    public function store(PreferenceRequest $request, UserPreferenceRepository $userPreferenceRepository)
    {
        $validData = $request->validated();
        $userPreference = $userPreferenceRepository->save($request->user()->id, $validData);
        if ($userPreference) {
            return response()->json($userPreference);
        }
        return response()->json(['message' => 'Failed to save preference'], 500);
    }
}
