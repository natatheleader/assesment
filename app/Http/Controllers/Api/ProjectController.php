<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Attribute;
use App\Models\AttributeValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProjectResource;

class ProjectController extends BaseController
{
    /**
     * Display a listing of projects with filtering
     */
    public function index(Request $request)
    {
        $query = Project::with(['attributeValues.attribute']);

        // Handle regular attribute filters
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            
            foreach ($filters as $field => $value) {
                // Check if it's a regular attribute
                if (in_array($field, ['name', 'status'])) {
                    if (is_array($value) && isset($value['operator'])) {
                        switch ($value['operator']) {
                            case '>':
                            case '<':
                            case '=':
                                $query->where($field, $value['operator'], $value['value']);
                                break;
                            case 'LIKE':
                                $query->where($field, 'LIKE', "%{$value['value']}%");
                                break;
                        }
                    } else {
                        $query->where($field, '=', $value);
                    }
                } else {
                    // Handle EAV attributes
                    $query->whereHas('attributeValues', function ($q) use ($field, $value) {
                        $q->whereHas('attribute', function ($q2) use ($field) {
                            $q2->where('name', $field);
                        });
                        
                        if (is_array($value) && isset($value['operator'])) {
                            switch ($value['operator']) {
                                case '>':
                                case '<':
                                case '=':
                                    $q->where('value', $value['operator'], $value['value']);
                                    break;
                                case 'LIKE':
                                    $q->where('value', 'LIKE', "%{$value['value']}%");
                                    break;
                            }
                        } else {
                            $q->where('value', '=', $value);
                        }
                    });
                }
            }
        }

        $projects = $query->paginate(10);
        return ProjectResource::collection($projects);
    }

    /**
     * Store a new project
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,pending,completed',
            'attributes' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            \DB::beginTransaction();

            $project = Project::create($request->only(['name', 'status']));

            // Handle dynamic attributes
            if ($request->has('attributes')) {
                foreach ($request->input('attributes') as $attributeName => $value) {
                    $attribute = Attribute::where('name', $attributeName)->first();
                    
                    if (!$attribute) {
                        continue;
                    }

                    // Validate value based on attribute type
                    $isValid = $this->validateAttributeValue($attribute->type, $value);
                    if (!$isValid) {
                        throw new \Exception("Invalid value for attribute: {$attributeName}");
                    }

                    AttributeValues::create([
                        'attribute_id' => $attribute->id,
                        'entity_id' => $project->id,
                        'value' => $value
                    ]);
                }
            }

            \DB::commit();
            return new ProjectResource($project->load('attributeValues.attribute'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        return new ProjectResource($project->load('attributeValues.attribute'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,pending,completed',
            'attributes' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            \DB::beginTransaction();

            $project->update($request->only(['name', 'status']));

            // Handle dynamic attributes
            if ($request->has('attributes')) {
                foreach ($request->input('attributes') as $attributeName => $value) {
                    $attribute = Attribute::where('name', $attributeName)->first();
                    
                    if (!$attribute) {
                        continue;
                    }

                    // Validate value based on attribute type
                    $isValid = $this->validateAttributeValue($attribute->type, $value);
                    if (!$isValid) {
                        throw new \Exception("Invalid value for attribute: {$attributeName}");
                    }

                    AttributeValues::updateOrCreate(
                        [
                            'attribute_id' => $attribute->id,
                            'entity_id' => $project->id
                        ],
                        ['value' => $value]
                    );
                }
            }

            \DB::commit();
            return new ProjectResource($project->load('attributeValues.attribute'));

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        try {
            \DB::beginTransaction();
            
            // Delete related attribute values
            $project->attributeValues()->delete();
            
            // Delete the project
            $project->delete();
            
            \DB::commit();
            return response()->json(null, 204);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate attribute value based on type
     */
    private function validateAttributeValue($type, $value)
    {
        switch ($type) {
            case 'number':
                return is_numeric($value);
            case 'date':
                return strtotime($value) !== false;
            case 'select':
                // Assuming valid options are stored somewhere
                return true; // Implement actual validation
            case 'text':
                return is_string($value);
            default:
                return false;
        }
    }
}