# Waste Assessment API (Powered by Gemini AI)

This project integrates Google's **Gemini 1.5 Flash** multimodal AI model to intelligently assess, classify, and provide preparation advice for waste based on user-uploaded images. 

This document explains how the Gemini integration works under the hood and how to use the assessment endpoint.

---

## 🧠 How Gemini is Used

### 1. The Controller Logic
The core logic resides in `app/Http/Controllers/WasteAssessmentController.php`. 
When a user uploads an image to the endpoint, the application performs the following steps:
1. **Validation**: Ensures the uploaded file is a valid image (`jpeg, png, jpg, webp`) and under 5MB.
2. **Encoding**: The image file is converted into a **Base64 string**. The Gemini REST API requires images to be sent as inline base64 data rather than multipart file uploads.
3. **Payload Construction**: We build a structured payload containing:
    - **Prompt**: A highly specific prompt instructing Gemini to analyze the image, categorize it, and provide preparation advice, strictly formatted as JSON without markdown blocks.
    - **Image Data**: The base64 string and its corresponding MIME type.
4. **API Call**: The payload is sent via a `POST` request to the `generativelanguage.googleapis.com` endpoint using Laravel's `Http` facade.
5. **Parsing**: The JSON response from Gemini is parsed, any markdown backticks are stripped out, and the data is formatted.
6. **Analytics Logging**: The identified `name` of the waste and the user's `barangay_id` are saved to the `waste_assessments` database table for analytics tracking.

### 2. The Model Configuration
We specifically use the `gemini-1.5-flash` model because it is highly optimized for multimodal tasks (text + images) and provides incredibly fast response times, which is crucial for a smooth user experience.

---

## 🚀 How to Use the Assess Feature

### Prerequisites
Ensure your Gemini API key is configured in your `.env` file:
```env
GEMINI_API_KEY=your_api_key_here
```

### The API Endpoint
**URL**: `/api/assess-waste`  
**Method**: `POST`  
**Content-Type**: `multipart/form-data`

### Request Parameters
| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `image` | File | **Yes** | The image of the waste to be analyzed (max 5MB). |
| `user_id` | UUID | **Yes** | The UUID of the user scanning the waste. |
| `collection_point_id` | UUID | No | The UUID of the collection point (if applicable). |

### Example using cURL
```bash
curl -X POST http://localhost:8000/api/assess-waste \
  -H "Accept: application/json" \
  -F "image=@/path/to/your/plastic-bottle.jpg" \
  -F "user_id=123e4567-e89b-12d3-a456-426614174000"
```

### Example using JavaScript (Fetch API)
```javascript
const fileInput = document.querySelector('input[type="file"]');
const userId = '123e4567-e89b-12d3-a456-426614174000'; 

const formData = new FormData();
formData.append('image', fileInput.files[0]);
formData.append('user_id', userId);

// Note: If calling from a Blade view, include your X-CSRF-TOKEN header
fetch('/api/assess-waste', {
    method: 'POST',
    headers: {
        'Accept': 'application/json'
    },
    body: formData
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```

### Successful Response Format (200 OK)
```json
{
    "success": true,
    "data": {
        "name": "Crushed Plastic Water Bottle",
        "category": "Non-Biodegradable",
        "preparation_advice": "Ensure the bottle is completely empty and rinsed. Crush it to save space and leave the cap on."
    }
}
```

### Error Response Format (422 Unprocessable Entity or 500 Server Error)
```json
{
    "success": false,
    "message": "The image must not be greater than 5120 kilobytes."
}
```
