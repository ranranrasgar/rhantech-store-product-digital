import os
import re
import glob

exclude_dirs = ['tenant', 'admin', 'auth']
base_dir = 'resources/views'

files_to_revert = []
for root, dirs, files in os.walk(base_dir):
    # Exclude directories
    dirs[:] = [d for d in dirs if d not in exclude_dirs]
    for file in files:
        if file.endswith('.blade.php'):
            files_to_revert.append(os.path.join(root, file))

prefixes = ['text', 'bg', 'border', 'ring', 'shadow', 'from', 'via', 'to', 'decoration', 'accent', 'caret', 'fill', 'stroke']

def replace_colors(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content
    
    # 1. Replace orange with sky
    for prefix in prefixes:
        pattern = rf"\b{prefix}-orange-([0-9]+)\b"
        content = re.sub(pattern, rf"{prefix}-sky-\1", content)
    
    # 2. Replace secondary with primary
    for prefix in prefixes:
        pattern = rf"\b{prefix}-secondary\b"
        content = re.sub(pattern, f"{prefix}-primary", content)
        
        pattern2 = rf"\b{prefix}-secondary/"
        content = re.sub(pattern2, f"{prefix}-primary/", content)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Reverted colors in {filepath}")

for fp in files_to_revert:
    replace_colors(fp)
