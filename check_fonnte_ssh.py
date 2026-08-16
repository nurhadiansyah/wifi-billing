import paramiko

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
path = 'domains/dreamnetindonesia.com/public_html'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    # Perintah untuk mengecek curl koneksi ke Fonnte menggunakan token dari .env
    cmd = f"cd {path} && TOKEN=$(grep FONNTE_TOKEN .env | cut -d '=' -f2 | tr -d '\"' | tr -d '\\r') && echo \"Token: $TOKEN\" && curl -s -X POST https://api.fonnte.com/send -H \"Authorization: $TOKEN\" -F 'target=085256486282' -F 'message=Halo! Ini adalah pesan tes untuk mengecek koneksi Fonnte dari server Hostinger Anda.'"
    stdin, stdout, stderr = client.exec_command(cmd)
    
    print("--- FONNTE SSH TEST RESULT ---")
    print(stdout.read().decode('utf-8'))
    
    err = stderr.read().decode('utf-8')
    if err:
        print("--- ERRORS ---")
        print(err)
        
    client.close()
except Exception as e:
    print(f"Error: {e}")
