# Report an Issue — Backend API Documentation

This document covers the **Report an Issue** feature, which allows users to submit photos and descriptions of **illegal dumping** or **missed garbage pickups**. Both endpoints use **Gemini AI** to automatically verify the uploaded photo and append an AI analysis to the report for barangay dashboard review.

---

## 🏗️ How It Works (Architecture)

### Flow Diagram
```
User uploads photo + details
        │
        ▼
  ReportIssueController
        │
        ├── 1. Validates the request (image, user_id, location/session data)
        ├── 2. Saves the image to local storage (public disk)
        ├── 3. Sends the image to Gemini AI for verification
        │       └── AI returns a short analysis (e.g., "Image shows illegal dumping of household waste...")
        ├── 4. Combines user description + AI analysis into one field
        ├── 5. Saves the report to the database (status: 'pending')
        └── 6. Returns the report data (including AI analysis) to the client
```

### Key Design Decisions
- **AI is advisory, not authoritative.** The AI analysis is appended under an `[AI Verification]` tag. Barangay admins make the final call on the dashboard.
- **Graceful degradation.** If the Gemini API is down or returns an error, the report is still saved. The AI field will contain a fallback message like `"AI verification unavailable at this time."`.
- **Separate storage paths.** Violation photos go to `storage/reports/violations/`, missed collection photos go to `storage/reports/missed/`.

---

## 📡 API Endpoints

### 1. Report Illegal Dumping (Violation)

**URL:** `POST /api/reports/violation`  
**Content-Type:** `multipart/form-data`

#### Request Parameters

| Parameter     | Type    | Required | Description                                           |
| :------------ | :------ | :------- | :---------------------------------------------------- |
| `image`       | File    | **Yes**  | Photo evidence of the illegal dumping (max 5MB).      |
| `user_id`     | UUID    | **Yes**  | The UUID of the user submitting the report.           |
| `barangay_id` | UUID    | **Yes**  | The UUID of the barangay where the dumping occurred.  |
| `latitude`    | Numeric | **Yes**  | GPS latitude of the dumping location.                 |
| `longitude`   | Numeric | **Yes**  | GPS longitude of the dumping location.                |
| `description` | String  | No       | Optional user description of the issue (max 2000 chars). |

#### Example using JavaScript (Fetch API)

```javascript
const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('user_id', '550e8400-e29b-41d4-a716-446655440000');
formData.append('barangay_id', '7c9e6679-7425-40de-944b-e07fc1f90ae7');
formData.append('latitude', 14.5995);
formData.append('longitude', 120.9842);
formData.append('description', 'Illegal dumping of construction debris near the creek.');

fetch('/api/reports/violation', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        // Include CSRF token if calling from a Blade view:
        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: formData
})
.then(res => res.json())
.then(data => {
    if (data.success) {
        console.log('Report submitted!', data.data);
        console.log('AI Analysis:', data.data.ai_analysis);
    } else {
        console.error('Error:', data.message);
    }
});
```

#### Success Response (200 OK)

```json
{
    "success": true,
    "data": {
        "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
        "photo_url": "/storage/reports/violations/abc123.jpg",
        "description": "Illegal dumping of construction debris near the creek.\n\n[AI Verification]\nThe image shows a significant pile of construction waste including concrete blocks and wooden planks dumped near a waterway. This constitutes illegal dumping and poses environmental contamination risks. Estimated volume is approximately 2-3 cubic meters.",
        "ai_analysis": "The image shows a significant pile of construction waste including concrete blocks and wooden planks dumped near a waterway. This constitutes illegal dumping and poses environmental contamination risks. Estimated volume is approximately 2-3 cubic meters.",
        "status": "pending"
    }
}
```

---

### 2. Report Missed Collection (Missed Pickup)

**URL:** `POST /api/reports/missed-collection`  
**Content-Type:** `multipart/form-data`

#### Request Parameters

| Parameter             | Type   | Required | Description                                               |
| :-------------------- | :----- | :------- | :-------------------------------------------------------- |
| `image`               | File   | **Yes**  | Photo evidence of uncollected garbage (max 5MB).          |
| `user_id`             | UUID   | **Yes**  | The UUID of the user submitting the report.               |
| `session_id`          | UUID   | **Yes**  | The UUID of the collection session that was missed.       |
| `collection_point_id` | UUID   | **Yes**  | The UUID of the collection point that was skipped.        |
| `notes`               | String | No       | Optional notes from the user about the issue (max 2000 chars). |

#### Example using JavaScript (Fetch API)

