<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceJob;
use App\Http\Resources\MaintenanceJobResource;
use Illuminate\Http\Request;

/**
 * @group Maintenance Job Management
 *
 * APIs for managing maintenance job assignments and tracking
 *
 * @authenticated
 */
class MaintenanceJobController extends Controller
{
    /**
     * List maintenance jobs
     *
     * Retrieve a paginated list of maintenance jobs with filtering.
     * Service providers see their assigned jobs. Landlords see all jobs for their properties.
     *
     * @queryParam request_id string Filter by maintenance request UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam service_provider_id string Filter by service provider UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @queryParam status string Filter by status (assigned, in_progress, on_hold, completed, cancelled). Example: in_progress
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     *
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "job_number": "MJ-2025-001",
     *       "request_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "service_provider_id": "9d5e8c7a-2222-3333-4444-555555555555",
     *       "status": "in_progress",
     *       "scheduled_date": "2025-11-15",
     *       "estimated_cost": 500.00
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = MaintenanceJob::with(['maintenanceRequest', 'serviceProvider']);

        if ($request->filled('request_id')) {
            $query->where('request_id', $request->request_id);
        }

        if ($request->filled('service_provider_id')) {
            $query->where('service_provider_id', $request->service_provider_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min($request->input('per_page', 20), 100);
        $jobs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return MaintenanceJobResource::collection($jobs);
    }

    /**
     * Assign a maintenance job
     *
     * Create and assign a maintenance job to a service provider.
     * Only landlords and admins can assign jobs.
     *
     * @bodyParam request_id string required Maintenance request UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @bodyParam service_provider_id string required Service provider UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @bodyParam scheduled_date date Scheduled service date. Example: 2025-11-15
     * @bodyParam scheduled_time string Scheduled time slot. Example: 10:00-12:00
     * @bodyParam estimated_cost numeric Estimated cost. Example: 500.00
     * @bodyParam estimated_duration integer Estimated duration in hours. Example: 2
     * @bodyParam work_description text Description of work to be done. Example: Fix leaking faucet and check water pressure
     * @bodyParam priority string Priority (low, medium, high, urgent). Example: high
     * @bodyParam special_instructions text Special instructions for service provider. Example: Call tenant 1 hour before arrival
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "job_number": "MJ-2025-001",
     *     "status": "assigned"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:maintenance_requests,id',
            'service_provider_id' => 'required|exists:service_providers,id',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|string|max:50',
            'estimated_cost' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:1',
            'work_description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'special_instructions' => 'nullable|string',
        ]);

        $job = MaintenanceJob::create($validated);

        // Update maintenance request status
        $job->maintenanceRequest->update(['status' => 'in_progress']);

        return new MaintenanceJobResource($job);
    }

    /**
     * Get maintenance job details
     *
     * Retrieve detailed information about a specific maintenance job.
     *
     * @urlParam id string required Maintenance job UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "job_number": "MJ-2025-001",
     *     "status": "completed",
     *     "actual_cost": 450.00,
     *     "completion_notes": "Fixed faucet and replaced washers",
     *     "maintenance_request": {
     *       "request_number": "MR-2025-001"
     *     },
     *     "service_provider": {
     *       "company_name": "ABC Plumbing"
     *     }
     *   }
     * }
     */
    public function show(string $id)
    {
        $job = MaintenanceJob::with(['maintenanceRequest', 'serviceProvider'])->findOrFail($id);

        return new MaintenanceJobResource($job);
    }

