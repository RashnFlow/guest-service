<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    private function determineCountry(string $phone): ?string
    {
        if (str_starts_with($phone, '+7')) {
            return 'Россия';
        }
        if (str_starts_with($phone, '+1')) {
            return 'США';
        }
        // Добавить другие страны по необходимости
        return null;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:guests',
            'phone' => 'required|string|regex:/^^(\+[0-9\s\-\+\(\)]*)$/|max:20|unique:guests',
            'country' => 'nullable|string|max:255',
        ]);

        if (!$request->has('country')) {
            $validated['country'] = $this->determineCountry($validated['phone']);
        }
        $guest = Guest::create($validated);

        return response()->json($guest, 201);
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $guest = Guest::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Запись не найдена'
            ], 404);
        }
        return response()->json($guest);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $guest = Guest::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Запись не найдена'
            ], 404);
        }


        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('guests')->ignore($guest->id),
            ],
            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                'regex:/^^(\+[0-9\s\-\+\(\)]*)$/',
                Rule::unique('guests')->ignore($guest->id),
            ],
            'country' => 'nullable|string|max:255',
        ]);

        if (!$request->has('country')) {
            $validated['country'] = $this->determineCountry($validated['phone'] ?? $guest->phone);
        }

        $guest->update($validated);

        return response()->json($guest);
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $guest = Guest::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Запись не найдена'
            ], 404);
        }
        $guest->delete();

        return response()->json(null, 204);
    }
}
