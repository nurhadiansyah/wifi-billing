import paramiko
import sys

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
path = 'domains/dreamnetindonesia.com/public_html'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    cmd = f"cd {path} && tail -n 200 storage/logs/laravel.log"
    
    stdin, stdout, stderr = client.exec_command(cmd)
    
    print("=== STDOUT ===")
    print(stdout.read().decode('utf-8'))
    
    print("=== STDERR ===")
    print(stderr.read().decode('utf-8'))

    client.close()
except Exception as e:
    print(f"Error: {e}")
