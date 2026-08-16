import requests

url = "https://api.fonnte.com/send"
headers = {
    "Authorization": "1Nmj6N8Bvk4LELU1Svep"
}
data1 = {
    "target": "085256486282", 
    "message": "Testing dengan format 0852"
}
data2 = {
    "target": "6285256486282", 
    "message": "Testing dengan format 62 tanpa countryCode"
}

try:
    print("Sending data1...")
    r1 = requests.post(url, headers=headers, data=data1)
    print("Response 1:", r1.text)
    
    print("Sending data2...")
    r2 = requests.post(url, headers=headers, data=data2)
    print("Response 2:", r2.text)
except Exception as e:
    print("Error:", e)
