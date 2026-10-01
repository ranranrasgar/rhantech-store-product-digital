import telnetlib
import time

HOST = '192.168.101.7'
USER = 'admin+c'
PASS = '1'

try:
    tn = telnetlib.Telnet(HOST, timeout=5)
    
    # Read until Login prompt
    tn.read_until(b"Login: ", timeout=3)
    tn.write(USER.encode('ascii') + b"\n")
    
    # Read until Password prompt
    tn.read_until(b"Password: ", timeout=3)
    tn.write(PASS.encode('ascii') + b"\n")
    
    # Wait for the prompt
    time.sleep(1)
    # Read the banner
    print("--- Login ---")
    print(tn.read_very_eager().decode('ascii', errors='ignore'))
    
    # Run system resource print
    tn.write(b"/system resource print\n")
    time.sleep(1)
    print("--- System Resources ---")
    print(tn.read_very_eager().decode('ascii', errors='ignore'))
    
    # Check interfaces
    tn.write(b"/interface print\n")
    time.sleep(1)
    print("--- Interfaces ---")
    print(tn.read_very_eager().decode('ascii', errors='ignore'))
    
    # Check recent logs
    tn.write(b"/log print without-paging tail=10\n")
    time.sleep(1)
    print("--- Logs ---")
    print(tn.read_very_eager().decode('ascii', errors='ignore'))
    
    # Exit
    tn.write(b"quit\n")
    tn.close()

except Exception as e:
    print(f"Error connecting: {e}")
