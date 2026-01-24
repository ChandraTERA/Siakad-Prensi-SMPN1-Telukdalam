<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolLocation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GpsValidationController extends Controller
{
    /**
     * Validasi apakah koordinat berada dalam radius sekolah
     */
    public function validateLocation(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $schoolLocation = SchoolLocation::getMainLocation();

        if (!$schoolLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi sekolah belum dikonfigurasi',
                'error_code' => 'SCHOOL_LOCATION_NOT_CONFIGURED'
            ], 400);
        }

        $isWithinRadius = $schoolLocation->isWithinRadius(
            $request->latitude,
            $request->longitude
        );

        $distance = SchoolLocation::calculateDistance(
            $schoolLocation->latitude,
            $schoolLocation->longitude,
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'success' => true,
            'is_within_radius' => $isWithinRadius,
            'distance_meters' => round($distance),
            'school_location' => [
                'nama_lokasi' => $schoolLocation->nama_lokasi,
                'latitude' => $schoolLocation->latitude,
                'longitude' => $schoolLocation->longitude,
                'radius_meter' => $schoolLocation->radius_meter,
                'alamat' => $schoolLocation->alamat,
            ],
            'student_location' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        ]);
    }

    /**
     * Dapatkan informasi lokasi sekolah aktif
     */
    public function getSchoolLocation(): JsonResponse
    {
        $schoolLocation = SchoolLocation::getMainLocation();

        if (!$schoolLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi sekolah belum dikonfigurasi',
                'error_code' => 'SCHOOL_LOCATION_NOT_CONFIGURED'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'school_location' => [
                'id' => $schoolLocation->id,
                'nama_lokasi' => $schoolLocation->nama_lokasi,
                'latitude' => $schoolLocation->latitude,
                'longitude' => $schoolLocation->longitude,
                'radius_meter' => $schoolLocation->radius_meter,
                'alamat' => $schoolLocation->alamat,
                'is_active' => $schoolLocation->is_active,
            ]
        ]);
    }

    /**
     * Test koordinat untuk debugging
     */
    public function testLocation(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $schoolLocation = SchoolLocation::getMainLocation();

        if (!$schoolLocation) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi sekolah belum dikonfigurasi'
            ], 400);
        }

        $distance = SchoolLocation::calculateDistance(
            $schoolLocation->latitude,
            $schoolLocation->longitude,
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'success' => true,
            'test_results' => [
                'school_coordinates' => [
                    'latitude' => $schoolLocation->latitude,
                    'longitude' => $schoolLocation->longitude,
                ],
                'test_coordinates' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ],
                'distance_meters' => round($distance),
                'radius_meter' => $schoolLocation->radius_meter,
                'is_within_radius' => $distance <= $schoolLocation->radius_meter,
                'margin_meters' => $schoolLocation->radius_meter - $distance,
            ]
        ]);
    }
}
