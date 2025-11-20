# API Testing Guide - Maintenance Job Actions

## Overview
This guide provides curl commands to test the newly implemented maintenance job action endpoints.

## Prerequisites

1. **Backend Server Running**
   ```bash
   cd /Users/malahmed/Development/The\ Lobby/main_server/the_lobby
   php artisan serve
   ```

2. **Get Authentication Token**
   ```bash
   # Login as a service provider
   curl -X POST http://localhost:8000/api/v1/login \
     -H "Content-Type: application/json" \
     -d '{
       "email": "provider@example.com",
       "password": "password"
     }'
   ```
   
   Save the token from the response.

3. **Create Test Data (Optional)**
   ```bash
   # Run seeders to create test maintenance jobs
   php artisan db:seed --class=MaintenanceJobSeeder
   ```

## Test Endpoints

### 1. List Maintenance Jobs
Get jobs assigned to the service provider:

```bash
curl -X GET "http://localhost:8000/api/v1/maintenance-jobs?status=assigned" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
  "data": [
    {
      "id": "uuid-here",
      "job_number": "MJ-2025-000001",
      "status": "assigned",
      "scheduled_date": "2025-11-25",
      ...
    }
  ]
}
```

### 2. Accept a Job

```bash
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/{job-id}/accept" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
  "message": "Job accepted successfully",
  "data": {
    "id": "uuid",
    "status": "accepted",
    ...
  }
}
```

**Error Cases:**
- Job not in 'assigned' or 'rejected' status: Returns 422
- Job not found: Returns 404

### 3. Reject a Job

```bash
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/{job-id}/reject" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "reason": "Schedule conflict - unable to fulfill on requested date"
  }'
```

**Expected Response:**
```json
{
  "message": "Job rejected successfully",
  "data": {
    "id": "uuid",
    "status": "rejected",
    "notes": "Rejection reason: Schedule conflict..."
  }
}
```

**Error Cases:**
- Job not in 'assigned' or 'accepted' status: Returns 422

### 4. Start a Job

```bash
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/{job-id}/start" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

**Expected Response:**
```json
{
  "message": "Job started successfully",
  "data": {
    "id": "uuid",
    "status": "in_progress",
    "started_at": "2025-11-20 10:30:00"
  }
}
```

**Error Cases:**
- Job not in 'accepted' status: Returns 422

### 5. Complete a Job (Minimal)

```bash
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/{job-id}/complete" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 6. Complete a Job (With Details)

```bash
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/{job-id}/complete" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "completion_notes": "Replaced faulty faucet and checked all water connections. No leaks detected.",
    "final_amount": 450.00,
    "completion_photos": [
      "storage/jobs/photo1.jpg",
      "storage/jobs/photo2.jpg"
    ],
    "work_items": [
      {
        "description": "Faucet replacement",
        "quantity": 1,
        "rate": 200.00,
        "amount": 200.00
      },
      {
        "description": "Washers and seals",
        "quantity": 5,
        "rate": 10.00,
        "amount": 50.00
      },
      {
        "description": "Labor (2 hours)",
        "quantity": 2,
        "rate": 100.00,
        "amount": 200.00
      }
    ]
  }'
```

**Expected Response:**
```json
{
  "message": "Job completed successfully",
  "data": {
    "id": "uuid",
    "status": "completed",
    "completed_at": "2025-11-20 15:30:00",
    "final_amount": 450.00,
    "work_items": [...],
    "completion_photos": [...]
  }
}
```

**Error Cases:**
- Job not in 'in_progress' status: Returns 422

## Full Job Lifecycle Test

Test the complete workflow:

```bash
# 1. Get a job ID (assigned status)
JOB_ID="your-job-uuid-here"
TOKEN="your-auth-token"

# 2. Accept the job
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/$JOB_ID/accept" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

# Wait a moment, then start the job
sleep 2

# 3. Start the job
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/$JOB_ID/start" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

# Simulate work being done
sleep 5

# 4. Complete the job
curl -X POST "http://localhost:8000/api/v1/maintenance-jobs/$JOB_ID/complete" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "completion_notes": "Job completed successfully",
    "final_amount": 300.00
  }'

# 5. Verify final status
curl -X GET "http://localhost:8000/api/v1/maintenance-jobs/$JOB_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

## Status Transitions

Valid status transitions:

```
assigned → accepted → in_progress → completed
    ↓
rejected (can be re-accepted if reassigned)
```

Invalid transitions (will return 422):
- `completed` → any status (cannot modify completed jobs)
- `assigned` → `in_progress` (must accept first)
- `rejected` → `in_progress` (must accept first)
- `accepted` → `completed` (must start first)

## Testing with Postman

Import these as Postman requests:

1. **Create Collection**: "Maintenance Jobs API"
2. **Add Environment Variable**: `base_url = http://localhost:8000/api/v1`
3. **Add Environment Variable**: `token = your-auth-token`

**Collection Structure:**
```
Maintenance Jobs API/
├── Auth/
│   └── Login (POST {{base_url}}/login)
├── Jobs/
│   ├── List Jobs (GET {{base_url}}/maintenance-jobs)
│   ├── Get Job (GET {{base_url}}/maintenance-jobs/:id)
│   ├── Accept Job (POST {{base_url}}/maintenance-jobs/:id/accept)
│   ├── Reject Job (POST {{base_url}}/maintenance-jobs/:id/reject)
│   ├── Start Job (POST {{base_url}}/maintenance-jobs/:id/start)
│   └── Complete Job (POST {{base_url}}/maintenance-jobs/:id/complete)
```

## Database Verification

Check the database directly to verify changes:

```bash
# Connect to MySQL
mysql -u your_user -p the_lobby_db

# Check job status
SELECT id, job_number, status, started_at, completed_at 
FROM maintenance_jobs 
WHERE id = YOUR_JOB_ID;

# Check maintenance request status
SELECT r.id, r.request_number, r.status 
FROM maintenance_requests r
JOIN maintenance_jobs j ON j.maintenance_request_id = r.id
WHERE j.id = YOUR_JOB_ID;
```

## Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Token expired or invalid
   - Solution: Login again and get a new token

2. **404 Not Found**
   - Job ID doesn't exist or incorrect
   - Solution: Verify job ID from list endpoint

3. **422 Invalid Status**
   - Job not in correct status for the action
   - Solution: Check current job status and follow valid transitions

4. **500 Server Error**
   - Check Laravel logs: `tail -f storage/logs/laravel.log`
   - Common causes: Database connection, missing relationships

## Success Criteria

✅ All endpoints return 200 on valid requests
✅ Job status transitions follow the correct flow
✅ Maintenance request status updates in sync with job status
✅ Timestamps (started_at, completed_at) are recorded correctly
✅ Notes and completion data are stored properly
✅ Invalid status transitions return 422 with clear error messages

## Next Steps

After successful testing:
1. Test with the Flutter providers app
2. Verify push notifications are sent (if implemented)
3. Test with multiple concurrent jobs
4. Performance test with large datasets