```javascript
const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('user_id', '550e8400-e29b-41d4-a716-446655440000');
formData.append('session_id', 'a1b2c3d4-e5f6-7890-abcd-ef1234567890');
formData.append('collection_point_id', 'f47ac10b-58cc-4372-a567-0e02b2c3d479');
formData.append('notes', 'The garbage truck skipped our street today.');

fetch('/api/reports/missed-collection', {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
    },
    body: formData
})
.then(res => res.json())
.then(data => {
    if (data.success) {
        console.log('Report submitted!', data.data);
        console.log('AI Analysis:', data.data.ai_analysis);
    } else {
        console.error('Error:', data.message);
    }
});
```

#### Success Response (200 OK)

```json
{
    "success": true,
    "data": {
        "id": "1b9d6bcd-bbfd-4b2d-9b5d-ab8dfbbd4bed",
        "photo_url": "/storage/reports/missed/xyz789.jpg",
        "notes": "The garbage truck skipped our street today.\n\n[AI Verification]\nThe image shows multiple filled garbage bags placed at what appears to be a designated pickup area. The bags appear to have been sitting for some time based on slight weathering. Volume estimate: 5-6 standard garbage bags of mixed household waste.",
        "ai_analysis": "The image shows multiple filled garbage bags placed at what appears to be a designated pickup area. The bags appear to have been sitting for some time based on slight weathering. Volume estimate: 5-6 standard garbage bags of mixed household waste.",
        "status": "pending"
    }
}
```

---

## ⚠️ Error Responses

### Validation Error (422 Unprocessable Entity)
Returned when required fields are missing or invalid.

```json
{
    "message": "The image field is required.",
    "errors": {
        "image": ["The image field is required."]
    }
}
```

### Server Error (500 Internal Server Error)
Returned when an unexpected error occurs during processing.

```json
{
    "success": false,
    "message": "SQLSTATE[23000]: Integrity constraint violation..."
}
```

---

## 🗂️ Database Tables Used

### `violation_reports`
| Column        | Type                                             | Description                              |
| :------------ | :----------------------------------------------- | :--------------------------------------- |
| `id`          | UUID (PK)                                        | Auto-generated UUID                      |
| `reported_by` | UUID (FK → users)                                | The user who filed the report            |
| `barangay_id` | UUID (FK → barangays)                            | The barangay where the violation occurred|
| `latitude`    | decimal(10,7)                                    | GPS latitude                             |
| `longitude`   | decimal(10,7)                                    | GPS longitude                            |
| `photo_url`   | string (nullable)                                | Path to the uploaded photo               |
| `description` | text                                             | User description + AI verification       |
| `status`      | enum: `pending`, `under_review`, `fined`, `dismissed` | Current review status              |
| `reported_at` | timestamp                                        | When the report was submitted            |

### `missed_collection_reports`
| Column               | Type                                             | Description                              |
| :------------------- | :----------------------------------------------- | :--------------------------------------- |
| `id`                 | UUID (PK)                                        | Auto-generated UUID                      |
| `session_id`         | UUID (FK → collection_sessions)                  | The collection session that was missed   |
| `collection_point_id`| UUID (FK → collection_points)                    | The specific pickup point skipped        |
| `reported_by`        | UUID (FK → users)                                | The user who filed the report            |
| `photo_url`          | string (nullable)                                | Path to the uploaded photo               |
| `notes`              | text (nullable)                                  | User notes + AI verification             |
| `status`             | enum: `pending`, `acknowledged`, `resolved`      | Current review status                    |
| `reported_at`        | timestamp                                        | When the report was submitted            |

---

## 🧠 How the AI Verification Works

Both endpoints call a shared `analyzeWithGemini()` method inside `ReportIssueController`. Here's what happens:

1. The uploaded image is **Base64 encoded** and sent to Google's `gemini-flash-latest` model.
2. A **context-specific prompt** is used depending on the report type:
   - **Violation**: *"Does this image show illegal garbage dumping?"*
   - **Missed Collection**: *"Does this image show uncollected garbage at a pickup point?"*
3. The AI returns a **2-3 sentence factual analysis**.
4. The analysis is appended to the user's text under the `[AI Verification]` tag.
5. If the API fails, a fallback message is used — **the report is never lost**.

### File Locations
- **Controller**: `app/Http/Controllers/ReportIssueController.php`
- **Models**: `app/Models/ViolationReport.php`, `app/Models/MissedCollectionReport.php`
- **Routes**: Defined in `routes/web.php`
