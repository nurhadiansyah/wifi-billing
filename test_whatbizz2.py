import requests

try:
    response = requests.get('https://whatsbizapi.com', timeout=5)
    print("Status:", response.status_code)
except Exception as e:
    print("Error:", e)
