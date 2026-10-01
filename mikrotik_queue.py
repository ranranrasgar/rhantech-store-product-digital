import urllib.request, json
from base64 import b64encode

def get_mikrotik(path):
    url = f'http://192.168.101.7/rest/{path}'
    request = urllib.request.Request(url)
    auth = b64encode(b'admin:1').decode('ascii')
    request.add_header('Authorization', 'Basic ' + auth)
    try:
        res = urllib.request.urlopen(request, timeout=5)
        return json.loads(res.read().decode('utf-8'))
    except Exception as e:
        return {'error': str(e)}

print('--- Simple Queues ---')
queues = get_mikrotik('queue/simple')
if isinstance(queues, list):
    for q in queues:
        name = q.get('name')
        target = q.get('target')
        rate = q.get('rate', '0/0')
        bytes_ = q.get('bytes', '0/0')
        limit = q.get('max-limit', '0/0')
        print(f"{name} ({target}): Limit {limit}, Rate {rate}, Total Bytes {bytes_}")
else:
    print(queues)

print('\n--- Firewall Filter (Fasttrack Check) ---')
fw = get_mikrotik('ip/firewall/filter')
if isinstance(fw, list):
    for f in fw:
        if f.get('action') == 'fasttrack-connection':
            print(f"Fasttrack Rule found: chain={f.get('chain')} action={f.get('action')} bytes={f.get('bytes')}")
