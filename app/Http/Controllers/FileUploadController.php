<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends ApiController
{
    /**
     * Upload image file
     */
    public function uploadImage(Request $request)
    {
        // Validate request
        $validator = validator($request->all(), [
            'image' => 'required|file|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        try {
            $file = $request->file('image');
            $user = auth('api')->user();
            
            // Check if file exists and is valid
            if (!$file || !$file->isValid()) {
                return $this->errorResponse('Invalid file upload', 400);
            }
            
            // Generate unique filename with user ID for better organization
            $filename = $user->id . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Get file info before storing
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
            
            // Store file in private storage (more secure)
            $path = $file->storeAs('images', $filename, 'local');
            
            // Generate secure URL that requires authentication
            $url = route('api.image.show', ['filename' => $filename]);
            
            return $this->successResponse([
                'url' => $url,
                'filename' => $filename,
                'original_name' => $originalName,
                'size' => $size,
                'mime_type' => $mimeType,
                'storage_path' => $path
            ], 'Image uploaded successfully');
            
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to upload image: ' . $e->getMessage());
        }
    }

    /**
     * Show image file (public access - no authentication required)
     */
    public function showImage($filename)
    {
        try {
            // Check if file exists
            if (!Storage::disk('local')->exists('images/' . $filename)) {
                return $this->notFoundResponse('Image not found');
            }
            
            // Get file path and mime type
            $path = Storage::disk('local')->path('images/' . $filename);
            $mimeType = mime_content_type($path);
            
            // Return file response
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=3600' // Cache for 1 hour
            ]);
            
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Failed to retrieve image: ' . $e->getMessage());
        }
    }
} 