import paramiko
import sys
import re
import os

hostname = '185.232.14.228'
port = 65002
username = 'u834700752'
password = 'Alifah.23'
path = 'domains/dreamnetindonesia.com/public_html'

new_wa_token = '1Nmj6N8Bvk4LELU1Svep'

try:
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    client.connect(hostname, port=port, username=username, password=password, timeout=10)
    
    sftp = client.open_sftp()
    
    # Read remote .env
    remote_env_path = f"{path}/.env"
    with sftp.file(remote_env_path, 'r') as f:
        env_content = f.read().decode('utf-8')
        
    # Replace FONNTE_TOKEN
    if 'FONNTE_TOKEN=' in env_content:
        env_content = re.sub(r'FONNTE_TOKEN=.*', f'FONNTE_TOKEN="{new_wa_token}"', env_content)
    elif 'WHATBIZZ_TOKEN=' in env_content:
        env_content = re.sub(r'WHATBIZZ_TOKEN=.*', f'FONNTE_TOKEN="{new_wa_token}"', env_content)
    else:
        env_content += f'\nFONNTE_TOKEN="{new_wa_token}"\n'
    
    # Write back
    with sftp.file(remote_env_path, 'w') as f:
        f.write(env_content)
        
    print("Remote .env updated with new FONNTE_TOKEN.")
    
    sftp.close()
    
    # Clear cache
    cmd = f"cd {path} && php artisan config:clear && php artisan cache:clear && php artisan route:clear"
    client.exec_command(cmd)
    
    client.close()
    print("Caches cleared successfully. Deployment complete!")
except Exception as e:
    print(f"Error: {e}")
