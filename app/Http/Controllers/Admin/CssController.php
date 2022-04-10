<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class CssController extends AdminController
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No file',
            ]);
        }

        if ($request->file('file')->getClientMimeType() !== 'text/css') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid file format',
            ]);
        }

        $path = $request->file->storePubliclyAs($this->model->getAttrDir('path'), $this->model->getFileNameById().'.css');

        return response()->json([
            'status' => 'ok',
            'message' => $this->model->getPublicPathByStorage($path)
        ]);
    }
}
