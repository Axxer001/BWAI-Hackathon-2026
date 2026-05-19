<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseApiController extends Controller
{
    use ApiResponse;

    
    protected string $model;

    
    protected array $rules = [];

    
    protected array $updateRules = [];

    
    protected ?string $resourceClass = null;

    
    public function index(Request $request): JsonResponse
    {
        $query = $this->model::query();

        
        $fillable = (new $this->model)->getFillable();
        foreach ($request->only($fillable) as $field => $value) {
            if ($value !== null && $value !== '') {
                $query->where($field, $value);
            }
        }

        
        $sortBy = $request->get('sort_by', 'id');
        $sortDir = $request->get('sort_dir', 'desc');
        if (in_array($sortBy, $fillable) || $sortBy === 'id') {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        
        if ($request->has('paginate')) {
            $perPage = (int) $request->get('paginate', 15);
            $records = $query->paginate($perPage);
        } else {
            $records = $query->get();
        }
        
        if ($this->resourceClass) {
            if ($request->has('paginate')) {
                return $this->successResponse($this->resourceClass::collection($records)->response()->getData(true));
            }
            return $this->successResponse($this->resourceClass::collection($records));
        }

        return $this->successResponse($records);
    }

    
    public function store(Request $request): JsonResponse
    {
        $rules = !empty($this->rules) ? $this->rules : [];
        $validated = $request->validate($rules);

        $record = $this->model::create($validated);

        if ($this->resourceClass) {
            return $this->successResponse(new $this->resourceClass($record), 'Created successfully.', 201);
        }

        return $this->successResponse($record, 'Created successfully.', 201);
    }

    
    public function show(mixed $id): JsonResponse
    {
        $record = $this->model::findOrFail($id);

        if ($this->resourceClass) {
            return $this->successResponse(new $this->resourceClass($record));
        }

        return $this->successResponse($record);
    }

    
    public function update(Request $request, mixed $id): JsonResponse
    {
        $record = $this->model::findOrFail($id);
        
        $rules = !empty($this->updateRules) ? $this->updateRules : $this->rules;
        $validated = $request->validate($rules);

        $record->update($validated);

        if ($this->resourceClass) {
            return $this->successResponse(new $this->resourceClass($record), 'Updated successfully.');
        }

        return $this->successResponse($record, 'Updated successfully.');
    }

    
    public function destroy(mixed $id): JsonResponse
    {
        $record = $this->model::findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'Deleted successfully.');
    }
}