    /**
     * Update maintenance job
     *
     * Update job details or status. Service providers can update their assigned jobs.
     * Landlords and admins can update any job.
     *
     * @urlParam id string required Maintenance job UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @bodyParam scheduled_date date Scheduled date. Example: 2025-11-20
     * @bodyParam scheduled_time string Scheduled time. Example: 14:00-16:00
     * @bodyParam estimated_cost numeric Estimated cost. Example: 600.00
     * @bodyParam estimated_duration integer Estimated duration in hours. Example: 3
     * @bodyParam actual_start_time datetime Actual start time. Example: 2025-11-15 10:30:00
     * @bodyParam actual_end_time datetime Actual end time. Example: 2025-11-15 12:30:00
     * @bodyParam actual_cost numeric Actual cost. Example: 450.00
     * @bodyParam work_description text Work description. Example: Updated work scope
     * @bodyParam completion_notes text Completion notes. Example: Work completed successfully
     * @bodyParam materials_used array Materials used. Example: [{"item": "Washer", "quantity": 2, "cost": 10.00}]
     * @bodyParam photos array Job photos. Example: ["jobs/before.jpg", "jobs/after.jpg"]
     * @bodyParam status string Status (assigned, in_progress, on_hold, completed, cancelled). Example: completed
     *
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "status": "completed",
     *     "actual_cost": 450.00
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $job = MaintenanceJob::findOrFail($id);

        $validated = $request->validate([
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|string|max:50',
            'estimated_cost' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:1',
            'actual_start_time' => 'nullable|date',
            'actual_end_time' => 'nullable|date|after:actual_start_time',
            'actual_cost' => 'nullable|numeric|min:0',
            'work_description' => 'nullable|string',
            'completion_notes' => 'nullable|string',
            'materials_used' => 'nullable|array',
            'photos' => 'nullable|array',
            'status' => 'nullable|in:assigned,in_progress,on_hold,completed,cancelled',
        ]);

        $job->update($validated);

        // Update maintenance request status if job is completed
        if (isset($validated['status']) && $validated['status'] === 'completed') {
            $job->maintenanceRequest->update(['status' => 'completed']);
        }

        return new MaintenanceJobResource($job);
    }

    /**
     * Delete maintenance job
     *
     * Delete a maintenance job. Only assigned jobs that haven't started can be deleted.
     *
     * @urlParam id string required Maintenance job UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @response 200 scenario="Success" {
     *   "message": "Maintenance job deleted successfully."
     * }
     *
     * @response 409 scenario="Cannot Delete" {
     *   "message": "Cannot delete jobs that are in progress or completed."
     * }
     */
    public function destroy(string $id)
    {
        $job = MaintenanceJob::findOrFail($id);

        if (in_array($job->status, ['in_progress', 'completed'])) {
            return response()->json([
                'message' => __('messages.cannot_delete_active_jobs')
            ], 409);
        }

        $job->delete();

        return response()->json([
            'message' => __('messages.maintenance_job_deleted')
        ]);
    }

    /**
     * Accept a maintenance job
     *
     * Service provider accepts an assigned job.
     * Updates job status to 'accepted' and maintenance request status to 'assigned'.
     *
     * @urlParam job string required Maintenance job UUID or ID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @response 200 scenario="Success" {
     *   "message": "Job accepted successfully",
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "job_number": "MJ-2025-000001",
     *     "status": "accepted"
     *   }
     * }
     *
     * @response 422 scenario="Invalid Status" {
     *   "message": "Job cannot be accepted. Current status does not allow acceptance."
     * }
     */
    public function accept(Request $request, string $id)
    {
        $job = MaintenanceJob::where('id', $id)
            ->orWhere('uuid', $id)
            ->with(['maintenanceRequest', 'serviceProvider'])
            ->firstOrFail();

        // Verify job is in assigned or rejected state (allow re-acceptance)
        if (!in_array($job->status, ['assigned', 'rejected'])) {
            return response()->json([
                'message' => 'Job cannot be accepted. Current status does not allow acceptance.',
                'current_status' => $job->status
            ], 422);
        }

        // Update job status
        $job->update([
            'status' => 'accepted',
        ]);

        // Update maintenance request status to assigned (job has been accepted by provider)
        $job->maintenanceRequest->update([
            'status' => 'assigned'
        ]);

        return response()->json([
            'message' => 'Job accepted successfully',
            'data' => new MaintenanceJobResource($job)
        ]);
    }

