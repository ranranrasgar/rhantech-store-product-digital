import urllib.request, urllib.error, json
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

print('--- Hotspot Active ---')
hs = get_mikrotik('ip/hotspot/active')
print(f'Count: {len(hs) if isinstance(hs, list) else hs}')

print('--- PPPoE Active ---')
ppp = get_mikrotik('ppp/active')
print(f'Count: {len(ppp) if isinstance(ppp, list) else ppp}')

print('--- Interfaces (Top 5 RX) ---')
ifaces = get_mikrotik('interface')
if isinstance(ifaces, list):
    for i in ifaces:
        i['rx-byte'] = int(i.get('rx-byte', 0))
    ifaces = sorted(ifaces, key=lambda x: x['rx-byte'], reverse=True)[:5]
    for i in ifaces:
        name = i.get('name')
        rx = int(i.get('rx-byte',0))/1024/1024
        tx = int(i.get('tx-byte',0))/1024/1024
        type_ = i.get('type')
        print(f"{name}: RX {rx:.2f}MB, TX {tx:.2f}MB, Type: {type_}")

print('--- Latest Logs ---')
logs = get_mikrotik('log')
if isinstance(logs, list):
    for l in logs[-10:]:
        print(f"{l.get('time')} - {l.get('topics')} - {l.get('message')}")

print('--- Ping 8.8.8.8 from Router ---')
try:
    url = 'http://192.168.101.7/rest/ping'
    data = json.dumps({'address': '8.8.8.8', 'count': 4}).encode('utf-8')
    req = urllib.request.Request(url, data=data, method='POST')
    auth = b64encode(b'admin:1').decode('ascii')
    req.add_header('Authorization', 'Basic ' + auth)
    req.add_header('Content-Type', 'application/json')
    res = urllib.request.urlopen(req, timeout=10)
    print(res.read().decode('utf-8'))
except Exception as e:
    print('Ping failed:', e)
