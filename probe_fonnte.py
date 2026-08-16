import requests

url = "https://api.fonnte.com/device"
headers = {
    "Authorization": "1Nmj6N8Bvk4LELU1Svep"
}

try:
    response = requests.post(url, headers=headers)
    print("Status:", response.status_code)
    print("Response:", response.text)
except Exception as e:
    print("Error:", e)