    /**
     * Reject a maintenance job
     *
     * Service provider rejects an assigned job with a reason.
     * Updates job status to 'rejected' and maintenance request status back to 'pending'.
     *
     * @urlParam job string required Maintenance job UUID or ID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @bodyParam reason string Rejection reason. Example: Schedule conflict - unable to fulfill requested date
     *
     * @response 200 scenario="Success" {
     *   "message": "Job rejected successfully",
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "status": "rejected",
     *     "notes": "Rejection reason stored in notes"
     *   }
     * }
     *
     * @response 422 scenario="Invalid Status" {
     *   "message": "Job cannot be rejected. Current status does not allow rejection."
     * }
     */
    public function reject(Request $request, string $id)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000'
        ]);

        $job = MaintenanceJob::where('id', $id)
            ->orWhere('uuid', $id)
            ->with(['maintenanceRequest', 'serviceProvider'])
            ->firstOrFail();

        // Verify job is in assigned or accepted state
        if (!in_array($job->status, ['assigned', 'accepted'])) {
            return response()->json([
                'message' => 'Job cannot be rejected. Current status does not allow rejection.',
                'current_status' => $job->status
            ], 422);
        }

        // Prepare rejection note
        $rejectionNote = isset($validated['reason'])
            ? "Rejection reason: " . $validated['reason']
            : "Job rejected by service provider";

        // Append to existing notes or create new
        $currentNotes = $job->notes;
        $updatedNotes = $currentNotes
            ? $currentNotes . "\n\n" . $rejectionNote
            : $rejectionNote;

        // Update job status
        $job->update([
            'status' => 'rejected',
            'notes' => $updatedNotes,
        ]);

        // Update maintenance request status to approved (needs reassignment)
        $job->maintenanceRequest->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'message' => 'Job rejected successfully',
            'data' => new MaintenanceJobResource($job)
        ]);
    }

    /**
     * Start working on a maintenance job
     *
     * Service provider starts work on an accepted job.
     * Updates job status to 'in_progress' and records start time.
     *
     * @urlParam job string required Maintenance job UUID or ID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @response 200 scenario="Success" {
     *   "message": "Job started successfully",
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "status": "in_progress",
     *     "started_at": "2025-11-20 10:30:00"
     *   }
     * }
     *
     * @response 422 scenario="Invalid Status" {
     *   "message": "Job must be accepted before starting work."
     * }
     */
    public function start(Request $request, string $id)
    {
        $job = MaintenanceJob::where('id', $id)
            ->orWhere('uuid', $id)
            ->with(['maintenanceRequest', 'serviceProvider'])
            ->firstOrFail();

        // Verify job is accepted
        if ($job->status !== 'accepted') {
            return response()->json([
                'message' => 'Job must be accepted before starting work.',
                'current_status' => $job->status
            ], 422);
        }

        // Update job status and record start time
        $job->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        // Update maintenance request status
        $job->maintenanceRequest->update([
            'status' => 'in_progress'
        ]);

        return response()->json([
            'message' => 'Job started successfully',
            'data' => new MaintenanceJobResource($job)
        ]);
    }

    /**
     * Complete a maintenance job
     *
     * Service provider marks job as completed with optional completion details.
     * Updates job status to 'completed' and records completion time.
     *
     * @urlParam job string required Maintenance job UUID or ID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     *
     * @bodyParam completion_notes string Completion notes/summary. Example: Replaced faulty faucet and checked all connections
     * @bodyParam final_amount numeric Final cost of the work. Example: 450.00
     * @bodyParam completion_photos array Array of photo URLs. Example: ["jobs/photo1.jpg", "jobs/photo2.jpg"]
     * @bodyParam work_items array Array of work items performed. Example: [{"description": "Faucet replacement", "quantity": 1, "rate": 200, "amount": 200}]
     *
     * @response 200 scenario="Success" {
     *   "message": "Job completed successfully",
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "status": "completed",
     *     "completed_at": "2025-11-20 15:30:00",
     *     "final_amount": 450.00
     *   }
     * }
     *
     * @response 422 scenario="Invalid Status" {
     *   "message": "Job must be in progress to mark as completed."
     * }
     */
    public function complete(Request $request, string $id)
    {
        $validated = $request->validate([
            'completion_notes' => 'nullable|string|max:2000',
            'final_amount' => 'nullable|numeric|min:0',
            'completion_photos' => 'nullable|array',
            'completion_photos.*' => 'string',
            'work_items' => 'nullable|array',
            'work_items.*.description' => 'required|string',
            'work_items.*.quantity' => 'required|numeric|min:0',
            'work_items.*.rate' => 'required|numeric|min:0',
            'work_items.*.amount' => 'required|numeric|min:0',
        ]);

        $job = MaintenanceJob::where('id', $id)
            ->orWhere('uuid', $id)
            ->with(['maintenanceRequest', 'serviceProvider'])
            ->firstOrFail();

        // Verify job is in progress
        if ($job->status !== 'in_progress') {
            return response()->json([
                'message' => 'Job must be in progress to mark as completed.',
                'current_status' => $job->status
            ], 422);
        }

        // Prepare update data
        $updateData = [
            'status' => 'completed',
            'completed_at' => now(),
        ];

        // Add optional fields if provided
        if (isset($validated['final_amount'])) {
            $updateData['final_amount'] = $validated['final_amount'];
        }

        if (isset($validated['completion_photos'])) {
            $updateData['completion_photos'] = $validated['completion_photos'];
        }

        if (isset($validated['work_items'])) {
            $updateData['work_items'] = $validated['work_items'];
        }

        // Append completion notes to existing notes
        if (isset($validated['completion_notes'])) {
            $currentNotes = $job->notes;
            $completionNote = "Completion notes: " . $validated['completion_notes'];
            $updateData['notes'] = $currentNotes
                ? $currentNotes . "\n\n" . $completionNote
                : $completionNote;
        }

        // Update job
        $job->update($updateData);

        // Update maintenance request
        $job->maintenanceRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Job completed successfully',
            'data' => new MaintenanceJobResource($job)
        ]);
    }
}
