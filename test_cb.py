import requests

url = "https://dreamnetindonesia.com/tripay/callback"
headers = {
    "User-Agent": "Tripay/1.0",
    "Content-Type": "application/json"
}

try:
    response = requests.post(url, json={"event": "payment_status"}, headers=headers)
    print("Status Code:", response.status_code)
    print("Response Body:", response.text[:500])
except Exception as e:
    print("Error:", e)
